<?php

namespace Controllers;

use MVC\Router;

class SpecialtyController {
    public static function index(Router $router) {
        isStartedSession();
        isAuth();
        $router->render('admin/specialties/index');
    }
}