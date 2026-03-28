<?php
/**
 * Database Check & Auto-Setup
 * Include this at the start of your app to auto-create DB if needed
 */

function checkAndSetupDatabase() {
    $dbHost = "localhost";
    $dbUser = "root";
    $dbPass = "";
    $dbName = "ephone";
    
    // Try to connect to database
    $conn = @new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    
    // If connection failed, try to create database
    if ($conn->connect_error) {
        $rootConn = @new mysqli($dbHost, $dbUser, $dbPass);
        
        if (!$rootConn->connect_error) {
            // Create database
            $rootConn->query("CREATE DATABASE IF NOT EXISTS {$dbName} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $rootConn->select_db($dbName);
            
            // Create tables
            createTables($rootConn);
            
            // Insert default data
            insertDefaultData($rootConn);
            
            $rootConn->close();
        }
    } else {
        // Check if tables exist
        $result = $conn->query("SHOW TABLES LIKE 'users'");
        if ($result->num_rows === 0) {
            createTables($conn);
            insertDefaultData($conn);
        }
        $conn->close();
    }
}

function createTables($conn) {
    $tables = [
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin','user') DEFAULT 'user',
            phone VARCHAR(20),
            address TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            series VARCHAR(10) NOT NULL,
            price DECIMAL(12,0) NOT NULL,
            specs VARCHAR(255),
            description TEXT,
            stock INT DEFAULT 0,
            status ENUM('active','inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS product_colors (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            color_name VARCHAR(50) NOT NULL,
            image_url VARCHAR(255) NOT NULL,
            stock INT DEFAULT 0,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        )",
        "CREATE TABLE IF NOT EXISTS product_specs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            spec_name VARCHAR(100) NOT NULL,
            spec_value VARCHAR(255) NOT NULL,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        )",
        "CREATE TABLE IF NOT EXISTS transactions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            customer_name VARCHAR(100) NOT NULL,
            customer_phone VARCHAR(20) NOT NULL,
            customer_address TEXT NOT NULL,
            payment_method VARCHAR(50) NOT NULL,
            payment_proof VARCHAR(255),
            additional_notes TEXT,
            total_amount DECIMAL(12,0) NOT NULL,
            status ENUM('pending','processing','shipped','completed','cancelled') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        )",
        "CREATE TABLE IF NOT EXISTS transaction_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            transaction_id INT NOT NULL,
            product_id INT NOT NULL,
            product_name VARCHAR(100) NOT NULL,
            color_name VARCHAR(50),
            price DECIMAL(12,0) NOT NULL,
            quantity INT NOT NULL,
            FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id)
        )",
        "CREATE TABLE IF NOT EXISTS contacts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            message TEXT NOT NULL,
            status ENUM('new','read','replied') DEFAULT 'new',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS order_history (
            id INT AUTO_INCREMENT PRIMARY KEY,
            transaction_id INT NOT NULL,
            status VARCHAR(50) NOT NULL,
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE
        )"
    ];
    
    foreach ($tables as $sql) {
        $conn->query($sql);
    }
}

function insertDefaultData($conn) {
    // Check if admin exists
    $result = $conn->query("SELECT id FROM users WHERE email = 'admin@ephone.com'");
    if ($result->num_rows === 0) {
        $adminPass = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'admin')");
        $username = 'admin';
        $email = 'admin@ephone.com';
        $stmt->bind_param("sss", $username, $email, $adminPass);
        $stmt->execute();
    }
    
    // Check if products exist
    $result = $conn->query("SELECT COUNT(*) as count FROM products");
    if ($result->fetch_assoc()['count'] == 0) {
        // Insert sample products
        $products = [
            ['POCO C71', 'C', 1049000, '4GB/8GB RAM, 128GB Storage', 'Budget smartphone', 50],
            ['POCO F6', 'F', 4549000, '12GB RAM, 512GB Storage', 'Flagship killer', 30],
            ['POCO M7', 'M', 2199000, '8GB RAM, 256GB Storage', 'Long battery', 50],
            ['POCO X7 5G', 'X', 3349000, '8GB/12GB RAM, 256GB/512GB Storage', 'Gaming phone', 35]
        ];
        
        $stmt = $conn->prepare("INSERT INTO products (name, series, price, specs, description, stock) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($products as $p) {
            $stmt->bind_param("ssdssi", $p[0], $p[1], $p[2], $p[3], $p[4], $p[5]);
            $stmt->execute();
        }
    }
}

// Uncomment line below to enable auto-setup
// checkAndSetupDatabase();
?>
