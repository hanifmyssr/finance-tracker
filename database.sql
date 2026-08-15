-- Database: finance_tracker
CREATE DATABASE IF NOT EXISTS finance_tracker;
USE finance_tracker;

-- Tabel kategori
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    type ENUM('income', 'expense') NOT NULL
);

-- Tabel transaksi
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    transaction_date DATE NOT NULL,
    note VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Data kategori awal
INSERT INTO categories (name, type) VALUES
('Gaji', 'income'),
('Bonus', 'income'),
('Uang Saku', 'income'),
('Makanan', 'expense'),
('Transportasi', 'expense'),
('Hiburan', 'expense'),
('Tagihan', 'expense'),
('Belanja', 'expense'),
('Kesehatan', 'expense'),
('Lainnya', 'expense');
