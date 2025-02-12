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
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Core\Router;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

Session::start();
Database::init();
View::init();

// $r = new Router();
// $r->get('/login', [AuthController::class, 'showLogin']);
// $r->post('/login', [AuthController::class, 'login']);
// $r->get('/logout', [AuthController::class, 'logout']);
// $r->get('/register', [AuthController::class, 'showRegister']);
// $r->post('/register', [AuthController::class, 'register']);

// $r->get('/', [HomeController::class, 'index']);
// $r->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

// Chargement des routes
require_once '../config/routes.php';

// Dispatch de la requête
$router = Router::create();
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
