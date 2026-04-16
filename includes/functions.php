<?php

define('PDF_FOLDER', $_SERVER['DOCUMENT_ROOT'] . './../storage/PDFs/');

function debug($variable) {
    echo '<pre>';
    var_dump($variable);
    echo '</pre>';
    exit;
}

function sanitizeHTML($html) {
    $s = htmlspecialchars($html);
    return $s;
}

function isStartedSession() {
    if (!isset($_SESSION)) {
        session_start();
    }
}

function isAuth() {
    if (!isset($_SESSION['login'])) {
        header('Location: /');
        exit;
    }
}

function isApiAuth() {
    if (!isset($_SESSION['login'])) {
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode(['error' => 'No autorizado']);
        exit;
    }
}

function validateRedirect($variable, $url) {
    if (!$variable) {
        header("Location: $url");
        exit;
    }
}

function currentPage($path) {
    return str_contains($_SERVER['PATH_INFO'], $path) ? true : false;
}

function calculateAge($birthDate) {
    $birthDate = new DateTime($birthDate);
    $currentDate = new DateTime();
    $age = $currentDate->diff($birthDate)->y;
    return $age;
}

function getClinicInfo() {
    return [
        'name' => $_ENV['CLINIC_NAME'] ?? 'Consultorio Dental',
        'address' => $_ENV['CLINIC_ADDRESS'] ?? '',
        'phone' => $_ENV['CLINIC_PHONE'] ?? '',
        'email' => $_ENV['CLINIC_EMAIL'] ?? '',
    ];
}

function formatTimestamp($timestamp, $time = false) {
    $timestamp = is_string($timestamp) ? strtotime($timestamp) : (int)$timestamp;

    $days = [
        'Monday' => 'lunes',
        'Tuesday' => 'martes',
        'Wednesday' => 'miércoles',
        'Thursday' => 'jueves',
        'Friday' => 'viernes',
        'Saturday' => 'sábado',
        'Sunday' => 'domingo'
    ];
    $months = [
        1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
        'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
    ];

    $weekDay = $days[date('l', $timestamp)];
    $day = date('d', $timestamp);
    $month = $months[(int)date('m', $timestamp)];
    $year = date('Y', $timestamp);
    $hour = date('H:i', $timestamp);

    if ($time) {
        return "$weekDay, $day de $month de $year. Hrs: $hour";
    }
    
    return "$weekDay, $day de $month de $year";
}