<?php

namespace Controllers;

use MVC\Router;

class ErrorController {
    public static function error404(Router $router) {
        $router->render('error/404');
    }
}