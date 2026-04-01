<?php

namespace Controllers;

use MVC\Router;
use Models\Patient;

class PatientController {
    public static function index(Router $router) {
        isStartedSession();
        isAuth();
        $patients = Patient::where('status', '1', true);
        $router->render('admin/patients/index', [
            'patients' => $patients
        ]);
    }

    public static function create() {
        isStartedSession();
        isAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patient = new Patient;
            $patient->synchronize($_POST);
            $alerts = $patient->validate();

            if (empty($alerts)) {
                $result = $patient->create();

                if ($result) {
                    header('Location: /admin/patients?patient_created=1');
                    exit;
                }
            }

            header('Location: /admin/patients?patient_created=0');
            exit;
        }
    }

    public static function update() {
        isStartedSession();
        isAuth();

        $id = $_GET['id'] ?? '';
        $id = filter_var($id, FILTER_VALIDATE_INT);
        validateRedirect($id, '/admin/patients');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patient = Patient::find($id);
            validateRedirect($patient, '/admin/patients');

            $patient->synchronize($_POST);
            $alerts = $patient->validate();

            if (empty($alerts)) {
                $result = $patient->update();

                if ($result) {
                    header('Location: /admin/patients?patient_updated=1');
                    exit;
                }
            }

            header('Location: /admin/patients?patient_updated=0');
            exit;
        }
    }

    public static function delete() {
        isStartedSession();
        isAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';

            /** @var Patient $patient */
            $patient = Patient::find($id);
            $patient->status = '0';
            $result = $patient->update();

            if ($result) {
                header('Location: /admin/patients?patient_deleted=1');
                exit;
            }
        }

        header('Location: /admin/patients?patient_deleted=0');
        exit;
    }
}
