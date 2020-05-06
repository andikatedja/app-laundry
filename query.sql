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