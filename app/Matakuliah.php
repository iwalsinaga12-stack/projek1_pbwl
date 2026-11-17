<?php

// KOMPOSISI (has-a): kelas Matakuliah TIDAK mewarisi Koneksi.
// Ia hanya "memiliki" objek koneksi yang disuntikkan lewat constructor.
class Matakuliah
{
    private PDO $db;

    public function __construct(Koneksi $k)
    {
        $this->db = $k->getKoneksi();
    }

    // Menampilkan semua data
    public function show(): array
    {
        return $this->db->query("SELECT * FROM tb_matakuliah ORDER BY matkul_semester, matkul_kode")->fetchAll();
    }

    // Data terbaru (dipakai dashboard)
    public function latest(int $n = 5): array
    {
        return $this->db->query("SELECT * FROM tb_matakuliah ORDER BY matkul_id DESC LIMIT " . (int) $n)->fetchAll();
    }

    public function total(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM tb_matakuliah")->fetchColumn();
    }

    // Menyimpan data baru (prepared statement -> aman dari SQL injection)
    public function save(): void
    {
        $stmt = $this->db->prepare("INSERT INTO tb_matakuliah (matkul_kode, matkul_nama, matkul_sks, matkul_semester) VALUES (:matkul_kode, :matkul_nama, :matkul_sks, :matkul_semester)");
        $stmt->execute([
            ':matkul_kode' => trim($_POST['matkul_kode'] ?? ''),
            ':matkul_nama' => trim($_POST['matkul_nama'] ?? ''),
            ':matkul_sks' => trim($_POST['matkul_sks'] ?? ''),
            ':matkul_semester' => trim($_POST['matkul_semester'] ?? ''),
        ]);
    }

    public function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tb_matakuliah WHERE matkul_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function update(): void
    {
        $stmt = $this->db->prepare("UPDATE tb_matakuliah SET matkul_kode = :matkul_kode, matkul_nama = :matkul_nama, matkul_sks = :matkul_sks, matkul_semester = :matkul_semester WHERE matkul_id = :id");
        $stmt->execute([
            ':matkul_kode' => trim($_POST['matkul_kode'] ?? ''),
            ':matkul_nama' => trim($_POST['matkul_nama'] ?? ''),
            ':matkul_sks' => trim($_POST['matkul_sks'] ?? ''),
            ':matkul_semester' => trim($_POST['matkul_semester'] ?? ''),
            ':id' => $_POST['matkul_id'] ?? 0,
        ]);
    }

    public function delete($id): void
    {
        $stmt = $this->db->prepare("DELETE FROM tb_matakuliah WHERE matkul_id = :id");
        $stmt->execute([':id' => $id]);
    }

    // Total SKS seluruh mata kuliah
    public function totalSks(): int
    {
        return (int) $this->db->query("SELECT COALESCE(SUM(matkul_sks), 0) FROM tb_matakuliah")->fetchColumn();
    }

    // Jumlah mata kuliah per semester (untuk grafik batang di dashboard)
    public function perSemester(): array
    {
        return $this->db->query(
            "SELECT matkul_semester AS smt, COUNT(*) AS jml FROM tb_matakuliah GROUP BY matkul_semester ORDER BY smt"
        )->fetchAll();
    }
}
