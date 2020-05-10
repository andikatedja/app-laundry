CREATE DATABASE app_laundry;

USE app_laundry;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(64) UNIQUE,
    password VARCHAR(255),
    role CHAR(1),
);

CREATE TABLE members (
    id_user INT PRIMARY KEY,
    nama VARCHAR(128),
    jenis_kelamin CHAR(1),
    alamat VARCHAR(128),
    no_telp VARCHAR(20),
    profil VARCHAR(64),
    poin INT,
    FOREIGN KEY (id_user) REFERENCES users(id)
);

CREATE TABLE kategori (
    id_kategori CHAR(1) NOT NULL PRIMARY KEY,
    nama_kategori VARCHAR(20)
);

CREATE TABLE servis (
    id_servis CHAR(1) NOT NULL PRIMARY KEY,
    nama_servis VARCHAR(20)
);

CREATE TABLE barang (
    id_barang CHAR(1) NOT NULL PRIMARY KEY,
    nama_barang VARCHAR(20)
);

CREATE TABLE daftar_harga (
    id_harga INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    id_barang CHAR(1),
    id_kategori CHAR(1),
    id_servis CHAR(1),
    harga INT,
    FOREIGN KEY (id_barang) REFERENCES barang(id_barang),
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori),
    FOREIGN KEY (id_servis) REFERENCES servis(id_servis)
);

CREATE TABLE status (
    id_status CHAR(1) NOT NULL PRIMARY KEY,
    nama_status VARCHAR(20)
);

CREATE TABLE transaksi (
    id_transaksi INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    tgl_masuk DATETIME,
    id_status CHAR(1),
    id_user INT,
    tgl_selesai DATETIME,
    total_harga INT,
    FOREIGN KEY (id_status) REFERENCES status(id_status),
    FOREIGN KEY (id_user) REFERENCES users(id)
);

CREATE TABLE detail_transaksi (
    id_transaksi INT NOT NULL,
    id_barang CHAR(1),
    id_kategori CHAR(1),
    id_servis CHAR(1),
    banyak INT,
    sub_total INT,
    FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi),
    FOREIGN KEY (id_barang) REFERENCES barang(id_barang),
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori),
    FOREIGN KEY (id_servis) REFERENCES servis(id_servis)
);

CREATE TABLE saran_komplain (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    tgl DATETIME,
    isi TEXT,
    tipe CHAR(1),
    balasan TEXT
);