-- e-PHONE E-Commerce Database Schema
-- Run this SQL to create all required tables

-- Create database
CREATE DATABASE IF NOT EXISTS ephone;
USE ephone;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user',
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    series VARCHAR(10) NOT NULL,
    price DECIMAL(12,0) NOT NULL,
    specs VARCHAR(255),
    description TEXT,
    stock INT DEFAULT 0,
    gambar VARCHAR(255),
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Product colors table
CREATE TABLE IF NOT EXISTS product_colors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    color_name VARCHAR(50) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    stock INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Product specifications table
CREATE TABLE IF NOT EXISTS product_specs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    spec_name VARCHAR(100) NOT NULL,
    spec_value VARCHAR(255) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Transactions/Orders table
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_address TEXT NOT NULL,
    kode_pos VARCHAR(10) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    payment_proof VARCHAR(255),
    additional_notes TEXT,
    total_amount DECIMAL(12,0) NOT NULL,
    status ENUM('pending','processing','shipped','completed','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Transaction items table
CREATE TABLE IF NOT EXISTS transaction_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(100) NOT NULL,
    color_name VARCHAR(50),
    price DECIMAL(12,0) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Contact messages table
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new','read','replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order history/tracking table
CREATE TABLE IF NOT EXISTS order_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@ephone.com', '$2y$12$pvcuby/o4eP8ICM3B654r.cXW1a5Ah.vaoCtqGPFlRL.bkBRVSVaO', 'admin');

-- Insert sample products (POCO Series)
INSERT INTO products (name, series, price, specs, description, stock, status) VALUES
('POCO C71', 'C', 1049000, '4GB/8GB RAM, 128GB Storage', 'Budget smartphone with UNISOC processor', 50, 'active'),
('POCO C75', 'C', 1349000, '6GB/8GB RAM, 128GB/256GB Storage', 'Entry-level gaming phone', 45, 'active'),
('POCO C85', 'C', 1499000, '6GB/8GB RAM, 128GB/256GB Storage', 'Large battery budget phone', 40, 'active'),
('POCO F6', 'F', 4549000, '12GB RAM, 512GB Storage', 'Flagship killer with Snapdragon 8s Gen 3', 30, 'active'),
('POCO F6 PRO', 'F', 9499000, '12GB/16GB RAM, 256GB/512GB/1TB Storage', 'Premium flagship experience', 25, 'active'),
('POCO F7', 'F', 5999000, '12GB RAM, 256GB/512GB Storage', 'Latest flagship processor', 35, 'active'),
('POCO F7 PRO', 'F', 7499000, '12GB RAM, 256GB/512GB Storage', 'Pro-level performance', 20, 'active'),
('POCO F7 ULTRA', 'F', 9799000, '12GB/16GB RAM, 256GB/512GB Storage', 'Ultimate gaming phone', 15, 'active'),
('POCO M6', 'M', 1899000, '8GB RAM, 256GB Storage', 'Mid-range photography phone', 40, 'active'),
('POCO M6 PRO 5G', 'M', 2330000, '12GB RAM, 256GB Storage', '5G enabled mid-ranger', 35, 'active'),
('POCO M7', 'M', 2199000, '8GB RAM, 256GB Storage', 'Long-lasting battery', 50, 'active'),
('POCO M7 PRO 5G', 'M', 2599000, '12GB RAM, 512GB Storage', 'Pro mid-range with 5G', 30, 'active'),
('POCO X6 5G', 'X', 2499000, '12GB RAM, 256GB Storage', '5G gaming on a budget', 45, 'active'),
('POCO X6 PRO', 'X', 6999000, '8GB/12GB RAM, 256GB/512GB Storage', 'Pro-level gaming', 25, 'active'),
('POCO X7 5G', 'X', 3349000, '8GB/12GB RAM, 256GB/512GB Storage', 'Latest X series with Dimensity', 35, 'active'),
('POCO X7 PRO 5G', 'X', 4599000, '12GB RAM, 512GB Storage', 'Pro gaming with large battery', 30, 'active');

-- Insert product colors
INSERT INTO product_colors (product_id, color_name, image_url, stock) VALUES
(1, 'Black', 'assets/images/POCO SERI C/C71/C71-BLACK.png', 20),
(1, 'Blue', 'assets/images/POCO SERI C/C71/C71-BLUE.png', 15),
(1, 'Gold', 'assets/images/POCO SERI C/C71/C71-GOLD.png', 15),
(2, 'Black', 'assets/images/POCO SERI C/C75/C75-BLACK.png', 25),
(2, 'Green', 'assets/images/POCO SERI C/C75/C75-GREEN.png', 20),
(3, 'Black', 'assets/images/POCO SERI C/C85/C85-BLACK.png', 15),
(3, 'Green', 'assets/images/POCO SERI C/C85/C85-GREEN.png', 15),
(3, 'Purple', 'assets/images/POCO SERI C/C85/C85-PURPLE.png', 10),
(4, 'Black', 'assets/images/POCO SERI F/F6/F6-BLACK.png', 12),
(4, 'Silver', 'assets/images/POCO SERI F/F6/F6-SILVER.png', 10),
(4, 'Titanium', 'assets/images/POCO SERI F/F6/F6-TITANIUM.png', 8),
(5, 'Black', 'assets/images/POCO SERI F/F6 PRO/F6 PRO-BLACK.png', 15),
(5, 'White', 'assets/images/POCO SERI F/F6 PRO/F6 PRO-WHITE.png', 10),
(6, 'Black', 'assets/images/POCO SERI F/F7/F7-BLACK.png', 15),
(6, 'Silver', 'assets/images/POCO SERI F/F7/F7-SILVER.png', 12),
(6, 'White', 'assets/images/POCO SERI F/F7/F7-WHITE.png', 8),
(7, 'Black', 'assets/images/POCO SERI F/F7 PRO/F7 PRO-BLACK.png', 12),
(7, 'Silver', 'assets/images/POCO SERI F/F7 PRO/F7 PRO-SILVER.png', 8),
(8, 'Black', 'assets/images/POCO SERI F/F7 ULTRA/F7 ULTRA-BLACK.png', 8),
(8, 'Silver', 'assets/images/POCO SERI F/F7 ULTRA/F7 ULTRA-SILVER.png', 4),
(8, 'Yellow', 'assets/images/POCO SERI F/F7 ULTRA/F7 ULTRA-YELLOW.png', 3),
(9, 'Black', 'assets/images/POCO SERI M/M6/M6-BLACK.png', 15),
(9, 'Purple', 'assets/images/POCO SERI M/M6/M6-PURPLE.png', 15),
(9, 'White', 'assets/images/POCO SERI M/M6/M6-WHITE.png', 10),
(10, 'Black', 'assets/images/POCO SERI M/M6 PRO/M6 PRO-BLACK.png', 15),
(10, 'Blue', 'assets/images/POCO SERI M/M6 PRO/M6 PRO-BLUE.png', 10),
(10, 'Purple', 'assets/images/POCO SERI M/M6 PRO/M6 PRO-PURPLE.png', 10),
(11, 'Black', 'assets/images/POCO SERI M/M7/M7-BLACK.png', 20),
(11, 'Blue', 'assets/images/POCO SERI M/M7/M7-BLUE.png', 15),
(11, 'Silver', 'assets/images/POCO SERI M/M7/M7-SILVER.png', 15),
(12, 'Black', 'assets/images/POCO SERI M/M7 PRO/M7 PRO-BLACK.png', 18),
(12, 'Silver', 'assets/images/POCO SERI M/M7 PRO/M7 PRO-SILVER.png', 12),
(13, 'Black', 'assets/images/POCO SERI X/X6 5g/X6 5G-BLACK.png', 25),
(13, 'White', 'assets/images/POCO SERI X/X6 5g/X6 5G-WHITE.png', 20),
(14, 'Black', 'assets/images/POCO SERI X/X6 5g PRO/X6 PRO-BLACK.png', 10),
(14, 'Silver', 'assets/images/POCO SERI X/X6 5g PRO/X6 PRO-SILVER.png', 8),
(14, 'Yellow', 'assets/images/POCO SERI X/X6 5g PRO/X6 PRO-YELLOW.png', 7),
(15, 'Black', 'assets/images/POCO SERI X/X7/X7-BLACK.png', 15),
(15, 'Green', 'assets/images/POCO SERI X/X7/X7-GREEN.png', 12),
(15, 'Silver', 'assets/images/POCO SERI X/X7/X7-SILVER.png', 8),
(16, 'Black', 'assets/images/POCO SERI X/X7 PRO/X7 PRO-BLACK.png', 12),
(16, 'Green', 'assets/images/POCO SERI X/X7 PRO/X7 PRO-GREEN.png', 10),
(16, 'Yellow', 'assets/images/POCO SERI X/X7 PRO/X7 PRO-YELLOW.png', 8);

-- Insert product specifications
INSERT INTO product_specs (product_id, spec_name, spec_value) VALUES
(1, 'Layar', '6,88 inch 120Hz'),
(1, 'Processor', 'UNISOC T7250'),
(1, 'RAM', '4GB/8GB'),
(1, 'Storage', '128GB'),
(1, 'Kamera', '32MP + 8MP'),
(1, 'Baterai', '5200 mAh, 15W Fast Charging'),
(2, 'Layar', '6,88 inch 120 Hz'),
(2, 'Processor', 'MediaTek Helio G81 Ultra'),
(2, 'RAM', '6GB/8GB'),
(2, 'Storage', '128GB/256GB'),
(2, 'Kamera', '50MP + 13MP'),
(2, 'Baterai', '5160 mAh, 18W Fast Charging'),
(3, 'Layar', '6,9 inch 120Hz'),
(3, 'Processor', 'MediaTek Helio G81-Ultra'),
(3, 'RAM', '6GB/8GB'),
(3, 'Storage', '128GB/256GB'),
(3, 'Kamera', '50MP + 8MP'),
(3, 'Baterai', '6000 mAh, 33W Fast Charging'),
(4, 'Layar', '6,67 inci AMOLED 120Hz'),
(4, 'Processor', 'Snapdragon 8s Gen 3'),
(4, 'RAM', '12GB'),
(4, 'Storage', '512GB'),
(4, 'Kamera', '50MP + 8MP + 20MP'),
(4, 'Baterai', '5000 mAh, 90W Fast Charging'),
(5, 'Layar', '6,67 inch AMOLED 120Hz'),
(5, 'Processor', 'Snapdragon 8s Gen 3'),
(5, 'RAM', '12GB/16GB'),
(5, 'Storage', '256GB/512GB/1TB'),
(5, 'Kamera', '50MP + 8MP + 2MP'),
(5, 'Baterai', '5000mAh, 120W Fast Charging'),
(6, 'Layar', '6,83 inch AMOLED 120Hz 1,5K'),
(6, 'Processor', 'Snapdragon 8s Gen 4'),
(6, 'RAM', '12GB'),
(6, 'Storage', '256GB/512GB'),
(6, 'Kamera', '50MP + 8MP + 20MP'),
(6, 'Baterai', '6.500 mAh, 90W Fast Charging'),
(7, 'Layar', '6,67 inch AMOLED 2K 120Hz'),
(7, 'Processor', 'Snapdragon 8 Gen 3'),
(7, 'RAM', '12GB'),
(7, 'Storage', '256GB/512GB'),
(7, 'Kamera', '50MP + 8MP + 20MP'),
(7, 'Baterai', '6000mAh, 90W Fast Charging'),
(8, 'Layar', '6.67 inch AMOLED 2K 120Hz'),
(8, 'Processor', 'Snapdragon 8 Elite'),
(8, 'RAM', '12GB/16GB'),
(8, 'Storage', '256GB/512GB'),
(8, 'Kamera', '50MP + 50MP + 32MP'),
(8, 'Baterai', '5300mAh, 120W Fast Charging'),
(9, 'Layar', '6,79 inch FHD+ 90 Hz'),
(9, 'Processor', 'Helio G91-Ultra'),
(9, 'RAM', '8GB'),
(9, 'Storage', '256GB'),
(9, 'Kamera', '108MP + 2MP + 13MP'),
(9, 'Baterai', '5030 mAh, 33W Fast Charging'),
(10, 'Layar', '6.5 inch AMOLED 120Hz'),
(10, 'Processor', 'MediaTek Helio G99 Ultra'),
(10, 'RAM', '12GB'),
(10, 'Storage', '256GB'),
(10, 'Kamera', '64MP + 8MP + 2MP + 16MP'),
(10, 'Baterai', '5000 mAh, 67W Fast Charging'),
(11, 'Layar', '6,9 inch 144Hz AdaptiveSync'),
(11, 'Processor', 'Snapdragon 685 (6 nm)'),
(11, 'RAM', '8GB'),
(11, 'Storage', '256GB'),
(11, 'Kamera', '50MP + 0,08MP + 8MP'),
(11, 'Baterai', '7000mAh, 33W Fast Charging'),
(12, 'Layar', '6,67 inch AMOLED FHD+ 120Hz'),
(12, 'Processor', 'Dimensity 7025-Ultra'),
(12, 'RAM', '12GB'),
(12, 'Storage', '512GB'),
(12, 'Kamera', '50MP + 2MP + 20MP'),
(12, 'Baterai', '5110mAh, 45W Fast Charging'),
(13, 'Layar', '6,67 inch Flow AMOLED CrystalRes 1.5K 120 Hz'),
(13, 'Processor', 'Snapdragon 7s Gen 2'),
(13, 'RAM', '12GB'),
(13, 'Storage', '256GB'),
(13, 'Kamera', '64MP + 2MP + 8MP + 16MP'),
(13, 'Baterai', '5000mAh, 67W Fast Charging'),
(14, 'Layar', '6,67 inch Flow AMOLED CrystalRes 1.5K 120Hz'),
(14, 'Processor', 'MediaTek Dimensity 8300-Ultra'),
(14, 'RAM', '8GB/12GB'),
(14, 'Storage', '256GB/512GB'),
(14, 'Kamera', '64MP + 8MP + 4MP'),
(14, 'Baterai', '5000mAh, 67W Fast Charging'),
(15, 'Layar', '6,67 inch Flow AMOLED CrystalRes 1.5K 120 Hz'),
(15, 'Processor', 'MediaTek Dimensity 7300-Ultra (4 nm)'),
(15, 'RAM', '8GB/12GB'),
(15, 'Storage', '256GB/512GB'),
(15, 'Kamera', '50MP + 8MP + 2MP'),
(15, 'Baterai', '5110 mAh, 45W Fast Charging'),
(16, 'Layar', '6,67 inci Flow AMOLED CrystalRes 1.5K'),
(16, 'Processor', 'MediaTek Dimensity 8400-Ultra (4 nm)'),
(16, 'RAM', '12GB'),
(16, 'Storage', '512GB'),
(16, 'Kamera', '50MP + 8MP'),
(16, 'Baterai', '6000 mAh, 90W Fast Charging');

-- Create database user (run as root if needed)
-- CREATE USER IF NOT EXISTS 'ephoneuser'@'localhost' IDENTIFIED BY '123456';
-- GRANT ALL PRIVILEGES ON ephone.* TO 'ephoneuser'@'localhost';
-- FLUSH PRIVILEGES;
