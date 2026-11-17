# Project 1 - SIAKAD Mini (komposisi objek)

Tema tampilan: **papan tulis kampus**.

## Cara menjalankan
1. Import `database.sql` lewat phpMyAdmin (membuat database `db_projek1_si2` + data contoh).
2. Taruh folder ini di `htdocs` (XAMPP), buka `http://localhost/project1/`.
3. Koneksi default: `localhost`, user `root`, tanpa password (ubah di `app/Koneksi.php`).

## Konsep: Komposisi (has-a)
File kelas: app/Koneksi.php, app/Mahasiswa.php, app/Dosen.php, app/Matakuliah.php

## Modul
Mahasiswa, Dosen, dan Matakuliah: masing-masing lengkap (daftar + cari, tambah, edit, hapus).
Halaman: `X.php`, `X_input.php`, `X_edit.php`, `X_proses.php` (X = mhsw / dosen / matkul).
