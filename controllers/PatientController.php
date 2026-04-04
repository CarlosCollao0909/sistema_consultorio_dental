<?php

namespace Controllers;

use MVC\Router;
use Models\Patient;
use Models\Treatment;
use Models\Appointment;
use Models\Payment;
use Models\Attachment;

class PatientController {
    public static function index(Router $router) {
        isStartedSession();
        isAuth();
        $patients = Patient::where('status', '1', true);
        $router->render('admin/patients/index', [
            'patients' => $patients
        ]);
    }

    public static function create(Router $router) {
        isStartedSession();
        isAuth();

        $alerts = [];
        $patient = new Patient;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patient->synchronize($_POST);
            $alerts = $patient->validate();

            if (empty($alerts)) {
                $result = $patient->create();

                if ($result) {
                    header('Location: /admin/patients?patient_created=1');
                    exit;
                }
            }
        }

        $router->render('admin/patients/create', [
            'alerts' => $alerts,
            'patient' => $patient
        ]);
    }

    public static function update(Router $router) {
        isStartedSession();
        isAuth();

        $id = $_GET['id'] ?? '';
        $id = filter_var($id, FILTER_VALIDATE_INT);
        validateRedirect($id, '/admin/patients');

        $patient = Patient::find($id);
        validateRedirect($patient, '/admin/patients');

        $alerts = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patient->synchronize($_POST);
            $alerts = $patient->validate();

            if (empty($alerts)) {
                $result = $patient->update();

                if ($result) {
                    header('Location: /admin/patients?patient_updated=1');
                    exit;
                }
            }
        }

        $router->render('admin/patients/update', [
            'alerts' => $alerts,
            'patient' => $patient
        ]);
    }

    public static function delete() {
        isStartedSession();
        isAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $id = filter_var($id, FILTER_VALIDATE_INT);
            validateRedirect($id, '/admin/patients');

            /** @var Patient $patient */
            $patient = Patient::find($id);
            validateRedirect($patient, '/admin/patients');
            
            $patient->status = '0';
            $result = $patient->update();

            if ($result) {
                header('Location: /admin/patients?patient_deleted=1');
                exit;
            }
        }
    }

    public static function showProfile(Router $router) {
        isStartedSession();
        isAuth();

        $id = $_GET['id'] ?? '';
        $id = filter_var($id, FILTER_VALIDATE_INT);
        validateRedirect($id, '/admin/patients');

        $patient = Patient::findActive($id);
        validateRedirect($patient, '/admin/patients');

        // Load treatments with doctor and specialty names
        $treatments = Treatment::findByPatient($id);

        // For each treatment, load appointments, payments and calculate balance
        foreach ($treatments as $treatment) {
            $treatment->appointments = Appointment::findByTreatment($treatment->id);
            $treatment->payments = Payment::findByTreatment($treatment->id);
            $treatment->total_paid = Payment::totalPaidByTreatment($treatment->id);
            $treatment->balance = (float)$treatment->total_cost - $treatment->total_paid;
        }

        // Load attachments
        $attachments = Attachment::findByPatient($id);

        $router->render('admin/patients/profile', [
            'patient' => $patient,
            'treatments' => $treatments,
            'attachments' => $attachments
        ]);
    }
}
