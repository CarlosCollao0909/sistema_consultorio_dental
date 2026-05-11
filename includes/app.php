<?php

use Dotenv\Dotenv;
use Models\Database;

define('APP_VERSION', '1.0.2');

require __DIR__ . './../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

require 'functions.php';
require 'db.php';

$db = connectDB();

Database::setDB($db);