<?php

use App\Controllers\AnnoncementsController;
use App\Controllers\AuthController;
use App\Controllers\CompaniesController;
use App\Controllers\ListAnnouncesController;
use App\Core\Router;

Router::get('/login', [AuthController::class, 'showLogin']);
Router::get('/register', [AuthController::class, 'showRegister']);
Router::post('/register', [AuthController::class, 'register']);
Router::post('/login', [AuthController::class, 'login']);
Router::get('/logout', [AuthController::class, 'logout']);

Router::get('/companies', [CompaniesController::class, 'index']);
Router::get('/companies/create', [CompaniesController::class, 'create']);
Router::post('/companies/create', [CompaniesController::class, 'store']);
Router::get('/companies/edit/{id}', [CompaniesController::class, 'edit']);
Router::post('/companies/edit/{id}', [CompaniesController::class, 'update']);
Router::post('/companies/delete/{id}', [CompaniesController::class, 'delete']);
Router::post('/companies/restore/{id}', [CompaniesController::class, 'restore']);
Router::get('/companies/trash', [CompaniesController::class, 'trash']);

Router::get('/listannounces', [ListAnnouncesController::class, 'index']);
Router::get('/announcements', [AnnoncementsController::class, 'index']);
Router::get('/announcements/create', [AnnoncementsController::class, 'create']);
Router::post('/announcements/store', [AnnoncementsController::class, 'store']);
Router::get('/announcements/edit/{id}', [AnnoncementsController::class, 'edit']);
Router::post('/announcements/update/{id}', [AnnoncementsController::class, 'update']);
Router::post('/announcements/delete/{id}', [AnnoncementsController::class, 'delete']);
