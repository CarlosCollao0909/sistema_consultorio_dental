<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . './../includes/app.php';

use MVC\Router;
use Controllers\UserController;
use Controllers\DashboardController;
use Controllers\PatientController;
use Controllers\AppointmentController;
use Controllers\PaymentController;
use Controllers\TreatmentController;
use Controllers\SpecialtyController;
use Controllers\AttachmentController;

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
$router->get('/admin/patients/profile', [PatientController::class, 'showProfile']);

// Treatments
$router->get('/admin/treatments', [TreatmentController::class, 'index']);
$router->get('/admin/treatments/create', [TreatmentController::class, 'create']);
$router->post('/admin/treatments/create', [TreatmentController::class, 'create']);
$router->get('/admin/treatments/update', [TreatmentController::class, 'update']);
$router->post('/admin/treatments/update', [TreatmentController::class, 'update']);
$router->post('/admin/treatments/delete', [TreatmentController::class, 'delete']);

// Appointments
$router->get('/admin/appointments', [AppointmentController::class, 'index']);
$router->get('/admin/appointments/create', [AppointmentController::class, 'create']);
$router->post('/admin/appointments/create', [AppointmentController::class, 'create']);
$router->get('/admin/appointments/update', [AppointmentController::class, 'update']);
$router->post('/admin/appointments/update', [AppointmentController::class, 'update']);
$router->post('/admin/appointments/delete', [AppointmentController::class, 'delete']);

// Payments
$router->get('/admin/payments', [PaymentController::class, 'index']);
$router->get('/admin/payments/create', [PaymentController::class, 'create']);
$router->post('/admin/payments/create', [PaymentController::class, 'create']);
$router->post('/admin/payments/delete', [PaymentController::class, 'delete']);

// Attachments
$router->get('/admin/attachments/create', [AttachmentController::class, 'create']);
$router->post('/admin/attachments/create', [AttachmentController::class, 'create']);
$router->post('/admin/attachments/delete', [AttachmentController::class, 'delete']);
$router->get('/admin/attachments/download', [AttachmentController::class, 'download']);
$router->get('/admin/attachments/show', [AttachmentController::class, 'show']);

$router->get('/admin/specialties', [SpecialtyController::class, 'index']);
$router->get('/admin/specialties/create', [SpecialtyController::class, 'create']);
$router->post('/admin/specialties/create', [SpecialtyController::class, 'create']);
$router->get('/admin/specialties/update', [SpecialtyController::class, 'update']);
$router->post('/admin/specialties/update', [SpecialtyController::class, 'update']);
$router->post('/admin/specialties/delete', [SpecialtyController::class, 'delete']);

$router->checkRoutes();
