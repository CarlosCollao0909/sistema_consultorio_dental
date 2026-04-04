<?php

namespace Controllers;

use MVC\Router;
use Models\Payment;
use Models\Treatment;

class PaymentController {
    public static function index(Router $router) {
        isStartedSession();
        isAuth();
        $router->render('admin/payments/index');
    }

    public static function create() {
        isStartedSession();
        isAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patientId = filter_var($_POST['patient_id'] ?? '', FILTER_VALIDATE_INT);
            validateRedirect($patientId, '/admin/patients');

            $payment = new Payment($_POST);
            $alerts = $payment->validate();

            if (empty($alerts)) {
                // Validate payment doesn't exceed balance
                $balance = Treatment::getBalance($payment->treatment_id);
                if ((float)$payment->amount_paid > $balance) {
                    header("Location: /admin/patients/profile?id=$patientId");
                    exit;
                }

                $payment->payment_date = date('Y-m-d H:i:s');
                $result = $payment->create();
                if ($result) {
                    header("Location: /admin/patients/profile?id=$patientId&payment_created=1");
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

            $payment = Payment::find($id);
            validateRedirect($payment, "/admin/patients/profile?id=$patientId");

            $result = $payment->delete();
            if ($result) {
                header("Location: /admin/patients/profile?id=$patientId&payment_deleted=1");
                exit;
            }
        }
    }
}