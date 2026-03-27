<?php

namespace Controllers;

use MVC\Router;
use Models\User;

class UserController {
    public static function login(Router $router) {
        $alerts = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new User($_POST);
            $alerts = $auth->validateLogin();

            if (empty($alerts)) {
                $user = User::where('username', $auth->username);
                
                if ($user) {
                    
                    if ($user->verifyPassword($auth->password)) {
                        isStartedSession();
                        $_SESSION['id'] = $user->id;
                        $_SESSION['name'] = $user->name . ' ' . $user->last_name;
                        $_SESSION['username'] = $user->username;
                        $_SESSION['login'] = true;

                        if ($user->role === '1') {
                            $_SESSION['admin'] = true;
                        }
                        debug($_SESSION);
                        header('Location: /admin/dashboard');
                    }
                } else {
                    User::setAlert('error', 'Usuario no encontrado o contraseña incorrecta');
                }
            }
        }

        $alerts = User::getAlerts();

        $router->render('auth/login', [
            'alerts' => $alerts
        ]);
    }

    public static function logout() {
        isStartedSession();
        $_SESSION = [];
        session_destroy();
        header('Location: /');
    }

    public static function createAccount(Router $router) {
        $user = new User;
        $alerts = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user->synchronize($_POST);
            $alerts = $user->validateAccount();
            
            if (empty($alerts)) {
                $user->hashPassword();
                $result = $user->create();
                
                if ($result) {
                    header('Location: /message');
                }
            }
        }

        $router->render('auth/createAccount', [
            'user' => $user,
            'alerts' => $alerts
        ]);
    }

    public static function showMessage(Router $router) {
        $router->render('auth/message');
    }
}