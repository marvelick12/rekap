-- SQL Script for Laporan Kerja Harian (PHP Native)
CREATE DATABASE IF NOT EXISTS rekaptugas;
USE rekaptugas;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    division VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS jurnal_harian (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tanggal DATE NOT NULL,
    hari VARCHAR(20) NOT NULL,
    bulan VARCHAR(20) NOT NULL,
    tahun INT NOT NULL,
    unit_divisi VARCHAR(100) NOT NULL,
    nama_pekerjaan VARCHAR(255) NOT NULL,
    catatan TEXT DEFAULT NULL,
    dokumentasi VARCHAR(255) DEFAULT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    status VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS rencana_pekerjaan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tanggal DATE NOT NULL,
    hari VARCHAR(20) NOT NULL,
    bulan VARCHAR(20) NOT NULL,
    tahun INT NOT NULL,
    nama_project VARCHAR(150) NOT NULL,
    target_pekerjaan VARCHAR(255) NOT NULL,
    catatan TEXT DEFAULT NULL,
    status TINYINT(1) DEFAULT 0, -- 0: Belum Selesai, 1: Selesai
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS evaluasi_harian (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tanggal DATE NOT NULL,
    berjalan_baik TEXT DEFAULT NULL,
    kendala TEXT DEFAULT NULL,
    solusi TEXT DEFAULT NULL,
    perlu_diperbaiki TEXT DEFAULT NULL,
    target_besok TEXT DEFAULT NULL,
    catatan_tambahan TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
