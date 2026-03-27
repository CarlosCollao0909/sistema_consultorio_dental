<?php

namespace Controllers;

use MVC\Router;

class PatientController {
    public static function index(Router $router) {
        isStartedSession();
        isAuth();
        $router->render('admin/patients/index');
    }
}