<?php

namespace Models;

class Attachment extends Database {
    protected static $table = 'attachments';
    protected static $columns = ['id', 'patient_id', 'file_name', 'file_path', 'uploaded_at'];

    public $id;
    public $patient_id;
    public $file_name;
    public $file_path;
    public $uploaded_at;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->patient_id = $args['patient_id'] ?? '';
        $this->file_name = $args['file_name'] ?? '';
        $this->file_path = $args['file_path'] ?? '';
        $this->uploaded_at = $args['uploaded_at'] ?? date('Y-m-d H:i:s');
    }

    public function validate() {
        self::$alerts = [];
        if (!$this->patient_id) {
            self::$alerts['error'][] = 'El paciente es obligatorio';
        }
        if (!$this->file_name) {
            self::$alerts['error'][] = 'El archivo es obligatorio';
        }
        return self::$alerts;
    }

    public static function findByPatient($patientId) {
        $query = "SELECT * FROM attachments WHERE patient_id = ? ORDER BY uploaded_at DESC";
        return self::preparedQuery($query, 'i', $patientId);
    }
}
