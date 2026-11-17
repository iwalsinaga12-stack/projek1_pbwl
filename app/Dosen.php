<?php

// KOMPOSISI (has-a): kelas Dosen TIDAK mewarisi Koneksi.
// Ia hanya "memiliki" objek koneksi yang disuntikkan lewat constructor.
class Dosen
{
    private PDO $db;

    public function __construct(Koneksi $k)
    {
        $this->db = $k->getKoneksi();
    }

    // Menampilkan semua data
    public function show(): array
    {
        return $this->db->query("SELECT * FROM tb_dosen ORDER BY dosen_nama")->fetchAll();
    }

    // Data terbaru (dipakai dashboard)
    public function latest(int $n = 5): array
    {
        return $this->db->query("SELECT * FROM tb_dosen ORDER BY dosen_id DESC LIMIT " . (int) $n)->fetchAll();
    }

    public function total(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM tb_dosen")->fetchColumn();
    }

    // Menyimpan data baru (prepared statement -> aman dari SQL injection)
    public function save(): void
    {
        $stmt = $this->db->prepare("INSERT INTO tb_dosen (dosen_nip, dosen_nama, dosen_golongan) VALUES (:dosen_nip, :dosen_nama, :dosen_golongan)");
        $stmt->execute([
            ':dosen_nip' => trim($_POST['dosen_nip'] ?? ''),
            ':dosen_nama' => trim($_POST['dosen_nama'] ?? ''),
            ':dosen_golongan' => trim($_POST['dosen_golongan'] ?? ''),
        ]);
    }

    public function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tb_dosen WHERE dosen_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function update(): void
    {
        $stmt = $this->db->prepare("UPDATE tb_dosen SET dosen_nip = :dosen_nip, dosen_nama = :dosen_nama, dosen_golongan = :dosen_golongan WHERE dosen_id = :id");
        $stmt->execute([
            ':dosen_nip' => trim($_POST['dosen_nip'] ?? ''),
            ':dosen_nama' => trim($_POST['dosen_nama'] ?? ''),
            ':dosen_golongan' => trim($_POST['dosen_golongan'] ?? ''),
            ':id' => $_POST['dosen_id'] ?? 0,
        ]);
    }

    public function delete($id): void
    {
        $stmt = $this->db->prepare("DELETE FROM tb_dosen WHERE dosen_id = :id");
        $stmt->execute([':id' => $id]);
    }
}
