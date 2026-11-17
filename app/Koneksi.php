<?php

class Koneksi
{
    private PDO $db;

    public function __construct()
    {
        try {
            $this->db = new PDO(
                "mysql:host=localhost;dbname=db_projek1_si2;charset=utf8mb4", "root", "",
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            die("Koneksi gagal: " . $e->getMessage());
        }
    }

    // Dipanggil oleh kelas lain yang memakai objek ini (komposisi)
    public function getKoneksi(): PDO
    {
        return $this->db;
    }
}
