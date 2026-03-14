-- Script Database SQL untuk Sistem Pelacakan Alumni
-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS alumni_tracker;
USE alumni_tracker;

-- Tabel 1: alumni (Menyimpan data dasar dan status pelacakan)
CREATE TABLE IF NOT EXISTS alumni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(255) NOT NULL,
    inisial VARCHAR(50),
    prodi VARCHAR(100),
    tahun_lulus YEAR,
    kota VARCHAR(100),
    bidang_keilmuan VARCHAR(100),
    status_pelacakan VARCHAR(50) DEFAULT 'Belum Dilacak',
    jabatan VARCHAR(100) DEFAULT NULL,
    perusahaan VARCHAR(100) DEFAULT NULL,
    lokasi VARCHAR(100) DEFAULT NULL,
    tanggal_update DATE DEFAULT NULL,
    waktu_sekarang DATE DEFAULT NULL,
    jadwal_pelacakan VARCHAR(20) DEFAULT '7_hari'
);

-- Tabel 2: jejak_bukti (Sesuai Pseudocode Langkah 10)
CREATE TABLE IF NOT EXISTS jejak_bukti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alumni_id INT NOT NULL,
    sumber_temuan VARCHAR(100),
    ringkasan_info TEXT,
    confidence_score INT,
    tanggal_ditemukan DATE,
    pointer_bukti TEXT,
    FOREIGN KEY (alumni_id) REFERENCES alumni(id) ON DELETE CASCADE
);

-- Tabel 3: riwayat_perubahan (Sesuai Pseudocode Langkah 11)
CREATE TABLE IF NOT EXISTS riwayat_perubahan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alumni_id INT NOT NULL,
    data_lama TEXT,
    data_baru TEXT,
    tanggal_perubahan DATETIME,
    FOREIGN KEY (alumni_id) REFERENCES alumni(id) ON DELETE CASCADE
);

-- Insert Data Dummy Target Alumni (Untuk Pengujian)
INSERT INTO alumni (nama_lengkap, inisial, prodi, tahun_lulus, kota, bidang_keilmuan, waktu_sekarang) VALUES 
('Azis Disha Rono Suryo', 'ADRS', 'Teknik Informatika', 2020, 'Malang', 'Software Engineering', CURDATE()),
('Alfarizi Hardiansyah', 'AH', 'Sistem Informasi', 2021, 'Surabaya', 'Data Analyst', CURDATE()),
('Govin Riofany Luthfi', 'GRL', 'Teknik Elektro', 2019, 'Jakarta', 'Network Engineer', CURDATE()),
('Muchammad Dwi Ferdi Ilham Arifin', 'MDFIA', 'Teknik Industri', 2021, 'Sidoarjo', 'Supply Chain', CURDATE());
