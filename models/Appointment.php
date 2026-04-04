<?php

namespace Models;

class Appointment extends Database {
    protected static $table = 'appointments';
    protected static $columns = ['id', 'treatment_id', 'date', 'time', 'status', 'observations'];

    public $id;
    public $treatment_id;
    public $date;
    public $time;
    public $status;
    public $observations;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->treatment_id = $args['treatment_id'] ?? '';
        $this->date = $args['date'] ?? '';
        $this->time = $args['time'] ?? '';
        $this->status = $args['status'] ?? 'programada';
        $this->observations = $args['observations'] ?? '';
    }

    public function validate() {
        self::$alerts = [];
        if (!$this->treatment_id) {
            self::$alerts['error'][] = 'El tratamiento es obligatorio';
        }
        if (!$this->date) {
            self::$alerts['error'][] = 'La fecha es obligatoria';
        }
        if (!$this->time) {
            self::$alerts['error'][] = 'La hora es obligatoria';
        }
        return self::$alerts;
    }

    public static function findByTreatment($treatmentId) {
        $query = "SELECT * FROM appointments WHERE treatment_id = ? ORDER BY date DESC, time DESC";
        return self::preparedQuery($query, 'i', $treatmentId);
    }
}
