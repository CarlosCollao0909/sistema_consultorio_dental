<?php

namespace Controllers;

use MVC\Router;

class DashboardController {
    public static function index(Router $router) {
        isStartedSession();
        isAuth();
        $router->render('admin/dashboard/index');
    }
}