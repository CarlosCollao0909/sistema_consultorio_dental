<?php

namespace Models;

use stdClass;

/** @property int $id */

class Database {
    protected static $db;
    protected static $table;
    protected static $columns = [];
    protected static $alerts = [];

    public static function setDB($database) {
        self::$db = $database;
    }

    public static function setAlert($type, $message) {
        static::$alerts[$type][] = $message;
    }

    public static function getAlerts() {
        return static::$alerts;
    }

    public function validate() {
        static::$alerts = [];
        return static::$alerts;
    }

    public static function customQuery($query, $model = false, $types = '', ...$params) {
        if ($types && $params) {
            $stmt = self::$db->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = self::$db->query($query);
        }

        $objectsArray = [];
        while ($row = $result->fetch_assoc()) {
            $object = $model ? new static : new stdClass;
            foreach ($row as $key => $value) {
                $object->$key = $value;
            }
            $objectsArray[] = $object;
        }

        $result->free();
        if (isset($stmt))
            $stmt->close();

        return $objectsArray;
    }

    public static function querySQL($query) {
        $result = self::$db->query($query);

        $array = [];
        while ($row = $result->fetch_assoc()) {
            $array[] = static::createObject($row);
        }

        $result->free();

        return $array;
    }

    protected static function preparedQuery($query, $types = '', ...$params) {
        $stmt = self::$db->prepare($query);

        if ($types && $params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $array = [];
        while ($row = $result->fetch_assoc()) {
            $array[] = static::createObject($row);
        }

        $result->free();
        $stmt->close();

        return $array;
    }

    protected static function createObject($row) {
        $object = new static;

        foreach ($row as $key => $value) {
            if (property_exists($object, $key)) {
                $object->$key = $value;
            }
        }

        return $object;
    }

    public function attributes() {
        $attributes = [];

        foreach (static::$columns as $column) {
            if ($column === 'id')
                continue;
            $attributes[$column] = $this->$column;
        }

        return $attributes;
    }

    protected static function getParamType($value): string {
        if (is_int($value))
            return 'i';
        if (is_float($value))
            return 'd';
        return 's';
    }

    public function synchronize($args = []) {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    public static function all() {
        $query = "SELECT * FROM " . static::$table;
        $result = self::querySQL($query);
        return $result;
    }

    public static function find($id) {
        $query = "SELECT * FROM " . static::$table . " WHERE id = ?";
        $result = self::preparedQuery($query, 'i', $id);
        return array_shift($result);
    }

    public static function where($column, $value = null, $all = false) {
        $allowed = static::$columns;

        // Modo array: where(['status' => '1', 'name' => 'Juan'])
        if (is_array($column)) {
            $filters = $column;
            $all = $value ?? false; // segundo parámetro pasa a ser $all

            $clauses = [];
            $types = '';
            $values = [];

            foreach ($filters as $col => $val) {
                if (!in_array($col, $allowed)) {
                    throw new \InvalidArgumentException("Columna no permitida: $col");
                }
                $clauses[] = "$col = ?";
                $types .= self::getParamType($val);
                $values[] = $val;
            }

            $query = "SELECT * FROM " . static::$table . " WHERE " . join(' AND ', $clauses);
            $result = self::preparedQuery($query, $types, ...$values);
            return $all ? $result : array_shift($result);
        }

        // Modo simple: where('username', 'admin')
        if (!in_array($column, $allowed)) {
            throw new \InvalidArgumentException("Columna no permitida: $column");
        }
        $query = "SELECT * FROM " . static::$table . " WHERE $column = ?";
        $type = self::getParamType($value);
        $result = self::preparedQuery($query, $type, $value);
        return $all ? $result : array_shift($result);
    }

    public static function pluckColumn($column, $key, $value) {
        $allowed = static::$columns;
        if (!in_array($column, $allowed) || !in_array($key, $allowed)) {
            throw new \InvalidArgumentException("Columna no permitida");
        }
        $query = "SELECT $column, $key FROM " . static::$table . " WHERE $key = ?";
        $type = self::getParamType($value);

        $stmt = self::$db->prepare($query);
        $stmt->bind_param($type, $value);
        $stmt->execute();
        $result = $stmt->get_result();

        $array = [];
        while ($row = $result->fetch_assoc()) {
            $array[$row[$key]] = $row[$column];
        }

        $result->free();
        $stmt->close();

        return $array;
    }

    public static function get($limit) {
        $query = "SELECT * FROM " . static::$table . " LIMIT ?";
        $result = self::preparedQuery($query, 'i', (int) $limit);
        return $result;
    }

    public function create() {
        $attributes = $this->attributes();
        $columns = array_keys($attributes);
        $values = array_values($attributes);
        $placeholders = array_fill(0, count($values), '?');
        $types = implode('', array_map([self::class, 'getParamType'], $values));

        $query = "INSERT INTO " . static::$table . " (";
        $query .= join(', ', $columns);
        $query .= ") VALUES (";
        $query .= join(', ', $placeholders);
        $query .= ")";

        $stmt = self::$db->prepare($query);
        $stmt->bind_param($types, ...$values);
        $result = $stmt->execute();
        $id = self::$db->insert_id;
        $stmt->close();

        return [
            'result' => $result,
            'id' => $id
        ];
    }

    public function update() {
        $attributes = $this->attributes();
        $columns = array_keys($attributes);
        $values = array_values($attributes);

        $setClauses = array_map(fn($col) => "$col = ?", $columns);

        $query = "UPDATE " . static::$table . " SET ";
        $query .= join(', ', $setClauses);
        $query .= " WHERE id = ? LIMIT 1";

        $values[] = $this->id;
        $types = implode('', array_map([self::class, 'getParamType'], $values));

        $stmt = self::$db->prepare($query);
        $stmt->bind_param($types, ...$values);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function delete() {
        $query = "DELETE FROM " . static::$table . " WHERE id = ? LIMIT 1";

        $stmt = self::$db->prepare($query);
        $stmt->bind_param('i', $this->id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
}