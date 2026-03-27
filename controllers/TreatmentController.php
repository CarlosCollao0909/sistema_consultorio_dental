<?php

namespace Controllers;

use MVC\Router;

class TreatmentController {
    public static function index(Router $router) {
        isStartedSession();
        isAuth();
        $router->render('admin/treatments/index');
    }
}