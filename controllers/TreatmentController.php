<?php

namespace Controllers;

use MVC\Router;
use Models\Treatment;

class TreatmentController {
    public static function index(Router $router) {
        isStartedSession();
        isAuth();
        $router->render('admin/treatments/index');
    }

    public static function create() {
        isStartedSession();
        isAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patientId = filter_var($_POST['patient_id'] ?? '', FILTER_VALIDATE_INT);
            validateRedirect($patientId, '/admin/patients');

            $treatment = new Treatment($_POST);
            $alerts = $treatment->validate();

            if (empty($alerts)) {
                $result = $treatment->create();
                if ($result) {
                    header("Location: /admin/patients/profile?id=$patientId&treatment_created=1");
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

            $treatment = Treatment::find($id);
            validateRedirect($treatment, "/admin/patients/profile?id=$patientId");

            $treatment->synchronize($_POST);
            $alerts = $treatment->validate();

            if (empty($alerts)) {
                $result = $treatment->update();
                if ($result) {
                    header("Location: /admin/patients/profile?id=$patientId&treatment_updated=1");
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

            $treatment = Treatment::find($id);
            validateRedirect($treatment, "/admin/patients/profile?id=$patientId");

            $result = $treatment->delete();
            if ($result) {
                header("Location: /admin/patients/profile?id=$patientId&treatment_deleted=1");
                exit;
            }
        }
    }
}