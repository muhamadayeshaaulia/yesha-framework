<?php

namespace App\Controllers;

use App\Models\Tugas;
use Inti\Pemandangan;

class TugasController {
    
    // Menampilkan semua tugas
    public function index() {
        $data = [
            'judul' => 'Daftar Tugas Saya',
            'tugas' => Tugas::all()
        ];
        return Pemandangan::tampilkan('tugas/index', $data);
    }
    
    // Contoh membuat tugas baru
    public function buat() {
        $tugasBaru = new Tugas();
        $tugasBaru->nama_tugas = 'Belajar membuat BaseModel yang canggih';
        $tugasBaru->selesai = false;
        $tugasBaru->save(); // Otomatis INSERT ke database

        echo "Tugas baru berhasil dibuat dengan ID: " . $tugasBaru->id;
    }

    // Contoh mengupdate tugas
    public function update($id) {
        $tugas = Tugas::find($id);
        if ($tugas) {
            $tugas->selesai = true; // Ubah statusnya
            $tugas->save(); // Otomatis UPDATE ke database

            echo "Tugas dengan ID {$id} berhasil diupdate.";
        } else {
            echo "Tugas tidak ditemukan.";
        }
    }

    // Contoh menghapus tugas
    public function hapus($id) {
        $tugas = Tugas::find($id);
        if ($tugas) {
            $tugas->delete(); // Otomatis DELETE dari database
            echo "Tugas dengan ID {$id} berhasil dihapus.";
        } else {
            echo "Tugas tidak ditemukan.";
        }
    }
}