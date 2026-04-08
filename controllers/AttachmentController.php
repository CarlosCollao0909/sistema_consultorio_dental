<?php

namespace Controllers;

use MVC\Router;
use Models\Attachment;

class AttachmentController {
    private const UPLOAD_DIR = __DIR__ . '/../storage/PDFs/';

    public static function create(Router $router) {
        isStartedSession();
        isAuth();

        $patientId = filter_var($_GET['patient_id'] ?? $_POST['patient_id'] ?? '', FILTER_VALIDATE_INT);
        validateRedirect($patientId, '/admin/patients');

        $alerts = [];
        $attachment = new Attachment;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Detect when PHP discards $_POST and $_FILES due to post_max_size exceeded
            $contentLength = isset($_SERVER['CONTENT_LENGTH']) ? (int) $_SERVER['CONTENT_LENGTH'] : 0;
            $postMaxBytes = self::parseSize(ini_get('post_max_size'));

            if ($contentLength > $postMaxBytes) {
                Attachment::setAlert('error', 'El archivo no debe superar 5MB');
                $alerts = Attachment::getAlerts();
            } else {
                $attachment->synchronize([
                    'patient_id' => $patientId,
                    'file_name' => $_POST['file_name'] ?? '',
                    'file' => $_FILES['attachment'] ?? null
                ]);

                $alerts = $attachment->validate();

                if (empty($alerts)) {
                    $hashedName = bin2hex(random_bytes(16)) . '.pdf';
                    $filePath = self::UPLOAD_DIR . $hashedName;

                    if (move_uploaded_file($attachment->file['tmp_name'], $filePath)) {
                        $attachment->file_path = $hashedName;
                        if (!str_ends_with(strtolower($attachment->file_name), '.pdf')) {
                            $attachment->file_name .= '.pdf';
                        }
                        
                        $result = $attachment->create();

                        if ($result) {
                            header("Location: /admin/patients/profile?id=$patientId&attachment_uploaded=1");
                            exit;
                        }

                        unlink($filePath);
                        Attachment::setAlert('error', 'Hubo un error al guardar el archivo, por favor intente nuevamente');
                        $alerts = Attachment::getAlerts();
                    } else {
                        Attachment::setAlert('error', 'Hubo un error al subir el archivo, por favor intente nuevamente');
                        $alerts = Attachment::getAlerts();
                    }
                }
            }
        }

        $router->render('admin/attachments/create', [
            'alerts' => $alerts,
            'patientId' => $patientId,
            'attachment' => $attachment
        ]);
    }

    private static function parseSize(string $size): int {
        $unit = strtolower(substr(trim($size), -1));
        $value = (int) $size;
        return match ($unit) {
            'g' => $value * 1024 * 1024 * 1024,
            'm' => $value * 1024 * 1024,
            'k' => $value * 1024,
            default => $value,
        };
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

    public static function show() {
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
        header('Content-Disposition: inline; filename="' . $attachment->file_name . '"');
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
