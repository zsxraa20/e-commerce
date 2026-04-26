<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../index.php');
    exit;
}

$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - e-PHONE Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../dashboard-admin/admin.css?v=20260426-product-upload">
</head>
<body class="admin-form-page">
    <main class="product-create-page">
        <div class="product-create-header">
            <div>
                <h1>Tambah Produk</h1>
                <p>Tambahkan produk manual beserta gambar utama.</p>
            </div>
            <a href="../index.php" class="btn-cancel">Kembali</a>
        </div>

        <?php if ($error): ?>
            <div class="form-alert error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form class="product-create-form admin-form" action="store.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama_produk">Nama Produk</label>
                <input type="text" id="nama_produk" name="nama_produk" required>
            </div>

            <div class="form-group">
                <label for="series">Seri</label>
                <select id="series" name="series" required>
                    <option value="C">Seri C</option>
                    <option value="F">Seri F</option>
                    <option value="M">Seri M</option>
                    <option value="X">Seri X</option>
                </select>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" required></textarea>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" id="harga" name="harga" min="1" required>
                </div>

                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" id="stok" name="stok" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label for="specs">Spesifikasi Singkat</label>
                <input type="text" id="specs" name="specs" placeholder="Contoh: 8GB RAM, 256GB Storage">
            </div>

            <div class="form-group">
                <label for="gambar">Upload Gambar</label>
                <input type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,image/jpeg,image/png" required>
                <small class="form-hint">Format jpg, jpeg, atau png. Maksimal 2MB.</small>
            </div>

            <div class="form-actions">
                <a href="../index.php" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-save">Simpan Produk</button>
            </div>
        </form>
    </main>
</body>
</html>
