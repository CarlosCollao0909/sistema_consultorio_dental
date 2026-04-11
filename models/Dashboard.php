<?php

namespace Models;

class Dashboard extends Database {

    public static function getKpis($userId) {
        $query = "SELECT
            (SELECT COUNT(*) FROM patients WHERE user_id = ? AND status = '1') AS patients_count,
            (SELECT COUNT(*) FROM appointments a
                INNER JOIN treatments t ON a.treatment_id = t.id
                WHERE t.user_id = ? AND a.date = CURDATE()) AS today_appointments,
            (SELECT COALESCE(SUM(p.amount_paid), 0) FROM payments p
                INNER JOIN treatments t ON p.treatment_id = t.id
                WHERE t.user_id = ? AND MONTH(p.payment_date) = MONTH(CURDATE())
                AND YEAR(p.payment_date) = YEAR(CURDATE())) AS monthly_revenue,
            (SELECT COUNT(*) FROM treatments
                WHERE user_id = ? AND status = 'in_progress' AND active = '1') AS active_treatments";

        $result = self::customQuery($query, false, 'iiii', $userId, $userId, $userId, $userId);
        return !empty($result) ? $result[0] : null;
    }

    public static function getMonthlyRevenue($userId) {
        $query = "SELECT DATE_FORMAT(p.payment_date, '%Y-%m') AS month,
                    SUM(p.amount_paid) AS total
                  FROM payments p
                  INNER JOIN treatments t ON p.treatment_id = t.id
                  WHERE t.user_id = ?
                    AND p.payment_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                  GROUP BY month
                  ORDER BY month";

        return self::customQuery($query, false, 'i', $userId);
    }

    public static function getAppointmentsByStatus($userId) {
        $query = "SELECT a.status, COUNT(*) AS count
                  FROM appointments a
                  INNER JOIN treatments t ON a.treatment_id = t.id
                  WHERE t.user_id = ?
                  GROUP BY a.status";

        return self::customQuery($query, false, 'i', $userId);
    }

    public static function getTreatmentsBySpecialty($userId) {
        $query = "SELECT s.specialty_name, COUNT(*) AS count
                  FROM treatments t
                  INNER JOIN specialties s ON t.specialty_id = s.id
                  WHERE t.user_id = ?
                    AND t.active = '1'
                  GROUP BY t.specialty_id, s.specialty_name";

        return self::customQuery($query, false, 'i', $userId);
    }
}
