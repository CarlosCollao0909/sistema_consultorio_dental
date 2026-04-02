<?php

namespace Controllers;

use MVC\Router;
use Models\Specialty;

class SpecialtyController {
    public static function index(Router $router) {
        isStartedSession();
        isAuth();
        $specialties = Specialty::where('status', '1', true);
        $router->render('admin/specialties/index', [
            'specialties' => $specialties
        ]);
    }

    public static function create(Router $router) {
        isStartedSession();
        isAuth();

        $alerts = [];
        $specialty = new Specialty;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $specialty->synchronize($_POST);
            $alerts = $specialty->validate();

            if (empty($alerts)) {
                $result = $specialty->create();

                if ($result) {
                    header('Location: /admin/specialties?specialty_created=1');
                    exit;
                }
            }
        }

        $router->render('admin/specialties/create', [
            'alerts' => $alerts,
            'specialty' => $specialty
        ]);
    }

    public static function update(Router $router) {
        isStartedSession();
        isAuth();

        $id = $_GET['id'] ?? '';
        $id = filter_var($id, FILTER_VALIDATE_INT);
        validateRedirect($id, '/admin/specialties');

        $specialty = Specialty::find($id);
        validateRedirect($specialty, '/admin/specialties');

        $alerts = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $specialty->synchronize($_POST);
            $alerts = $specialty->validate();

            if (empty($alerts)) {
                $result = $specialty->update();

                if ($result) {
                    header('Location: /admin/specialties?specialty_updated=1');
                    exit;
                }
            }
        }

        $router->render('admin/specialties/update', [
            'alerts' => $alerts,
            'specialty' => $specialty
        ]);
    }

    public static function delete() {
        isStartedSession();
        isAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $id = filter_var($id, FILTER_VALIDATE_INT);
            validateRedirect($id, '/admin/specialties');

            /** @var Specialty $specialty */
            $specialty = Specialty::find($id);
            validateRedirect($specialty, '/admin/specialties');

            $specialty->status = '0';
            $result = $specialty->update();

            if ($result) {
                header('Location: /admin/specialties?specialty_deleted=1');
                exit;
            }
        }
    }
}
