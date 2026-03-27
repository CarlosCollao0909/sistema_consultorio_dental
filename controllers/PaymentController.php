<?php

namespace Controllers;

use MVC\Router;

class PaymentController {
    public static function index(Router $router) {
        isStartedSession();
        isAuth();
        $router->render('admin/payments/index');
    }
}