<?php

namespace Models;

class Payment extends Database {
    protected static $table = 'payments';
    protected static $columns = ['id', 'treatment_id', 'amount_paid', 'payment_date'];

    public $id;
    public $treatment_id;
    public $amount_paid;
    public $payment_date;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->treatment_id = $args['treatment_id'] ?? '';
        $this->amount_paid = $args['amount_paid'] ?? '';
        $this->payment_date = $args['payment_date'] ?? date('Y-m-d H:i:s');
    }

    public function validate() {
        self::$alerts = [];
        if (!$this->treatment_id) {
            self::$alerts['error'][] = 'El tratamiento es obligatorio';
        }
        if (!$this->amount_paid || $this->amount_paid <= 0) {
            self::$alerts['error'][] = 'El monto debe ser mayor a 0';
        }
        return self::$alerts;
    }

    public static function findByTreatment($treatmentId) {
        $query = "SELECT * FROM payments WHERE treatment_id = ? ORDER BY payment_date DESC";
        return self::preparedQuery($query, 'i', $treatmentId);
    }

    public static function totalPaidByTreatment($treatmentId) {
        $query = "SELECT COALESCE(SUM(amount_paid), 0) AS total FROM payments WHERE treatment_id = ?";
        $result = self::customQuery($query, false, 'i', $treatmentId);
        return !empty($result) ? (float)$result[0]->total : 0;
    }
}
