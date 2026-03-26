<?php 

namespace MVC;

class Router {
    public array $getRoutes = [];
    public array $postRoutes = [];

    public function get($url, $fn) {
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn) {
        $this->postRoutes[$url] = $fn;
    }

    public function checkRoutes() {
        $currentUrl = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $fn = $this->getRoutes[$currentUrl] ?? null;
        } else {
            $fn = $this->postRoutes[$currentUrl] ?? null;
        }

        if ($fn) {
            call_user_func($fn, $this);
        } else {
            header('Location: /error/404');
        }

    }

    public function render($view, $data = []) {
        foreach ($data as $key => $value) {
            $$key = $value;
        }

        ob_start();

        include_once __DIR__ . "/views/$view.php";
        $content = ob_get_clean();

        $currentUrl = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';

        if(str_contains($currentUrl, '/admin')) {
            include_once __DIR__ . '/views/layouts/admin.php';
        } else if (str_contains($currentUrl, '/error')) {
            include_once __DIR__ . 'views/layouts/error.php';
        } else {
            include_once __DIR__ . 'views/layouts/account.php';
        }
    }
}