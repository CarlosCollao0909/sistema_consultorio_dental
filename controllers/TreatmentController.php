<?php

namespace Controllers;

use MVC\Router;
use Models\Treatment;
use Models\User;
use Models\Specialty;

class TreatmentController {
    public static function index(Router $router) {
        isStartedSession();
        isAuth();
        $router->render('admin/treatments/index');
    }

    public static function create(Router $router) {
        isStartedSession();
        isAuth();

        $patientId = filter_var($_GET['patient_id'] ?? $_POST['patient_id'] ?? '', FILTER_VALIDATE_INT);
        validateRedirect($patientId, '/admin/patients');

        $alerts = [];
        $treatment = new Treatment(['patient_id' => $patientId, 'user_id' => $_SESSION['id']]);
        $specialties = Specialty::where('status', '1', true);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $treatment->synchronize($_POST);
            $alerts = $treatment->validate();

            if (empty($alerts)) {
                $result = $treatment->create();
                if ($result) {
                    header("Location: /admin/patients/profile?id=$patientId&treatment_created=1");
                    exit;
                }
            }
        }

        $router->render('admin/treatments/create', [
            'alerts' => $alerts,
            'treatment' => $treatment,
            'specialties' => $specialties,
            'patientId' => $patientId
        ]);
    }

    public static function update(Router $router) {
        isStartedSession();
        isAuth();

        $treatmentId = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
        validateRedirect($treatmentId, '/admin/patients');

        /** @var Treatment $treatment */
        $treatment = Treatment::find($treatmentId);
        validateRedirect($treatment, '/admin/patients');

        $patientId = $treatment->patient_id;
        $alerts = [];
        $doctors = User::all();
        $specialties = Specialty::where('status', '1', true);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $treatment->synchronize($_POST);
            $alerts = $treatment->validate();

            if (empty($alerts)) {
                $result = $treatment->update();
                if ($result) {
                    header("Location: /admin/patients/profile?id=$patientId&treatment_updated=1");
                    exit;
                }
            }
        }

        $router->render('admin/treatments/update', [
            'alerts' => $alerts,
            'treatment' => $treatment,
            'doctors' => $doctors,
            'specialties' => $specialties,
            'patientId' => $patientId
        ]);
    }

    public static function delete() {
        isStartedSession();
        isAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patientId = filter_var($_POST['patient_id'] ?? '', FILTER_VALIDATE_INT);
            validateRedirect($patientId, '/admin/patients');

            $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
            validateRedirect($id, "/admin/patients/profile?id=$patientId");

            /** @var Treatment $treatment */
            $treatment = Treatment::find($id);
            validateRedirect($treatment, "/admin/patients/profile?id=$patientId");

            $treatment->active = '0';
            $result = $treatment->update();
            if ($result) {
                header("Location: /admin/patients/profile?id=$patientId&treatment_deleted=1");
                exit;
            }
        }
    }
}