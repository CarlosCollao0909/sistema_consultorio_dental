<?php

namespace Models;

class Patient extends Database {
    protected static $table = 'patients';
    protected static $columns = ['id', 'name', 'last_name', 'phone', 'birth_date', 'medical_notes', 'allergies', 'status'];

    public $id;
    public $name;
    public $last_name;
    public $phone;
    public $birth_date;
    public $medical_notes;
    public $allergies;
    public $status;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->last_name = $args['last_name'] ?? '';
        $this->phone = $args['phone'] ?? '';
        $this->birth_date = $args['birth_date'] ?? '';
        $this->medical_notes = $args['medical_notes'] ?? '';
        $this->allergies = $args['allergies'] ?? '';
        $this->status = $args['status'] ?? '1';
    }

    public function validate() {
        if (!$this->name) {
            self::$alerts['error'][] = 'El nombre es obligatorio';
        }
        if (!$this->last_name) {
            self::$alerts['error'][] = 'El apellido es obligatorio';
        }
        if (!$this->phone) {
            self::$alerts['error'][] = 'El teléfono es obligatorio';
        }
        if (!$this->birth_date) {
            self::$alerts['error'][] = 'La fecha de nacimiento es obligatoria';
        }
        return self::$alerts;
    }

    public static function findActive($id) {
        $query = "SELECT * FROM " . static::$table . " WHERE status = '1' AND id = ?";
        $result = self::preparedQuery($query, 'i', $id);
        // debug($result);
        return array_shift($result);
    }
}
