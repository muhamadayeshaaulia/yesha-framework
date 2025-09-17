<?php

namespace Inti;

class Pemandangan {
    public static function tampilkan($namaView, $data = []) {
        $pathView = __DIR__ . '/../views/' . $namaView . '.yesh';

        if (!file_exists($pathView)) {
            echo "Error: View '{$namaView}.yesh' tidak ditemukan.";
            return;
        }

        $konten = file_get_contents($pathView);
        $konten = preg_replace('/\{\{\s*(.+?)\s*\}\}/', '<?php echo htmlspecialchars($1); ?>', $konten);

        extract($data);

        ob_start();
        // PERHATIAN: eval() digunakan di sini untuk kesederhanaan tutorial.
        // Untuk produksi, lebih baik menggunakan sistem cache file.
        eval('?>' . $konten);
        $output = ob_get_clean();

        echo $output;
    }
}