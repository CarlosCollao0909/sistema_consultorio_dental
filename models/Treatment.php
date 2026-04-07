<?php

namespace Models;

class Treatment extends Database {
    protected static $table = 'treatments';
    protected static $columns = ['id', 'patient_id', 'user_id', 'specialty_id', 'treatment_name', 'total_cost', 'status'];

    public $id;
    public $patient_id;
    public $user_id;
    public $specialty_id;
    public $treatment_name;
    public $total_cost;
    public $status;

    // Loaded via JOIN (not in $columns, excluded from create/update)
    public $doctor_name;
    public $specialty_name;

    // Loaded via Controller for profile view
    public $appointments = [];
    public $payments = [];
    public $total_paid = 0;
    public $balance = 0.0;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->patient_id = $args['patient_id'] ?? '';
        $this->user_id = $args['user_id'] ?? '';
        $this->specialty_id = $args['specialty_id'] ?? '';
        $this->treatment_name = $args['treatment_name'] ?? '';
        $this->total_cost = $args['total_cost'] ?? '';
        $this->status = $args['status'] ?? 'pendiente';
    }

    public function validate() {
        self::$alerts = [];
        if (!$this->patient_id) {
            self::$alerts['error'][] = 'El paciente es obligatorio';
        }
        if (!$this->user_id) {
            self::$alerts['error'][] = 'El doctor es obligatorio';
        }
        if (!$this->specialty_id) {
            self::$alerts['error'][] = 'La especialidad es obligatoria';
        }
        if (!$this->treatment_name) {
            self::$alerts['error'][] = 'El nombre del tratamiento es obligatorio';
        }
        if (!$this->total_cost || $this->total_cost <= 0) {
            self::$alerts['error'][] = 'El costo total debe ser mayor a 0';
        }
        return self::$alerts;
    }

    public static function findByPatient($patientId) {
        $query = "SELECT t.*, 
                    CONCAT(u.name, ' ', u.last_name) AS doctor_name, 
                    s.specialty_name 
                  FROM treatments t 
                  INNER JOIN users u ON t.user_id = u.id 
                  INNER JOIN specialties s ON t.specialty_id = s.id 
                  WHERE t.patient_id = ? 
                  ORDER BY t.id DESC";
        return self::customQuery($query, true, 'i', $patientId);
    }

    public static function getBalance($treatmentId) {
        $query = "SELECT t.total_cost, COALESCE(SUM(p.amount_paid), 0) AS total_paid 
                  FROM treatments t 
                  LEFT JOIN payments p ON t.id = p.treatment_id 
                  WHERE t.id = ? 
                  GROUP BY t.id";
        $result = self::customQuery($query, false, 'i', $treatmentId);
        if (!empty($result)) {
            $row = $result[0];
            return (float)$row->total_cost - (float)$row->total_paid;
        }
        return 0;
    }
}
