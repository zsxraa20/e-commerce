<?php
/**
 * Admin Dashboard
 * e-PHONE E-Commerce System
 */

require_once '../config/db.php';
require_once '../config/session.php';

// Check if user is admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../index.html');
    exit;
}

// Get admin info
$adminName = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - e-PHONE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../dashboard-admin/admin.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>
<body>

    <div class="sidebar">
        <h2>e-PHONE Admin</h2>
        <div class="nav-menu">
            <a href="#" class="nav-item active" onclick="showTab('dashboard')"><i class="fas fa-home"></i> Dashboard</a>
            <a href="#" class="nav-item" onclick="showTab('produk')"><i class="fas fa-box"></i> Produk</a>
            <a href="#" class="nav-item" onclick="showTab('transaksi')"><i class="fas fa-shopping-cart"></i> Transaksi</a>
            <a href="#" class="nav-item" onclick="showTab('user')"><i class="fas fa-users"></i> Pelanggan</a>
            <a href="#" class="nav-item" onclick="showTab('kontak')"><i class="fas fa-envelope"></i> Pesan</a>
        </div>
        <a href="#" class="nav-item logout" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <!-- Dashboard Section -->
        <section id="dashboard" class="tab-content active">
            <div class="header-main">
                <h1>Ringkasan Toko</h1>
                <p>Halo, <?php echo htmlspecialchars($adminName); ?>! 👋</p>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Produk</h3>
                    <p id="stat-produk">0</p>
                </div>
                <div class="stat-card">
                    <h3>Total Transaksi</h3>
                    <p id="stat-transaksi">0</p>
                </div>
                <div class="stat-card">
                    <h3>Total Pengguna</h3>
                    <p id="stat-user">0</p>
                </div>
                <div class="stat-card" style="border-left: 5px solid #ff4444;">
                    <h3>Stok Hampir Habis</h3>
                    <p id="stat-low">0</p>
                </div>
            </div>

            <div class="dashboard-layout">
                <div class="recent-orders">
                    <h2>Pesanan Terbaru</h2>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Pembeli</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="table-dash-order"></tbody>
                        </table>
                    </div>
                </div>
                <div class="recent-orders low-stock-panel">
                    <h2>Produk Hampir Habis</h2>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Sisa Stok</th>
                                </tr>
                            </thead>
                            <tbody id="table-dash-stock"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section id="produk" class="tab-content">
            <div class="header-main">
                <h1>Manajemen Produk</h1>
                <button class="btn-add" onclick="openProductModal('add')">+ Tambah Produk</button>
            </div>
            <div class="filter-row">
                <select id="filter-series" onchange="filterProducts()">
                    <option value="">Semua Seri</option>
                    <option value="C">Seri C</option>
                    <option value="F">Seri F</option>
                    <option value="M">Seri M</option>
                    <option value="X">Seri X</option>
                </select>
            </div>
            <div class="table-wrapper main-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Seri</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-produk"></tbody>
                </table>
            </div>
        </section>

        <!-- Transactions Section -->
        <section id="transaksi" class="tab-content">
            <h1>Manajemen Transaksi</h1>
            <div class="filter-row">
                <select id="filter-status" onchange="filterTransactions()">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="table-wrapper main-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-transaksi"></tbody>
                </table>
            </div>
        </section>

        <!-- Users Section -->
        <section id="user" class="tab-content">
            <div class="header-main">
                <h1>Manajemen Pelanggan</h1>
            </div>
            <div class="table-wrapper main-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Bergabung</th>
                        </tr>
                    </thead>
                    <tbody id="table-users"></tbody>
                </table>
            </div>
        </section>

        <!-- Contact Messages Section -->
        <section id="kontak" class="tab-content">
            <h1>Pesan Kontak</h1>
            <div class="table-wrapper main-table">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Pesan</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody id="table-kontak"></tbody>
                </table>
            </div>
        </section>
    </div>

    <!-- Product Modal -->
    <div id="product-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeProductModal()">&times;</span>
            <h2 id="modal-title">Tambah Produk</h2>
            <form id="product-form">
                <input type="hidden" id="product-id">
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" id="product-name" required>
                </div>
                <div class="form-group">
                    <label>Seri</label>
                    <select id="product-series" required>
                        <option value="C">Seri C</option>
                        <option value="F">Seri F</option>
                        <option value="M">Seri M</option>
                        <option value="X">Seri X</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" id="product-price" required>
                </div>
                <div class="form-group">
                    <label>Spesifikasi</label>
                    <input type="text" id="product-specs" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea id="product-description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" id="product-stock" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select id="product-status">
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>
                <button type="submit" class="btn-save">Simpan</button>
            </form>
        </div>
    </div>

    <script>
        // Tab Navigation
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
            
            // Load data based on tab
            if (tabName === 'dashboard') loadDashboardStats();
            if (tabName === 'produk') loadProducts();
            if (tabName === 'transaksi') loadTransactions();
            if (tabName === 'user') loadUsers();
            if (tabName === 'kontak') loadContacts();
        }

        // Load Dashboard Stats
        async function loadDashboardStats() {
            try {
                const response = await fetch('../api/admin-stats.php');
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('stat-produk').textContent = data.data.total_products;
                    document.getElementById('stat-transaksi').textContent = data.data.total_transactions;
                    document.getElementById('stat-user').textContent = data.data.total_users;
                    document.getElementById('stat-low').textContent = data.data.low_stock;
                    
                    // Recent orders
                    const ordersHtml = data.data.recent_transactions.map(t => `
                        <tr>
                            <td>#${t.id}</td>
                            <td>${t.customer_name}</td>
                            <td>Rp ${parseInt(t.total_amount).toLocaleString('id-ID')}</td>
                            <td><span class="status-badge status-${t.status}">${t.status}</span></td>
                        </tr>
                    `).join('');
                    document.getElementById('table-dash-order').innerHTML = ordersHtml || '<tr><td colspan="4" style="text-align: center;">Belum ada pesanan</td></tr>';
                    
                    // Low stock products
                    const stockHtml = data.data.low_stock_products.map(p => `
                        <tr>
                            <td>${p.name}</td>
                            <td style="color: #ff4444; font-weight: bold;">${p.stock}</td>
                        </tr>
                    `).join('');
                    document.getElementById('table-dash-stock').innerHTML = stockHtml || '<tr><td colspan="2" style="text-align: center;">Tidak ada produk stok rendah</td></tr>';
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // Load Products
        async function loadProducts(series = '') {
            try {
                let url = '../api/products.php';
                if (series) url += '?series=' + series;
                
                const response = await fetch(url);
                const data = await response.json();
                
                if (data.success) {
                    const html = data.data.map(p => `
                        <tr>
                            <td>${p.id}</td>
                            <td>${p.name}</td>
                            <td>${p.series}</td>
                            <td>Rp ${parseInt(p.price).toLocaleString('id-ID')}</td>
                            <td>${p.stock}</td>
                            <td><span class="status-badge status-${p.status}">${p.status}</span></td>
                            <td>
                                <button class="btn-edit" onclick="editProduct(${p.id})">Edit</button>
                                <button class="btn-delete" onclick="deleteProduct(${p.id})">Hapus</button>
                            </td>
                        </tr>
                    `).join('');
                    document.getElementById('table-produk').innerHTML = html || '<tr><td colspan="7" style="text-align: center;">Tidak ada produk</td></tr>';
                }
            } catch (error) {
                console.error('Error loading products:', error);
            }
        }

        function filterProducts() {
            const series = document.getElementById('filter-series').value;
            loadProducts(series);
        }

        // Load Transactions
        async function loadTransactions(status = '') {
            try {
                const response = await fetch('../api/transactions.php');
                const data = await response.json();
                
                if (data.success) {
                    let transactions = data.data;
                    if (status) {
                        transactions = transactions.filter(t => t.status === status);
                    }
                    
                    const html = transactions.map(t => `
                        <tr>
                            <td>#${t.id}</td>
                            <td>${t.customer_name}</td>
                            <td>Rp ${parseInt(t.total_amount).toLocaleString('id-ID')}</td>
                            <td><span class="status-badge status-${t.status}">${t.status}</span></td>
                            <td>${new Date(t.created_at).toLocaleDateString('id-ID')}</td>
                            <td>
                                <button class="btn-edit" onclick="viewTransaction(${t.id})">Detail</button>
                                <button class="btn-edit" onclick="updateStatus(${t.id})">Update</button>
                            </td>
                        </tr>
                    `).join('');
                    document.getElementById('table-transaksi').innerHTML = html || '<tr><td colspan="6" style="text-align: center;">Tidak ada transaksi</td></tr>';
                }
            } catch (error) {
                console.error('Error loading transactions:', error);
            }
        }

        function filterTransactions() {
            const status = document.getElementById('filter-status').value;
            loadTransactions(status);
        }

        // Load Users
        async function loadUsers() {
            try {
                const response = await fetch('../api/users.php');
                const data = await response.json();
                
                if (data.success) {
                    const html = data.data.map(u => `
                        <tr>
                            <td>${u.id}</td>
                            <td>${u.username}</td>
                            <td>${u.email}</td>
                            <td>${u.phone || '-'}</td>
                            <td>${new Date(u.created_at).toLocaleDateString('id-ID')}</td>
                        </tr>
                    `).join('');
                    document.getElementById('table-users').innerHTML = html || '<tr><td colspan="5" style="text-align: center;">Tidak ada pengguna</td></tr>';
                }
            } catch (error) {
                console.error('Error loading users:', error);
            }
        }

        // Load Contacts
        async function loadContacts() {
            try {
                const response = await fetch('../api/contacts.php');
                const data = await response.json();
                
                if (data.success) {
                    const html = data.data.map(c => `
                        <tr>
                            <td>${c.name}</td>
                            <td>${c.email}</td>
                            <td>${c.message.substring(0, 50)}${c.message.length > 50 ? '...' : ''}</td>
                            <td>${new Date(c.created_at).toLocaleDateString('id-ID')}</td>
                        </tr>
                    `).join('');
                    document.getElementById('table-kontak').innerHTML = html || '<tr><td colspan="4" style="text-align: center;">Tidak ada pesan</td></tr>';
                }
            } catch (error) {
                console.error('Error loading contacts:', error);
            }
        }

        // Product Modal
        function openProductModal(mode) {
            document.getElementById('product-modal').style.display = 'block';
            document.getElementById('modal-title').textContent = mode === 'add' ? 'Tambah Produk' : 'Edit Produk';
            if (mode === 'add') {
                document.getElementById('product-form').reset();
                document.getElementById('product-id').value = '';
            }
        }

        function closeProductModal() {
            document.getElementById('product-modal').style.display = 'none';
        }

        // Product Form Submit
        document.getElementById('product-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const id = document.getElementById('product-id').value;
            const productData = {
                name: document.getElementById('product-name').value,
                series: document.getElementById('product-series').value,
                price: document.getElementById('product-price').value,
                specs: document.getElementById('product-specs').value,
                description: document.getElementById('product-description').value,
                stock: document.getElementById('product-stock').value,
                status: document.getElementById('product-status').value
            };
            
            if (id) productData.id = id;
            
            try {
                const response = await fetch('../api/products.php', {
                    method: id ? 'PUT' : 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(productData)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert(id ? 'Produk berhasil diupdate!' : 'Produk berhasil ditambahkan!');
                    closeProductModal();
                    loadProducts();
                } else {
                    alert(data.message || 'Gagal menyimpan produk');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            }
        });

        // Delete Product
        async function deleteProduct(id) {
            if (!confirm('Yakin ingin menghapus produk ini?')) return;
            
            try {
                const response = await fetch('../api/products.php?id=' + id, {
                    method: 'DELETE'
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Produk berhasil dihapus!');
                    loadProducts();
                } else {
                    alert(data.message || 'Gagal menghapus produk');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            }
        }

        // Update Transaction Status
        async function updateStatus(id) {
            const statuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];
            const status = prompt('Pilih status baru:\n1. pending\n2. processing\n3. shipped\n4. completed\n5. cancelled');
            
            if (!status || status < 1 || status > 5) return;
            
            try {
                const response = await fetch('../api/transactions.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        id: id,
                        status: statuses[status - 1],
                        notes: 'Status updated by admin'
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Status berhasil diupdate!');
                    loadTransactions();
                } else {
                    alert(data.message || 'Gagal update status');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            }
        }

        // Logout
        async function logout() {
            try {
                await fetch('../api/logout.php');
                window.location.href = '../index.html';
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Load initial data
        document.addEventListener('DOMContentLoaded', loadDashboardStats);
    </script>
</body>
</html>
