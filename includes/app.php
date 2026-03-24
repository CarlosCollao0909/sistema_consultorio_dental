<?php

use Dotenv\Dotenv;
use Models\Database;

require __DIR__ . './../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

require 'functions.php';
require 'db.php';

Database::setDB($db);