<?php

namespace Controllers;

use MVC\Router;
use Models\Dashboard;

class DashboardController {
    public static function index(Router $router) {
        isStartedSession();
        isAuth();
        $router->render('admin/dashboard/index');
    }

    public static function getKpis() {
        isStartedSession();
        isApiAuth();
        header('Content-Type: application/json');
        $data = Dashboard::getKpis($_SESSION['id']);
        echo json_encode($data);
    }

    public static function getMonthlyRevenue() {
        isStartedSession();
        isApiAuth();
        header('Content-Type: application/json');
        $data = Dashboard::getMonthlyRevenue($_SESSION['id']);
        echo json_encode($data);
    }

    public static function getAppointmentsByStatus() {
        isStartedSession();
        isApiAuth();
        header('Content-Type: application/json');
        $data = Dashboard::getAppointmentsByStatus($_SESSION['id']);
        echo json_encode($data);
    }

    public static function getTreatmentsBySpecialty() {
        isStartedSession();
        isApiAuth();
        header('Content-Type: application/json');
        $data = Dashboard::getTreatmentsBySpecialty($_SESSION['id']);
        echo json_encode($data);
    }
}