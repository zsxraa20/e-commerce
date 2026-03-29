<?php
/**
 * Products API
 * e-PHONE E-Commerce System
 */

require_once '../config/db.php';

// Set JSON header
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get all products or single product
        if (isset($_GET['id'])) {
            getProduct($conn, intval($_GET['id']));
        } else {
            getAllProducts($conn);
        }
        break;
        
    case 'POST':
        require_once '../config/session.php';
        requireAdmin();
        createProduct($conn);
        break;
        
    case 'PUT':
        require_once '../config/session.php';
        requireAdmin();
        updateProduct($conn);
        break;
        
    case 'DELETE':
        require_once '../config/session.php';
        requireAdmin();
        if (isset($_GET['id'])) {
            deleteProduct($conn, intval($_GET['id']));
        } else {
            sendJsonResponse(false, 'Product ID is required');
        }
        break;
        
    default:
        sendJsonResponse(false, 'Invalid request method');
}

/**
 * Get all products with colors and specs
 */
function getAllProducts($conn) {
    $series = isset($_GET['series']) ? sanitizeInput($conn, $_GET['series']) : null;
    $isAdminRequest = isset($_GET['admin']) && $_GET['admin'] === '1';
    $includeInactive = false;

    if ($isAdminRequest) {
        require_once '../config/session.php';
        if (!isLoggedIn() || !isAdmin()) {
            sendJsonResponse(false, 'Access denied. Admin only.');
        }
        $includeInactive = true;
    }

    $sql = "SELECT p.* FROM products p WHERE 1=1";
    if (!$includeInactive) {
        $sql .= " AND p.status = 'active'";
    }
    if ($series) {
        $sql .= " AND p.series = ?";
    }
    $sql .= " ORDER BY p.id ASC";

    $stmt = $conn->prepare($sql);
    if ($series) {
        $stmt->bind_param("s", $series);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($product = $result->fetch_assoc()) {
        $product['colors'] = getProductColors($conn, $product['id']);
        $product['specs'] = getProductSpecs($conn, $product['id']);
        $products[] = $product;
    }

    sendJsonResponse(true, 'Products retrieved successfully', $products);
}

/**
 * Get single product with colors and specs
 */
function getProduct($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        sendJsonResponse(false, 'Product not found');
    }
    
    $product = $result->fetch_assoc();
    $product['colors'] = getProductColors($conn, $product['id']);
    $product['specs'] = getProductSpecs($conn, $product['id']);
    
    sendJsonResponse(true, 'Product retrieved successfully', $product);
}

/**
 * Get product colors
 */
function getProductColors($conn, $productId) {
    $stmt = $conn->prepare("SELECT color_name as name, image_url as imageUrl, stock FROM product_colors WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $colors = [];
    while ($color = $result->fetch_assoc()) {
        $colors[] = $color;
    }
    return $colors;
}

/**
 * Get product specifications
 */
function getProductSpecs($conn, $productId) {
    $stmt = $conn->prepare("SELECT spec_name, spec_value FROM product_specs WHERE product_id = ? ORDER BY id ASC");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $specs = [];
    while ($spec = $result->fetch_assoc()) {
        $specs[] = $spec['spec_name'] . ': ' . $spec['spec_value'];
    }
    return $specs;
}

/**
 * Create new product (Admin only)
 */
function createProduct($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $name = sanitizeInput($conn, $data['name']);
    $series = sanitizeInput($conn, $data['series']);
    $price = floatval($data['price']);
    $specs = sanitizeInput($conn, $data['specs']);
    $description = sanitizeInput($conn, $data['description']);
    $stock = intval($data['stock']);
    
    $stmt = $conn->prepare("INSERT INTO products (name, series, price, specs, description, stock) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdssi", $name, $series, $price, $specs, $description, $stock);
    
    if ($stmt->execute()) {
        $productId = $stmt->insert_id;
        
        // Insert colors if provided
        if (isset($data['colors']) && is_array($data['colors'])) {
            foreach ($data['colors'] as $color) {
                $colorName = sanitizeInput($conn, $color['name']);
                $imageUrl = sanitizeInput($conn, $color['imageUrl']);
                $colorStock = intval($color['stock']);
                
                $stmt = $conn->prepare("INSERT INTO product_colors (product_id, color_name, image_url, stock) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("issi", $productId, $colorName, $imageUrl, $colorStock);
                $stmt->execute();
            }
        }
        
        sendJsonResponse(true, 'Product created successfully', ['id' => $productId]);
    } else {
        sendJsonResponse(false, 'Failed to create product: ' . $conn->error);
    }
}

/**
 * Update product (Admin only)
 */
function updateProduct($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id'])) {
        sendJsonResponse(false, 'Product ID is required');
    }
    
    $id = intval($data['id']);
    $name = sanitizeInput($conn, $data['name']);
    $series = sanitizeInput($conn, $data['series']);
    $price = floatval($data['price']);
    $specs = sanitizeInput($conn, $data['specs']);
    $description = sanitizeInput($conn, $data['description']);
    $stock = intval($data['stock']);
    $status = sanitizeInput($conn, $data['status']);
    
    $stmt = $conn->prepare("UPDATE products SET name=?, series=?, price=?, specs=?, description=?, stock=?, status=? WHERE id=?");
    $stmt->bind_param("ssdssisi", $name, $series, $price, $specs, $description, $stock, $status, $id);
    
    if ($stmt->execute()) {
        sendJsonResponse(true, 'Product updated successfully');
    } else {
        sendJsonResponse(false, 'Failed to update product: ' . $conn->error);
    }
}

/**
 * Delete product (Admin only)
 */
function deleteProduct($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        sendJsonResponse(true, 'Product deleted successfully');
    } else {
        sendJsonResponse(false, 'Failed to delete product: ' . $conn->error);
    }
}
?>
