<?php
require "../vendor/larapack/dd/src/helper.php";
include '../vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\Core\Database;
use App\Core\Session;
use Dotenv\Dotenv;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Core\Router;


$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

Session::start();
Database::init();


$r = new Router();
require_once '../config/routes.php';

// $r->get('/', [HomeController::class, 'index']);
$r->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
