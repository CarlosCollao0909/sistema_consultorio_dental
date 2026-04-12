<?php

namespace Controllers;

use Dompdf\Dompdf;
use MVC\Router;
use Models\Patient;
use Models\Payment;
use Models\Treatment;

class PaymentController {
    public static function index(Router $router) {
        isStartedSession();
        isAuth();
        $router->render('admin/payments/index');
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

        $balance = Treatment::getBalance($treatmentId);
        $alerts = [];
        $payment = new Payment(['treatment_id' => $treatmentId]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payment->synchronize($_POST);
            $alerts = $payment->validate();

            if (empty($alerts)) {
                if ((float)$payment->amount_paid > $balance) {
                    Payment::setAlert('error', 'El monto excede el balance pendiente (Bs. ' . number_format($balance, 2) . ')');
                    $alerts = Payment::getAlerts();
                } else {
                    $payment->payment_date = date('Y-m-d H:i:s');
                    $result = $payment->create();
                    if ($result) {
                        header("Location: /admin/patients/profile?id=$patientId&payment_created=1");
                        exit;
                    }
                }
            }
        }

        $router->render('admin/payments/create', [
            'alerts' => $alerts,
            'payment' => $payment,
            'treatment' => $treatment,
            'balance' => $balance,
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

            $payment = Payment::find($id);
            validateRedirect($payment, "/admin/patients/profile?id=$patientId");

            $result = $payment->delete();
            if ($result) {
                header("Location: /admin/patients/profile?id=$patientId&payment_deleted=1");
                exit;
            }
        }
    }

    public static function receipt() {
        isStartedSession();
        isAuth();

        $treatmentId = filter_var($_GET['treatment_id'] ?? '', FILTER_VALIDATE_INT);
        validateRedirect($treatmentId, '/admin/patients');

        // Load treatment with doctor and specialty names
        /** @var Treatment $treatment  */
        $treatment = Treatment::find($treatmentId);
        validateRedirect($treatment, '/admin/patients');

        // Load patient and verify ownership
        $patient = Patient::where(['id' => $treatment->patient_id, 'user_id' => $_SESSION['id'], 'status' => '1']);
        validateRedirect($patient, '/admin/patients');

        // Load doctor_name and specialty_name via the JOIN query
        $treatmentFull = Treatment::findByPatient($patient->id);
        foreach ($treatmentFull as $t) {
            if ((int)$t->id === (int)$treatmentId) {
                $treatment->doctor_name = $t->doctor_name;
                $treatment->specialty_name = $t->specialty_name;
                break;
            }
        }

        // Load payments and calculate totals
        $payments = Payment::findByTreatment($treatmentId);
        $totalPaid = Payment::totalPaidByTreatment($treatmentId);
        $balance = (float)$treatment->total_cost - $totalPaid;
        $clinic = getClinicInfo();

        // Render receipt HTML
        ob_start();
        include __DIR__ . '/../views/admin/payments/receipt.php';
        $html = ob_get_clean();

        // Generate PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter', 'landscape');
        $dompdf->render();

        $fileName = 'Recibo_' . preg_replace('/[^A-Za-z0-9_]/', '_', $treatment->treatment_name) . '_' . date('Ymd') . '.pdf';
        $dompdf->stream($fileName, ['Attachment' => false]);
        exit;
    }
}