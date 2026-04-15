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
        isApiAuth();

        header('Content-Type: application/json');

        $date = $_GET['date'] ?? null;

        if ($date) {
            $dates = explode('-', $date);
            
            if (count($dates) !== 3 || !checkdate((int)$dates[1], (int)$dates[2], (int)$dates[0])) {
                http_response_code(400);
                echo json_encode(['error' => 'Fecha no válida']);
                return;
            }

            $appointments = Appointment::getAppointments($_SESSION['id'], $date);
        } else {
            $appointments = Appointment::getAppointments($_SESSION['id']);
        }

        echo json_encode($appointments);
    }

    public static function updateStatus() {
        isStartedSession();
        isApiAuth();

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
            $status = $_POST['status'] ?? '';
            $allowedStatuses = ['pending', 'completed', 'canceled'];

            if (!$id || !$status) {
                http_response_code(400);
                echo json_encode(['error' => 'Datos no válidos']);
                return;
            }

            if (!in_array($status, $allowedStatuses)) {
                http_response_code(400);
                echo json_encode(['error' => 'Estado no válido']);
                return;
            }

            // Verificar que la cita pertenece al usuario autenticado
            $appointments = Appointment::getAppointments($_SESSION['id']);
            $ownsAppointment = false;
            foreach ($appointments as $apt) {
                if ((int)$apt->id === $id) {
                    $ownsAppointment = true;
                    break;
                }
            }

            if (!$ownsAppointment) {
                http_response_code(403);
                echo json_encode(['error' => 'No tienes permiso para modificar esta cita']);
                return;
            }

            /** @var Appointment $appointment */
            $appointment = Appointment::find($id);

            $appointment->status = $status;
            $result = $appointment->update();

            if ($result) {
                echo json_encode(['success' => 'Estado actualizado']);
            } else {
                echo json_encode(['error' => 'Error al actualizar el estado']);
            }
        }
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