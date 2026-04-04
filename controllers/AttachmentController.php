<?php

namespace Controllers;

use MVC\Router;
use Models\Attachment;

class AttachmentController {
    private const UPLOAD_DIR = __DIR__ . '/../storage/PDFs/';
    private const MAX_SIZE = 5 * 1024 * 1024; // 5MB

    public static function create(Router $router) {
        isStartedSession();
        isAuth();

        $patientId = filter_var($_GET['patient_id'] ?? $_POST['patient_id'] ?? '', FILTER_VALIDATE_INT);
        validateRedirect($patientId, '/admin/patients');

        $alerts = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $file = $_FILES['attachment'] ?? null;

            if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
                Attachment::setAlert('error', 'Debe seleccionar un archivo PDF');
                $alerts = Attachment::getAlerts();
            } else {
                // Validate MIME type
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($file['tmp_name']);
                if ($mimeType !== 'application/pdf') {
                    Attachment::setAlert('error', 'El archivo debe ser un PDF');
                    $alerts = Attachment::getAlerts();
                } elseif ($file['size'] > self::MAX_SIZE) {
                    Attachment::setAlert('error', 'El archivo no debe superar 5MB');
                    $alerts = Attachment::getAlerts();
                } else {
                    $hashedName = md5(uniqid((string)rand(), true)) . '.pdf';
                    $originalName = basename($file['name']);
                    $destination = self::UPLOAD_DIR . $hashedName;

                    if (move_uploaded_file($file['tmp_name'], $destination)) {
                        $attachment = new Attachment([
                            'patient_id' => $patientId,
                            'file_name' => $originalName,
                            'file_path' => $hashedName,
                            'uploaded_at' => date('Y-m-d H:i:s')
                        ]);

                        $result = $attachment->create();
                        if ($result) {
                            header("Location: /admin/patients/profile?id=$patientId&attachment_uploaded=1");
                            exit;
                        }
                    }
                }
            }
        }

        $router->render('admin/attachments/create', [
            'alerts' => $alerts,
            'patientId' => $patientId
        ]);
    }

    public static function download() {
        isStartedSession();
        isAuth();

        $id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
        validateRedirect($id, '/admin/patients');

        /** @var Attachment $attachment */
        $attachment = Attachment::find($id);
        validateRedirect($attachment, '/admin/patients');

        $filePath = self::UPLOAD_DIR . $attachment->file_path;

        if (!file_exists($filePath)) {
            header("Location: /admin/patients/profile?id=$attachment->patient_id");
            exit;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $attachment->file_name . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    public static function delete() {
        isStartedSession();
        isAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patientId = filter_var($_POST['patient_id'] ?? '', FILTER_VALIDATE_INT);
            validateRedirect($patientId, '/admin/patients');

            $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
            validateRedirect($id, "/admin/patients/profile?id=$patientId");

            /** @var Attachment $attachment */
            $attachment = Attachment::find($id);
            validateRedirect($attachment, "/admin/patients/profile?id=$patientId");

            $filePath = self::UPLOAD_DIR . $attachment->file_path;
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $result = $attachment->delete();
            if ($result) {
                header("Location: /admin/patients/profile?id=$patientId&attachment_deleted=1");
                exit;
            }
        }
    }
}
