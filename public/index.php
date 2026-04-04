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

// Treatments (profile actions)
$router->post('/admin/patients/profile/treatment-create', [TreatmentController::class, 'create']);
$router->post('/admin/patients/profile/treatment-update', [TreatmentController::class, 'update']);
$router->post('/admin/patients/profile/treatment-delete', [TreatmentController::class, 'delete']);

// Appointments (profile actions)
$router->post('/admin/patients/profile/appointment-create', [AppointmentController::class, 'create']);
$router->post('/admin/patients/profile/appointment-update', [AppointmentController::class, 'update']);
$router->post('/admin/patients/profile/appointment-delete', [AppointmentController::class, 'delete']);

// Payments (profile actions)
$router->post('/admin/patients/profile/payment-create', [PaymentController::class, 'create']);
$router->post('/admin/patients/profile/payment-delete', [PaymentController::class, 'delete']);

// Attachments (profile actions)
$router->post('/admin/patients/profile/attachment-upload', [AttachmentController::class, 'upload']);
$router->post('/admin/patients/profile/attachment-delete', [AttachmentController::class, 'delete']);
$router->get('/admin/patients/profile/attachment-download', [AttachmentController::class, 'download']);

$router->get('/admin/appointments', [AppointmentController::class, 'index']);

$router->get('/admin/payments', [PaymentController::class, 'index']);

$router->get('/admin/treatments', [TreatmentController::class, 'index']);

$router->get('/admin/specialties', [SpecialtyController::class, 'index']);
$router->get('/admin/specialties/create', [SpecialtyController::class, 'create']);
$router->post('/admin/specialties/create', [SpecialtyController::class, 'create']);
$router->get('/admin/specialties/update', [SpecialtyController::class, 'update']);
$router->post('/admin/specialties/update', [SpecialtyController::class, 'update']);
$router->post('/admin/specialties/delete', [SpecialtyController::class, 'delete']);

$router->checkRoutes();
