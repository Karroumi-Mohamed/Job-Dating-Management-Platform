<?php
require "../vendor/larapack/dd/src/helper.php";
include '../vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\Core\Database;
use App\Core\Session;
use App\Core\View;
use Dotenv\Dotenv;
use App\Core\Router;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

Session::start();
Database::init();
View::init();

require_once '../config/routes.php';

$router = Router::create();
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
