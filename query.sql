CREATE DATABASE app_laundry;

USE app_laundry;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(64) UNIQUE,
    password VARCHAR(255),
    role CHAR(1),
    nama VARCHAR(128),
    jenis_kelamin CHAR(1),
    alamat VARCHAR(128),
    no_telp VARCHAR(20),
    profil VARCHAR(64),
    poin INT
);

CREATE TABLE kategori (
    id_kategori CHAR(1) NOT NULL PRIMARY KEY,
    nama_kategori VARCHAR(20)
);

CREATE TABLE servis (
    id_servis INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nama_servis VARCHAR(20)
);

CREATE TABLE barang (
    id_barang INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nama_barang VARCHAR(20)
);

CREATE TABLE daftar_harga (
    id_harga INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    id_barang INT,
    id_kategori CHAR(1),
    id_servis INT,
    harga INT,
    FOREIGN KEY (id_barang) REFERENCES barang(id_barang),
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori),
    FOREIGN KEY (id_servis) REFERENCES servis(id_servis)
);

CREATE TABLE status (
    id_status INT NOT NULL PRIMARY KEY,
    nama_status VARCHAR(20)
);

CREATE TABLE transaksi (
    id_transaksi INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    tgl_masuk DATETIME,
    id_status INT,
    id_member INT,
    id_admin INT,
    tgl_selesai DATETIME,
    total_harga INT,
    FOREIGN KEY (id_status) REFERENCES status(id_status),
    FOREIGN KEY (id_member) REFERENCES users(id)
);

CREATE TABLE detail_transaksi (
    id_transaksi INT NOT NULL,
    id_harga INT,
    banyak INT,
    harga INT,
    sub_total INT,
    PRIMARY KEY (id_transaksi, id_harga),
    FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi),
    FOREIGN KEY (id_harga) REFERENCES daftar_harga(id_harga)
);

CREATE TABLE saran_komplain (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    tgl DATETIME,
    isi TEXT,
    tipe CHAR(1),
    id_member INT,
    balasan TEXT,
    FOREIGN KEY (id_member) REFERENCES users(id)
);