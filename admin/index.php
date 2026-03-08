<?php
session_start();
include '../config/db.php';

// Cek login admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

// Hitung total data
$total_produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jumlah FROM produk"))['jumlah'];
$total_transaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jumlah FROM transaksi"))['jumlah'];
$total_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jumlah FROM users"))['jumlah'];

// Ambil 5 transaksi terbaru
$transaksi_baru = mysqli_query($conn, "
    SELECT t.*, u.nama
    FROM transaksi t
    JOIN users u ON t.user_id = u.id
    ORDER BY t.id DESC
    LIMIT 5
");

// Ambil 5 produk terbaru
$produk_baru = mysqli_query($conn, "
    SELECT * FROM produk ORDER BY id DESC LIMIT 5
");
?>

<!--Navbar-->
  <header>
   <div class="header-container">
    <div class="logo">
     <div class="logo-icon">
      <img src="assets/icons/ICON.png" alt="logo-icon" class="logo-icon">
     </div>
     <div class="logo-text">
      <h1>e-PHONE</h1>
     </div>
    </div>
    <nav>
     <ul>
      <li><a href="#beranda">Beranda</a></li>
      <li><a href="#produk">Produk</a></li>
      <li><a href="#testimoni">Testimoni</a></li>
      <li><a href="#tentang">Tentang</a></li>
      <li><a href="#kontak">Kontak</a></li>
     </ul>
    </nav><button class="cart-button" id="cartBtn"> 🛒 Keranjang <span class="cart-count" id="cartCount">0</span> </button>
   </div>
  </header>