<?php
namespace App\Commands;

class MigrateRefreshCommand {
    public $nama = 'migrate:refresh';
    public $deskripsi = 'Me-reset dan menjalankan ulang semua migrasi.';

    public function handle() {
        echo "Menjalankan migrate:reset...\n";
        // Panggil logika dari MigrateResetCommand
        (new MigrateResetCommand())->handle();

        echo "\nMenjalankan migrate...\n";
        // Panggil logika dari MigrateCommand
        (new MigrateCommand())->handle();

        echo "\nDatabase berhasil di-refresh.\n";
    }
}