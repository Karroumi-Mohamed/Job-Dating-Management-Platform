<?php

use App\Controllers\AnnoncementsController;
use App\Controllers\AuthController;
use App\Controllers\CompaniesController;
use App\Controllers\ListAnnouncesController;
use App\Core\Auth;
use App\Core\Router;

Router::get('/login', [AuthController::class, 'showLogin']);
Router::get('/register', [AuthController::class, 'showRegister']);
Router::post('/register', [AuthController::class, 'register']);
Router::post('/login', [AuthController::class, 'login']);
Router::get('/logout', function () {
    Auth::logout();
    header('Location: /login');
    exit;
});


Router::get('/companies', [CompaniesController::class, 'index']);
Router::get('/companies/trash', [CompaniesController::class, 'trash']);
Router::post('/companies/restore/{id}', [CompaniesController::class, 'restore']);

Router::get('/api/companies', [CompaniesController::class, 'getCompaniesTable']);
Router::get('/api/companies/trash', [CompaniesController::class, 'getTrashTable']);
Router::post('/api/companies/store', [CompaniesController::class, 'store']);
Router::post('/api/companies/update/{id}', [CompaniesController::class, 'update']);
Router::post('/api/companies/delete/{id}', [CompaniesController::class, 'delete']);
Router::post('/api/companies/restore/{id}', [CompaniesController::class, 'restore']);

Router::get('/', [ListAnnouncesController::class, 'index']);
Router::get('/announcements', [AnnoncementsController::class, 'index']);
Router::get('/announcements/trash', [AnnoncementsController::class, 'trash']);

// api nta3 ajax
Router::get('/api/announcements', [AnnoncementsController::class, 'getAnnouncementsTable']);
Router::get('/api/announcements/trash', [AnnoncementsController::class, 'getTrashTable']);
Router::post('/api/announcements/store', [AnnoncementsController::class, 'store']);
Router::post('/api/announcements/update/{id}', [AnnoncementsController::class, 'update']);
Router::post('/api/announcements/delete/{id}', [AnnoncementsController::class, 'delete']);
Router::post('/api/announcements/restore/{id}', [AnnoncementsController::class, 'restore']);
