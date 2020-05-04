CREATE DATABASE app_laundry;

USE app_laundry;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(64) UNIQUE,
    password VARCHAR(255),
    role CHAR(1),
    name VARCHAR(128),
    address VARCHAR(128),
    phone VARCHAR(20)
);