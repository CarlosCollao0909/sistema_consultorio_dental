<?php

namespace Controllers;

use MVC\Router;
use Models\Appointment;
use Models\Treatment;

class AppointmentController {
    public static function index(Router $router) {
        isStartedSession();
        isAuth();
        $router->render('admin/appointments/index');
    }

    public static function create(Router $router) {
        isStartedSession();
        isAuth();

        $patientId = filter_var($_GET['patient_id'] ?? $_POST['patient_id'] ?? '', FILTER_VALIDATE_INT);
        validateRedirect($patientId, '/admin/patients');

        $treatmentId = filter_var($_GET['treatment_id'] ?? $_POST['treatment_id'] ?? '', FILTER_VALIDATE_INT);
        validateRedirect($treatmentId, "/admin/patients/profile?id=$patientId");

        /** @var Treatment $treatment */
        $treatment = Treatment::find($treatmentId);
        validateRedirect($treatment, "/admin/patients/profile?id=$patientId");

        $alerts = [];
        $appointment = new Appointment(['treatment_id' => $treatmentId]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointment->synchronize($_POST);
            $alerts = $appointment->validate();

            if (empty($alerts)) {
                $result = $appointment->create();
                if ($result) {
                    header("Location: /admin/patients/profile?id=$patientId&appointment_created=1");
                    exit;
                }
            }
        }

        $router->render('admin/appointments/create', [
            'alerts' => $alerts,
            'appointment' => $appointment,
            'treatment' => $treatment,
            'patientId' => $patientId
        ]);
    }

    public static function update(Router $router) {
        isStartedSession();
        isAuth();

        $id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
        validateRedirect($id, '/admin/patients');

        /** @var Appointment $appointment */
        $appointment = Appointment::find($id);
        validateRedirect($appointment, '/admin/patients');

        /** @var Treatment $treatment */
        $treatment = Treatment::find($appointment->treatment_id);
        validateRedirect($treatment, '/admin/patients');

        $patientId = $treatment->patient_id;
        $alerts = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointment->synchronize($_POST);
            $alerts = $appointment->validate();

            if (empty($alerts)) {
                $result = $appointment->update();
                if ($result) {
                    header("Location: /admin/patients/profile?id=$patientId&appointment_updated=1");
                    exit;
                }
            }
        }

        $router->render('admin/appointments/update', [
            'alerts' => $alerts,
            'appointment' => $appointment,
            'treatment' => $treatment,
            'patientId' => $patientId
        ]);
    }

    public static function getAppointments() {
        isStartedSession();
        isAuth();

        $date = $_GET['date'] ?? null;

        if ($date) {
            $dates = explode('-', $date);
            
            if (count($dates) !== 3 || !checkdate((int)$dates[1], (int)$dates[2], (int)$dates[0])) {
                echo json_encode(['error' => 'Fecha no válida']);
                return;
            }

            $appointments = Appointment::getAppointments($date);
        } else {
            $appointments = Appointment::getAppointments();
        }
        
        header('Content-Type: application/json');
        echo json_encode($appointments);
    }

    public static function delete() {
        isStartedSession();
        isAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patientId = filter_var($_POST['patient_id'] ?? '', FILTER_VALIDATE_INT);
            validateRedirect($patientId, '/admin/patients');

            $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
            validateRedirect($id, "/admin/patients/profile?id=$patientId");

            $appointment = Appointment::find($id);
            validateRedirect($appointment, "/admin/patients/profile?id=$patientId");

            $result = $appointment->delete();
            if ($result) {
                header("Location: /admin/patients/profile?id=$patientId&appointment_deleted=1");
                exit;
            }
        }
    }
}