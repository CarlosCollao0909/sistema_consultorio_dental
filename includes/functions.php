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
    }
}

function validateRedirect($variable, $url) {
    if (!$variable) {
        header("Location: $url");
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