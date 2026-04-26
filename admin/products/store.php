<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../index.php');
    exit;
}

function redirectWithError($message) {
    header('Location: create.php?error=' . urlencode($message));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: create.php');
    exit;
}

$namaProduk = isset($_POST['nama_produk']) ? sanitizeInput($conn, $_POST['nama_produk']) : '';
$series = isset($_POST['series']) ? sanitizeInput($conn, $_POST['series']) : '';
$deskripsi = isset($_POST['deskripsi']) ? sanitizeInput($conn, $_POST['deskripsi']) : '';
$harga = isset($_POST['harga']) ? floatval($_POST['harga']) : 0;
$stok = isset($_POST['stok']) ? intval($_POST['stok']) : -1;
$specs = isset($_POST['specs']) ? sanitizeInput($conn, $_POST['specs']) : '';

if (!$namaProduk || !$series || !$deskripsi || $harga <= 0 || $stok < 0) {
    redirectWithError('Semua field wajib diisi. Harga dan stok harus numeric.');
}

if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
    redirectWithError('Gambar produk wajib diupload.');
}

$gambar = $_FILES['gambar'];
$maxSize = 2 * 1024 * 1024;
$allowedExtensions = ['jpg', 'jpeg', 'png'];
$allowedMimeTypes = ['image/jpeg', 'image/png'];
$extension = strtolower(pathinfo($gambar['name'], PATHINFO_EXTENSION));

if ($gambar['size'] > $maxSize) {
    redirectWithError('Ukuran gambar maksimal 2MB.');
}

if (!in_array($extension, $allowedExtensions, true)) {
    redirectWithError('Format gambar harus jpg, jpeg, atau png.');
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $gambar['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedMimeTypes, true)) {
    redirectWithError('File yang diupload bukan gambar jpg/png yang valid.');
}

$uploadDir = '../../public/uploads/produk/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$fileName = 'produk_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
$targetPath = $uploadDir . $fileName;
$dbPath = 'public/uploads/produk/' . $fileName;

if (!move_uploaded_file($gambar['tmp_name'], $targetPath)) {
    redirectWithError('Gagal menyimpan gambar produk.');
}

$conn->begin_transaction();

$stmt = $conn->prepare("INSERT INTO products (name, series, price, specs, description, stock, gambar, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'active')");
$stmt->bind_param("ssdssis", $namaProduk, $series, $harga, $specs, $deskripsi, $stok, $dbPath);

if (!$stmt->execute()) {
    $conn->rollback();
    if (file_exists($targetPath)) {
        unlink($targetPath);
    }
    redirectWithError('Gagal menyimpan produk: ' . $conn->error);
}

$productId = $stmt->insert_id;

$colorName = 'Default';
$colorStmt = $conn->prepare("INSERT INTO product_colors (product_id, color_name, image_url, stock) VALUES (?, ?, ?, ?)");
$colorStmt->bind_param("issi", $productId, $colorName, $dbPath, $stok);

if (!$colorStmt->execute()) {
    $conn->rollback();
    if (file_exists($targetPath)) {
        unlink($targetPath);
    }
    redirectWithError('Gagal menyimpan gambar varian produk: ' . $conn->error);
}

if ($specs !== '') {
    $specName = 'Spesifikasi';
    $specStmt = $conn->prepare("INSERT INTO product_specs (product_id, spec_name, spec_value) VALUES (?, ?, ?)");
    $specStmt->bind_param("iss", $productId, $specName, $specs);

    if (!$specStmt->execute()) {
        $conn->rollback();
        if (file_exists($targetPath)) {
            unlink($targetPath);
        }
        redirectWithError('Gagal menyimpan spesifikasi produk: ' . $conn->error);
    }
}

$conn->commit();

header('Location: ../index.php');
exit;
