<?php

// use App\Controllers\AdminController;

// // Routes Admin
// Router::get('/admin', [AdminController::class, 'dashboard']);

// // Gestion des entreprises
// Router::get('/admin/companies', [AdminController::class, 'companies']);
// Router::post('/admin/companies/create', [AdminController::class, 'createCompany']);
// Router::post('/admin/companies/edit/{id}', [AdminController::class, 'editCompany']);

// // Gestion des annonces
// Router::get('/admin/announcements', [AdminController::class, 'announcements']);
// Router::post('/admin/announcements/create', [AdminController::class, 'createAnnouncement']);
// Router::post('/admin/announcements/edit/{id}', [AdminController::class, 'editAnnouncement']);
// Router::post('/admin/announcements/delete/{id}', [AdminController::class, 'deleteAnnouncement']);
// Router::post('/admin/announcements/restore/{id}', [AdminController::class, 'restoreAnnouncement']);

use App\Controllers\AuthController;
use App\Controllers\CompaniesController;
use App\Core\Auth;
use App\Core\Router;

Router::get('/companies', [CompaniesController::class, 'index']);
Router::get('/login', [AuthController::class, 'showLogin']);
Router::get('/register', [AuthController::class, 'showRegister']);
Router::post('/register', [AuthController::class, 'register']);
Router::post('/login', [AuthController::class, 'login']);
Router::get('/logout', [AuthController::class, 'logout']);
