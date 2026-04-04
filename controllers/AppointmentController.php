<?php

namespace Controllers;

use MVC\Router;
use Models\Appointment;

class AppointmentController {
    public static function index(Router $router) {
        isStartedSession();
        isAuth();
        $router->render('admin/appointments/index');
    }

    public static function create() {
        isStartedSession();
        isAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patientId = filter_var($_POST['patient_id'] ?? '', FILTER_VALIDATE_INT);
            validateRedirect($patientId, '/admin/patients');

            $appointment = new Appointment($_POST);
            $alerts = $appointment->validate();

            if (empty($alerts)) {
                $result = $appointment->create();
                if ($result) {
                    header("Location: /admin/patients/profile?id=$patientId&appointment_created=1");
                    exit;
                }
            }

            header("Location: /admin/patients/profile?id=$patientId");
            exit;
        }
    }

    public static function update() {
        isStartedSession();
        isAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patientId = filter_var($_POST['patient_id'] ?? '', FILTER_VALIDATE_INT);
            validateRedirect($patientId, '/admin/patients');

            $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
            validateRedirect($id, "/admin/patients/profile?id=$patientId");

            $appointment = Appointment::find($id);
            validateRedirect($appointment, "/admin/patients/profile?id=$patientId");

            $appointment->synchronize($_POST);
            $alerts = $appointment->validate();

            if (empty($alerts)) {
                $result = $appointment->update();
                if ($result) {
                    header("Location: /admin/patients/profile?id=$patientId&appointment_updated=1");
                    exit;
                }
            }

            header("Location: /admin/patients/profile?id=$patientId");
            exit;
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