<?php

namespace Models;

use stdClass;

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

    public static function customQuery($query, $model = false) {
        $result = self::$db->query($query);
        $objectsArray = [];
        while ($row = $result->fetch_assoc()) {
            $objetc = $model ? new static : new stdClass;
            foreach ($row as $key => $value) {
                $objetc->$key = $value;
            }
            $objectsArray[] = $objetc;
        }

        $result->free();

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
            if ($column === 'id') continue;
            $attributes[$column] = $this->$column;
        }

        return $attributes;
    }

    public function sanitizeAttributes() {
        $attributes = $this->attributes();
        $sanitized = [];

        foreach ($attributes as $key => $value) {
            $sanitized[$key] = self::$db->escape_string($value);
        }

        return $sanitized;
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
        $query = "SELECT * FROM " . static::$table . " WHERE id = $id";
        $result = self::querySQL($query);
        return array_shift($result);
    }

    public static function where($column, $value, $all = false) {
        $query = "SELECT * FROM " . static::$table . " WHERE $column = '$value'";
        $result = self::querySQL($query);
        return $all ? $result : array_shift($result);
    }

    public static function pluckColumn($column, $key, $value) {
        $query = "SELECT $column FROM " . static::$table . " WHERE $key = '$value'";
        $result = self::$db->query($query);
        $array = [];

        while ($row = $result->fetch_assoc()) {
            $array[$row[$key]] = $row[$column];
        }

        return $array;
    }

    public static function get($limit) {
        $query = "SELECT * FROM " . static::$table . " LIMIT $limit";
        $result = self::querySQL($query);
        return $result;
    }

    public function create() {
        $attributes = $this->sanitizeAttributes();

        $query = "INSERT INTO " . static::$table . " (";
        $query .= join(', ', array_keys($attributes));
        $query .= ") VALUES ('";
        $query .= join("', '", array_values($attributes));
        $query .= "')";

        $result = self::$db->query($query);

        return [
            'result' => $result,
            'id' => self::$db->insert_id
        ];
    }

    public function update() {
        $attributes = $this->sanitizeAttributes();
        $values = [];

        foreach ($attributes as $key => $value) {
            $values[] = "{$key}='{$value}'";
        }

        $query = "UPDATE " . static::$table . " SET ";
        $query .= join(', ', $values);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "'";
        $query .= " LIMIT 1";

        $result = self::$db->query($query);

        return $result;
    }

    public function delete() {
        $query = "DELETE FROM " . static::$table . " WHERE id = '" . self::$db->escape_string($this->id) . "'";
        $query .= " LIMIT 1";

        $result = self::$db->query($query);

        return $result;
    }
}