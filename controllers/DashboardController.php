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
        isAuth();
        $data = Dashboard::getKpis($_SESSION['id']);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public static function getMonthlyRevenue() {
        isStartedSession();
        isAuth();
        $data = Dashboard::getMonthlyRevenue($_SESSION['id']);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public static function getAppointmentsByStatus() {
        isStartedSession();
        isAuth();
        $data = Dashboard::getAppointmentsByStatus($_SESSION['id']);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public static function getTreatmentsBySpecialty() {
        isStartedSession();
        isAuth();
        $data = Dashboard::getTreatmentsBySpecialty($_SESSION['id']);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}