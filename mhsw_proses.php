<?php

require_once "app/Koneksi.php";
require_once "app/Mahasiswa.php";

$koneksi = new Koneksi(); // satu koneksi dipakai bersama
$mhsw = new Mahasiswa($koneksi);

$action = $_GET['action'] ?? '';
$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
$status = '';

// Validasi sisi server: kolom wajib tidak boleh kosong
$valid = true;
foreach (['mhsw_nim', 'mhsw_nama'] as $f) {
    if (trim($_POST[$f] ?? '') === '') {
        $valid = false;
    }
}

try {
    switch ($action) {
        case 'save':
            if ($isPost && isset($_POST['save'])) {
                if ($valid) { $mhsw->save(); $status = 'saved'; } else { $status = 'invalid'; }
            }
            break;

        case 'update':
            if ($isPost && isset($_POST['update'])) {
                if ($valid) { $mhsw->update(); $status = 'updated'; } else { $status = 'invalid'; }
            }
            break;

        case 'delete': // hapus hanya lewat POST (bukan link GET)
            if ($isPost && isset($_POST['mhsw_id'])) {
                $mhsw->delete($_POST['mhsw_id']);
                $status = 'deleted';
            }
            break;
    }
} catch (PDOException $e) {
    // 23000 = pelanggaran UNIQUE (NIM kembar)
    $status = ($e->getCode() === '23000') ? 'dup' : 'error';
}

header("Location: index.php?page=mhsw" . ($status ? "&status=" . $status : ""));
exit;
