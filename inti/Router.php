<?php

namespace Inti;

class Router {
    private static $rute = [];

    public static function get($jalur, $aksi) {
        self::$rute['GET'][$jalur] = $aksi;
    }

    public static function jalankan() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

        if ($path === '') $path = '/';

        if (isset(self::$rute[$method][$path])) {
            $aksi = self::$rute[$method][$path];
            $controller = new $aksi[0]();
            $methodController = $aksi[1];
            $controller->$methodController();
        } else {
            http_response_code(404);
            echo "<h1>404 | Halaman Tidak Ditemukan</h1>";
        }
    }
}