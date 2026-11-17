<?php

// KOMPOSISI (has-a): kelas Mahasiswa TIDAK mewarisi Koneksi.
// Ia hanya "memiliki" objek koneksi yang disuntikkan lewat constructor.
class Mahasiswa
{
    private PDO $db;

    public function __construct(Koneksi $k)
    {
        $this->db = $k->getKoneksi();
    }

    // Menampilkan semua data
    public function show(): array
    {
        return $this->db->query("SELECT * FROM tb_mhsw ORDER BY mhsw_nim")->fetchAll();
    }

    // Data terbaru (dipakai dashboard)
    public function latest(int $n = 5): array
    {
        return $this->db->query("SELECT * FROM tb_mhsw ORDER BY mhsw_id DESC LIMIT " . (int) $n)->fetchAll();
    }

    public function total(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM tb_mhsw")->fetchColumn();
    }

    // Menyimpan data baru (prepared statement -> aman dari SQL injection)
    public function save(): void
    {
        $stmt = $this->db->prepare("INSERT INTO tb_mhsw (mhsw_nim, mhsw_nama, mhsw_alamat) VALUES (:mhsw_nim, :mhsw_nama, :mhsw_alamat)");
        $stmt->execute([
            ':mhsw_nim' => trim($_POST['mhsw_nim'] ?? ''),
            ':mhsw_nama' => trim($_POST['mhsw_nama'] ?? ''),
            ':mhsw_alamat' => trim($_POST['mhsw_alamat'] ?? ''),
        ]);
    }

    public function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tb_mhsw WHERE mhsw_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function update(): void
    {
        $stmt = $this->db->prepare("UPDATE tb_mhsw SET mhsw_nim = :mhsw_nim, mhsw_nama = :mhsw_nama, mhsw_alamat = :mhsw_alamat WHERE mhsw_id = :id");
        $stmt->execute([
            ':mhsw_nim' => trim($_POST['mhsw_nim'] ?? ''),
            ':mhsw_nama' => trim($_POST['mhsw_nama'] ?? ''),
            ':mhsw_alamat' => trim($_POST['mhsw_alamat'] ?? ''),
            ':id' => $_POST['mhsw_id'] ?? 0,
        ]);
    }

    public function delete($id): void
    {
        $stmt = $this->db->prepare("DELETE FROM tb_mhsw WHERE mhsw_id = :id");
        $stmt->execute([':id' => $id]);
    }
}
