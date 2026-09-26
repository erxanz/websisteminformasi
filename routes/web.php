<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\MediaController;
use App\Controllers\ProfileController;
use App\Controllers\ProgramStudiController;
use App\Core\Router;

/** @var Router $router */
$router->get('/', [HomeController::class, 'index']);
$router->get('/program-studi/{id}', [ProgramStudiController::class, 'show']);

$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/dashboard', [DashboardController::class, 'index']);

$router->get('/ubah-password', [AuthController::class, 'showChangePassword']);
$router->post('/ubah-password', [AuthController::class, 'changePassword']);

$router->get('/profil', [ProfileController::class, 'show']);
$router->post('/profil', [ProfileController::class, 'update']);
$router->post('/profil/foto', [ProfileController::class, 'updatePhoto']);
$router->post('/profil/foto/hapus', [ProfileController::class, 'deletePhoto']);

// Foto profil disajikan lewat aplikasi karena folder storage tidak dapat diakses langsung.
$router->get('/media/profil/{file}', [MediaController::class, 'profilePhoto']);

