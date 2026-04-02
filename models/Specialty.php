<?php

namespace Models;

class Specialty extends Database {
    protected static $table = 'specialties';
    protected static $columns = ['id', 'specialty_name', 'description', 'status'];

    public $id;
    public $specialty_name;
    public $description;
    public $status;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->specialty_name = $args['name'] ?? '';
        $this->description = $args['description'] ?? '';
        $this->status = $args['status'] ?? '1';
    }

    public function validate() {
        if (!$this->specialty_name) {
            self::$alerts['error'][] = 'El nombre de la especialidad es obligatorio';
        }
        if (!$this->description) {
            self::$alerts['error'][] = 'La descripción es obligatoria';
        }
        return self::$alerts;
    }
}