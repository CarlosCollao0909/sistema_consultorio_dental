<?php

namespace Controllers;

use MVC\Router;

class AppointmentController {
    public static function index(Router $router) {
        isStartedSession();
        isAuth();
        $router->render('admin/appointments/index');
    }
}