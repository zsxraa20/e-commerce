<?php
/**
 * e-PHONE Auto Installer
 * Automatically creates database and tables if they don't exist
 */

// Configuration
$dbHost = "localhost";
$dbUser = "root";      // Change to your MySQL username
$dbPass = "";          // Change to your MySQL password
$dbName = "ephone";

// ANSI colors for terminal output
$green = "\033[32m";
$red = "\033[31m";
$yellow = "\033[33m";
$reset = "\033[0m";

echo "=====================================\n";
echo "  e-PHONE Auto Installer\n";
echo "=====================================\n\n";

// Step 1: Connect to MySQL (without database)
echo "{$yellow}Step 1: Connecting to MySQL...{$reset}\n";

$conn = new mysqli($dbHost, $dbUser, $dbPass);

if ($conn->connect_error) {
    echo "{$red}✗ Failed to connect to MySQL!{$reset}\n";
    echo "Error: " . $conn->connect_error . "\n";
    echo "\nPlease check your MySQL credentials in install.php\n";
    exit(1);
}

echo "{$green}✓ Connected to MySQL{$reset}\n\n";

// Step 2: Create database if not exists
echo "{$yellow}Step 2: Creating database '{$dbName}'...{$reset}\n";

$sql = "CREATE DATABASE IF NOT EXISTS {$dbName} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

if ($conn->query($sql)) {
    echo "{$green}✓ Database created or already exists{$reset}\n\n";
} else {
    echo "{$red}✗ Failed to create database!{$reset}\n";
    echo "Error: " . $conn->error . "\n";
    exit(1);
}

// Step 3: Select database
echo "{$yellow}Step 3: Selecting database...{$reset}\n";

if ($conn->select_db($dbName)) {
    echo "{$green}✓ Database selected{$reset}\n\n";
} else {
    echo "{$red}✗ Failed to select database!{$reset}\n";
    exit(1);
}

// Step 4: Create tables
echo "{$yellow}Step 4: Creating tables...{$reset}\n\n";

$tables = [
    'users' => "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin','user') DEFAULT 'user',
        phone VARCHAR(20),
        address TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    'products' => "CREATE TABLE IF NOT EXISTS products (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    'product_colors' => "CREATE TABLE IF NOT EXISTS product_colors (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        color_name VARCHAR(50) NOT NULL,
        image_url VARCHAR(255) NOT NULL,
        stock INT DEFAULT 0,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    'product_specs' => "CREATE TABLE IF NOT EXISTS product_specs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        spec_name VARCHAR(100) NOT NULL,
        spec_value VARCHAR(255) NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    'transactions' => "CREATE TABLE IF NOT EXISTS transactions (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    'transaction_items' => "CREATE TABLE IF NOT EXISTS transaction_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        transaction_id INT NOT NULL,
        product_id INT NOT NULL,
        product_name VARCHAR(100) NOT NULL,
        color_name VARCHAR(50),
        price DECIMAL(12,0) NOT NULL,
        quantity INT NOT NULL,
        FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    'contacts' => "CREATE TABLE IF NOT EXISTS contacts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        message TEXT NOT NULL,
        status ENUM('new','read','replied') DEFAULT 'new',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    'order_history' => "CREATE TABLE IF NOT EXISTS order_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        transaction_id INT NOT NULL,
        status VARCHAR(50) NOT NULL,
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
];

$successCount = 0;
foreach ($tables as $tableName => $sql) {
    echo "  Creating table '{$tableName}'... ";
    if ($conn->query($sql)) {
        echo "{$green}✓{$reset}\n";
        $successCount++;
    } else {
        echo "{$red}✗{$reset}\n";
        echo "    Error: " . $conn->error . "\n";
    }
}

echo "\n{$green}✓ Created {$successCount}/" . count($tables) . " tables{$reset}\n\n";

// Step 5: Insert default admin
echo "{$yellow}Step 5: Creating default admin user...{$reset}\n";

$checkAdmin = $conn->query("SELECT id FROM users WHERE email = 'admin@ephone.com'");
if ($checkAdmin->num_rows === 0) {
    $adminPass = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'admin')");
    $adminUser = 'admin';
    $adminEmail = 'admin@ephone.com';
    $stmt->bind_param("sss", $adminUser, $adminEmail, $adminPass);
    
    if ($stmt->execute()) {
        echo "{$green}✓ Admin user created{$reset}\n";
        echo "  Email: admin@ephone.com\n";
        echo "  Password: admin123\n\n";
    } else {
        echo "{$red}✗ Failed to create admin user{$reset}\n\n";
    }
} else {
    echo "{$yellow}⚠ Admin user already exists{$reset}\n\n";
}

// Step 6: Insert sample products
echo "{$yellow}Step 6: Inserting sample products...{$reset}\n";

$checkProducts = $conn->query("SELECT COUNT(*) as count FROM products");
$productCount = $checkProducts->fetch_assoc()['count'];

if ($productCount == 0) {
    // Insert sample products
    $sampleProducts = [
        ['POCO C71', 'C', 1049000, '4GB/8GB RAM, 128GB Storage', 'Budget smartphone with UNISOC processor', 50],
        ['POCO C75', 'C', 1349000, '6GB/8GB RAM, 128GB/256GB Storage', 'Entry-level gaming phone', 45],
        ['POCO C85', 'C', 1499000, '6GB/8GB RAM, 128GB/256GB Storage', 'Large battery budget phone', 40],
        ['POCO F6', 'F', 4549000, '12GB RAM, 512GB Storage', 'Flagship killer with Snapdragon 8s Gen 3', 30],
        ['POCO F6 PRO', 'F', 9499000, '12GB/16GB RAM, 256GB/512GB/1TB Storage', 'Premium flagship experience', 25],
        ['POCO F7', 'F', 5999000, '12GB RAM, 256GB/512GB Storage', 'Latest flagship processor', 35],
        ['POCO F7 PRO', 'F', 7499000, '12GB RAM, 256GB/512GB Storage', 'Pro-level performance', 20],
        ['POCO F7 ULTRA', 'F', 9799000, '12GB/16GB RAM, 256GB/512GB Storage', 'Ultimate gaming phone', 15],
        ['POCO M6', 'M', 1899000, '8GB RAM, 256GB Storage', 'Mid-range photography phone', 40],
        ['POCO M6 PRO 5G', 'M', 2330000, '12GB RAM, 256GB Storage', '5G enabled mid-ranger', 35],
        ['POCO M7', 'M', 2199000, '8GB RAM, 256GB Storage', 'Long-lasting battery', 50],
        ['POCO M7 PRO 5G', 'M', 2599000, '12GB RAM, 512GB Storage', 'Pro mid-range with 5G', 30],
        ['POCO X6 5G', 'X', 2499000, '12GB RAM, 256GB Storage', '5G gaming on a budget', 45],
        ['POCO X6 PRO', 'X', 6999000, '8GB/12GB RAM, 256GB/512GB Storage', 'Pro-level gaming', 25],
        ['POCO X7 5G', 'X', 3349000, '8GB/12GB RAM, 256GB/512GB Storage', 'Latest X series with Dimensity', 35],
        ['POCO X7 PRO 5G', 'X', 4599000, '12GB RAM, 512GB Storage', 'Pro gaming with large battery', 30]
    ];
    
    $stmt = $conn->prepare("INSERT INTO products (name, series, price, specs, description, stock) VALUES (?, ?, ?, ?, ?, ?)");
    
    foreach ($sampleProducts as $product) {
        $stmt->bind_param("ssdssi", $product[0], $product[1], $product[2], $product[3], $product[4], $product[5]);
        $stmt->execute();
    }
    
    echo "{$green}✓ Inserted " . count($sampleProducts) . " sample products{$reset}\n\n";
} else {
    echo "{$yellow}⚠ Products already exist ({$productCount} products){$reset}\n\n";
}

// Step 7: Create database user (optional)
echo "{$yellow}Step 7: Creating database user...{$reset}\n";

// Check if user already exists
$checkUser = $conn->query("SELECT user FROM mysql.user WHERE user = 'ephoneuser' AND host = 'localhost'");
if ($checkUser->num_rows === 0) {
    $createUser = $conn->query("CREATE USER 'ephoneuser'@'localhost' IDENTIFIED BY '123456'");
    if ($createUser) {
        $grant = $conn->query("GRANT ALL PRIVILEGES ON {$dbName}.* TO 'ephoneuser'@'localhost'");
        $flush = $conn->query("FLUSH PRIVILEGES");
        echo "{$green}✓ Database user 'ephoneuser' created{$reset}\n";
        echo "  Username: ephoneuser\n";
        echo "  Password: 123456\n\n";
    } else {
        echo "{$yellow}⚠ Could not create user (may need root privileges){$reset}\n";
        echo "  You can manually create user or use root credentials\n\n";
    }
} else {
    echo "{$yellow}⚠ User 'ephoneuser' already exists{$reset}\n\n";
}

// Summary
echo "=====================================\n";
echo "{$green}  Installation Complete! ✓{$reset}\n";
echo "=====================================\n\n";

echo "Database: {$dbName}\n";
echo "Tables: {$successCount} created\n\n";

echo "Default Login:\n";
echo "  Email: admin@ephone.com\n";
echo "  Password: admin123\n\n";

echo "Next steps:\n";
echo "  1. Update config/db.php with your database credentials\n";
echo "  2. Access the website via browser\n";
echo "  3. Login with default admin credentials\n\n";

$conn->close();
?>
