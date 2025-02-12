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

Router::get('/announcements/trash', [AnnoncementsController::class, 'trash']);

Router::post('/announcements/restore/{id}', [AnnoncementsController::class, 'restore']);

Router::get('/listannounces', [ListAnnouncesController::class, 'index']);
Router::get('/announcements', [AnnoncementsController::class, 'index']);
Router::post('/announcements/delete/{id}', [AnnoncementsController::class, 'delete']);
