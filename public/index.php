<?php

// Memuat autoloader Composer
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Menggunakan kelas dengan namespace-nya
use App\Controllers\BerandaController;
use Inti\Router;

// --- DEFINISI RUTE ---
Router::get('/', [BerandaController::class, 'index']);

// --- JALANKAN ROUTER ---
Router::jalankan();