<?php

require_once __DIR__ . './../includes/app.php';

use MVC\Router;
use Controllers\UserController;

$router = new Router();

$router->get('/', [UserController::class, 'login']);
$router->post('/', [UserController::class, 'login']);
$router->get('/logout', [UserController::class, 'logout']);
$router->get('/create-account', [UserController::class, 'createAccount']);
$router->post('/create-account', [UserController::class, 'createAccount']);
$router->get('/message', [UserController::class, 'showMessage']);

$router->checkRoutes();