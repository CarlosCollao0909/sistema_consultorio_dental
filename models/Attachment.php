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
    public $file;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->patient_id = $args['patient_id'] ?? '';
        $this->file_name = $args['file_name'] ?? '';
        $this->file_path = $args['file_path'] ?? '';
        $this->uploaded_at = $args['uploaded_at'] ?? date('Y-m-d H:i:s');
        $this->file = $args['file'] ?? null;
    }

    public function validate() {
        self::$alerts = [];
        if (!$this->patient_id) {
            self::$alerts['error'][] = 'El paciente es obligatorio';
        }
        if (!$this->file_name) {
            self::$alerts['error'][] = 'El nombre del archivo es obligatorio';
        }
        if (!$this->file || $this->file['error'] === UPLOAD_ERR_NO_FILE) {
            self::$alerts['error'][] = 'Debe seleccionar un archivo PDF';
        } elseif ($this->file['error'] === UPLOAD_ERR_INI_SIZE || $this->file['error'] === UPLOAD_ERR_FORM_SIZE) {
            self::$alerts['error'][] = 'El archivo no debe superar 5MB';
        } elseif ($this->file['error'] !== UPLOAD_ERR_OK) {
            self::$alerts['error'][] = 'Hubo un error al subir el archivo';
        } else {
            // validate MIME type
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($this->file['tmp_name']);

            if ($mimeType !== 'application/pdf') {
                self::$alerts['error'][] = 'El archivo debe ser un PDF';
            } elseif ($this->file['size'] > 5 * 1024 *1024) {
                self::$alerts['error'][] = 'El archivo no debe superar 5MB';
            }
        }
        return self::$alerts;
    }

    public static function findByPatient($patientId) {
        $query = "SELECT * FROM attachments WHERE patient_id = ? ORDER BY uploaded_at DESC";
        return self::preparedQuery($query, 'i', $patientId);
    }
}
