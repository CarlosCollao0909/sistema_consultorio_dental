<?php

require_once __DIR__ . './../includes/app.php';

use MVC\Router;
use Controllers\UserController;
use Controllers\DashboardController;
use Controllers\PatientController;
use Controllers\AppointmentController;
use Controllers\PaymentController;
use Controllers\TreatmentController;
use Controllers\SpecialtyController;

$router = new Router();

$router->get('/', [UserController::class, 'login']);
$router->post('/', [UserController::class, 'login']);
$router->get('/logout', [UserController::class, 'logout']);
$router->get('/create-account', [UserController::class, 'createAccount']);
$router->post('/create-account', [UserController::class, 'createAccount']);
$router->get('/message', [UserController::class, 'showMessage']);

$router->get('/admin/dashboard', [DashboardController::class, 'index']);

$router->get('/admin/patients', [PatientController::class, 'index']);
$router->get('/admin/patients/create', [PatientController::class, 'create']);
$router->post('/admin/patients/create', [PatientController::class, 'create']);
$router->get('/admin/patients/update', [PatientController::class, 'update']);
$router->post('/admin/patients/update', [PatientController::class, 'update']);
$router->post('/admin/patients/delete', [PatientController::class, 'delete']);

$router->get('/admin/appointments', [AppointmentController::class, 'index']);

$router->get('/admin/payments', [PaymentController::class, 'index']);

$router->get('/admin/treatments', [TreatmentController::class, 'index']);

$router->get('/admin/specialties', [SpecialtyController::class, 'index']);

$router->checkRoutes();
