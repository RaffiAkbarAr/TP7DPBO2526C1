CREATE DATABASE IF NOT EXISTS db_restoran
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_restoran;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS pesanan;
DROP TABLE IF EXISTS menu;
DROP TABLE IF EXISTS kategori;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT NULL
) ENGINE=InnoDB;

CREATE TABLE menu (
    id_menu INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori INT NOT NULL,
    nama_menu VARCHAR(150) NOT NULL,
    jenis_menu ENUM('Makanan', 'Minuman') NOT NULL,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    stok INT NOT NULL DEFAULT 0,
    deskripsi TEXT NULL,
    CONSTRAINT fk_menu_kategori FOREIGN KEY (id_kategori)
        REFERENCES kategori(id_kategori)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE pesanan (
    id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
    id_menu INT NOT NULL,
    nama_pelanggan VARCHAR(120) NOT NULL,
    jumlah INT NOT NULL,
    total_harga DECIMAL(12,2) NOT NULL,
    tanggal_pesanan DATETIME NOT NULL,
    status_pesanan ENUM('Menunggu', 'Diproses', 'Selesai', 'Dibatalkan') NOT NULL DEFAULT 'Menunggu',
    CONSTRAINT fk_pesanan_menu FOREIGN KEY (id_menu)
        REFERENCES menu(id_menu)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO kategori (nama_kategori, deskripsi) VALUES
('Makanan Utama', 'Menu makanan utama restoran.'),
('Minuman', 'Minuman dingin dan hangat.'),
('Camilan', 'Makanan ringan untuk teman bersantai.');

INSERT INTO menu (id_kategori, nama_menu, jenis_menu, harga, stok, deskripsi) VALUES
(1, 'Nasi Goreng Spesial', 'Makanan', 28000, 25, 'Nasi goreng dengan telur dan ayam.'),
(1, 'Chicken Steak', 'Makanan', 42000, 15, 'Steak ayam dengan saus spesial.'),
(2, 'Es Teh Lemon', 'Minuman', 12000, 40, 'Teh segar dengan lemon.'),
(2, 'Kopi Susu Aren', 'Minuman', 18000, 30, 'Kopi susu dengan gula aren.'),
(3, 'Kentang Goreng', 'Makanan', 16000, 20, 'Kentang goreng renyah.');

INSERT INTO pesanan (id_menu, nama_pelanggan, jumlah, total_harga, tanggal_pesanan, status_pesanan) VALUES
(1, 'Andi', 2, 56000, NOW(), 'Selesai'),
(3, 'Sinta', 1, 12000, NOW(), 'Diproses'),
(4, 'Bima', 2, 36000, NOW(), 'Menunggu');
