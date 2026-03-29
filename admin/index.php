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
    <link rel="stylesheet" href="../dashboard-admin/admin.css?v=20260329-modern-statusgui-detail">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>
<body>

    <div class="sidebar">
        <h2>e-PHONE Admin</h2>
        <div class="nav-menu">
            <a href="#" class="nav-item active" onclick="showTab('dashboard', this)"><i class="fas fa-home"></i> Dashboard</a>
            <a href="#" class="nav-item" onclick="showTab('produk', this)"><i class="fas fa-box"></i> Produk</a>
            <a href="#" class="nav-item" onclick="showTab('transaksi', this)"><i class="fas fa-shopping-cart"></i> Transaksi</a>
            <a href="#" class="nav-item" onclick="showTab('user', this)"><i class="fas fa-users"></i> Pelanggan</a>
            <a href="#" class="nav-item" onclick="showTab('kontak', this)"><i class="fas fa-envelope"></i> Pesan</a>
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
                <div class="stat-card stat-danger">
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
                            <th>Status</th>
                            <th>Balasan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
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

    <!-- Transaction Detail Modal -->
    <div id="transaction-modal" class="modal" style="display: none;">
        <div class="modal-content transaction-modal-content">
            <span class="close" onclick="closeTransactionModal()">&times;</span>
            <h2>Detail Transaksi <span id="detail-transaction-id"></span></h2>

            <div class="transaction-meta">
                <div class="meta-card">
                    <small>Pelanggan</small>
                    <strong id="detail-customer">-</strong>
                </div>
                <div class="meta-card">
                    <small>Telepon</small>
                    <strong id="detail-phone">-</strong>
                </div>
                <div class="meta-card">
                    <small>Metode Bayar</small>
                    <strong id="detail-payment">-</strong>
                </div>
                <div class="meta-card">
                    <small>Status</small>
                    <span id="detail-status" class="status-badge">-</span>
                </div>
                <div class="meta-card">
                    <small>Total</small>
                    <strong id="detail-total">-</strong>
                </div>
                <div class="meta-card">
                    <small>Tanggal</small>
                    <strong id="detail-date">-</strong>
                </div>
            </div>

            <div class="detail-block">
                <h3>Alamat Pengiriman</h3>
                <p id="detail-address" class="detail-text">-</p>
            </div>

            <div class="detail-block">
                <h3>Item Pesanan</h3>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Warna</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="detail-items-body">
                            <tr><td colspan="5" style="text-align:center;">Memuat...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="detail-block">
                <h3>Riwayat Status</h3>
                <ul id="detail-history" class="history-list">
                    <li>Memuat riwayat...</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="status-modal" class="modal" style="display: none;">
        <div class="modal-content status-modal-content">
            <span class="close" onclick="closeStatusModal()">&times;</span>
            <h2>Update Status Transaksi</h2>
            <form id="status-form">
                <input type="hidden" id="status-transaction-id">

                <div class="form-group">
                    <label for="new-status">Pilih Status</label>
                    <select id="new-status" required>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status-notes">Catatan (opsional)</label>
                    <textarea id="status-notes" rows="3" placeholder="Contoh: Paket sedang diproses"></textarea>
                </div>

                <div class="status-modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeStatusModal()">Batal</button>
                    <button type="submit" class="btn-save">Simpan Status</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reply Contact Modal -->
    <div id="reply-modal" class="modal" style="display: none;">
        <div class="modal-content status-modal-content">
            <span class="close" onclick="closeReplyModal()">&times;</span>
            <h2>Balas Pesan Kontak</h2>
            <form id="reply-form">
                <input type="hidden" id="reply-contact-id">

                <div class="form-group">
                    <label>Nama Pengirim</label>
                    <input type="text" id="reply-contact-name" readonly>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="text" id="reply-contact-email" readonly>
                </div>

                <div class="form-group">
                    <label>Pesan Masuk</label>
                    <textarea id="reply-contact-message" rows="4" readonly></textarea>
                </div>

                <div class="form-group">
                    <label for="reply-message">Isi Balasan</label>
                    <textarea id="reply-message" rows="4" required placeholder="Tulis balasan untuk pelanggan..."></textarea>
                </div>

                <div class="status-modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeReplyModal()">Batal</button>
                    <button type="submit" class="btn-save">Kirim Balasan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="admin-toast" class="admin-toast" aria-live="polite"></div>

    <script>
        let transactionStatusMap = {};

        function showToast(message, type = 'success') {
            const toast = document.getElementById('admin-toast');
            if (!toast) return;

            toast.textContent = message;
            toast.className = `admin-toast show ${type}`;

            clearTimeout(window.__adminToastTimeout);
            window.__adminToastTimeout = setTimeout(() => {
                toast.className = 'admin-toast';
            }, 2200);
        }

        function formatCurrency(value) {
            return `Rp ${parseInt(value || 0).toLocaleString('id-ID')}`;
        }

        function formatDateTime(dateString) {
            if (!dateString) return '-';
            const d = new Date(dateString);
            if (Number.isNaN(d.getTime())) return dateString;
            return d.toLocaleString('id-ID', {
                day: '2-digit', month: '2-digit', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });
        }

        function closeTransactionModal() {
            document.getElementById('transaction-modal').style.display = 'none';
        }

        async function viewTransaction(id) {
            const modal = document.getElementById('transaction-modal');
            modal.style.display = 'block';

            document.getElementById('detail-transaction-id').textContent = `#${id}`;
            document.getElementById('detail-customer').textContent = 'Memuat...';
            document.getElementById('detail-phone').textContent = '-';
            document.getElementById('detail-payment').textContent = '-';
            document.getElementById('detail-total').textContent = '-';
            document.getElementById('detail-date').textContent = '-';
            document.getElementById('detail-address').textContent = '-';
            document.getElementById('detail-items-body').innerHTML = '<tr><td colspan="5" style="text-align:center;">Memuat detail item...</td></tr>';
            document.getElementById('detail-history').innerHTML = '<li>Memuat riwayat...</li>';

            try {
                const response = await fetch(`../api/transactions.php?id=${id}`);
                const data = await response.json();

                if (!data.success) {
                    showToast(data.message || 'Gagal memuat detail transaksi', 'error');
                    return;
                }

                const t = data.data;
                document.getElementById('detail-customer').textContent = t.customer_name || '-';
                document.getElementById('detail-phone').textContent = t.customer_phone || '-';
                document.getElementById('detail-payment').textContent = t.payment_method || '-';
                document.getElementById('detail-total').textContent = formatCurrency(t.total_amount);
                document.getElementById('detail-date').textContent = formatDateTime(t.created_at);
                document.getElementById('detail-address').textContent = t.customer_address || '-';

                const statusEl = document.getElementById('detail-status');
                statusEl.className = `status-badge status-${t.status}`;
                statusEl.textContent = t.status || '-';

                const items = Array.isArray(t.items) ? t.items : [];
                const itemsHtml = items.map(item => {
                    const qty = parseInt(item.quantity || 0, 10);
                    const price = parseInt(item.price || 0, 10);
                    const subtotal = qty * price;

                    return `
                        <tr>
                            <td>${item.product_name || '-'}</td>
                            <td>${item.color_name || '-'}</td>
                            <td>${qty}</td>
                            <td>${formatCurrency(price)}</td>
                            <td>${formatCurrency(subtotal)}</td>
                        </tr>
                    `;
                }).join('');

                document.getElementById('detail-items-body').innerHTML =
                    itemsHtml || '<tr><td colspan="5" style="text-align:center;">Tidak ada item</td></tr>';

                const history = Array.isArray(t.history) ? t.history : [];
                const historyHtml = history.map(h => `
                    <li>
                        <span class="status-badge status-${h.status}">${h.status}</span>
                        <span class="history-time">${formatDateTime(h.created_at)}</span>
                        <div class="history-note">${h.notes || '-'}</div>
                    </li>
                `).join('');

                document.getElementById('detail-history').innerHTML =
                    historyHtml || '<li>Belum ada riwayat status</li>';
            } catch (error) {
                console.error('Error loading transaction detail:', error);
                showToast('Terjadi kesalahan saat memuat detail', 'error');
            }
        }

        // Tab Navigation
        function showTab(tabName, clickedElement = null) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            document.getElementById(tabName).classList.add('active');
            if (clickedElement) clickedElement.classList.add('active');
            
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
                const params = new URLSearchParams({ admin: '1' });
                if (series) params.set('series', series);

                const response = await fetch(`../api/products.php?${params.toString()}`);
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
                    transactionStatusMap = Object.fromEntries(transactions.map(t => [String(t.id), t.status]));

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
                                <button class="btn-edit" onclick="updateStatus(${t.id})">Ubah Status</button>
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
                    const html = data.data.map(c => {
                        const status = (c.status || 'new').toLowerCase();
                        const statusClass = `status-${status}`;
                        const messagePreview = `${c.message.substring(0, 60)}${c.message.length > 60 ? '...' : ''}`;
                        const replyText = c.admin_reply || '-';
                        const replyPreview = c.admin_reply
                            ? `${c.admin_reply.substring(0, 60)}${c.admin_reply.length > 60 ? '...' : ''}`
                            : '-';
                        const repliedAt = c.replied_at ? `\n<small>${formatDateTime(c.replied_at)}</small>` : '';

                        return `
                            <tr>
                                <td>${c.name}</td>
                                <td>${c.email}</td>
                                <td title="${c.message.replace(/"/g, '&quot;')}">${messagePreview}</td>
                                <td><span class="status-badge ${statusClass}">${status}</span></td>
                                <td title="${replyText.replace(/"/g, '&quot;')}">${replyPreview}${repliedAt}</td>
                                <td>${new Date(c.created_at).toLocaleDateString('id-ID')}</td>
                                <td>
                                    <button class="btn-edit" onclick='openReplyModal(${c.id}, ${JSON.stringify(c.name)}, ${JSON.stringify(c.email)}, ${JSON.stringify(c.message)}, ${JSON.stringify(c.admin_reply || '')})'>Balas</button>
                                    ${status === 'new' ? `<button class="btn-cancel" onclick="markContactAsRead(${c.id})">Tandai Dibaca</button>` : ''}
                                </td>
                            </tr>
                        `;
                    }).join('');
                    document.getElementById('table-kontak').innerHTML = html || '<tr><td colspan="7" style="text-align: center;">Tidak ada pesan</td></tr>';
                }
            } catch (error) {
                console.error('Error loading contacts:', error);
            }
        }

        function openReplyModal(id, name, email, message, existingReply = '') {
            document.getElementById('reply-contact-id').value = id;
            document.getElementById('reply-contact-name').value = name || '';
            document.getElementById('reply-contact-email').value = email || '';
            document.getElementById('reply-contact-message').value = message || '';
            document.getElementById('reply-message').value = existingReply || '';
            document.getElementById('reply-modal').style.display = 'block';
        }

        function closeReplyModal() {
            document.getElementById('reply-modal').style.display = 'none';
            document.getElementById('reply-form').reset();
        }

        async function markContactAsRead(id) {
            try {
                const response = await fetch('../api/contacts.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, status: 'read' })
                });

                const data = await response.json();
                if (data.success) {
                    showToast('Pesan ditandai sebagai dibaca', 'success');
                    loadContacts();
                } else {
                    showToast(data.message || 'Gagal update status pesan', 'error');
                }
            } catch (error) {
                console.error('Error updating contact status:', error);
                showToast('Terjadi kesalahan saat update status pesan', 'error');
            }
        }

        document.getElementById('reply-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const id = parseInt(document.getElementById('reply-contact-id').value, 10);
            const reply_message = document.getElementById('reply-message').value.trim();

            if (!reply_message) {
                showToast('Balasan tidak boleh kosong', 'error');
                return;
            }

            try {
                const response = await fetch('../api/contacts.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, reply_message })
                });

                const data = await response.json();
                if (data.success) {
                    closeReplyModal();
                    showToast('Balasan berhasil dikirim', 'success');
                    loadContacts();
                } else {
                    showToast(data.message || 'Gagal mengirim balasan', 'error');
                }
            } catch (error) {
                console.error('Error replying contact:', error);
                showToast('Terjadi kesalahan saat mengirim balasan', 'error');
            }
        });

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

        // Edit Product
        async function editProduct(id) {
            try {
                const response = await fetch(`../api/products.php?id=${id}`);
                const data = await response.json();

                if (!data.success || !data.data) {
                    alert(data.message || 'Data produk tidak ditemukan');
                    return;
                }

                const product = data.data;
                openProductModal('edit');

                document.getElementById('product-id').value = product.id || '';
                document.getElementById('product-name').value = product.name || '';
                document.getElementById('product-series').value = product.series || 'C';
                document.getElementById('product-price').value = product.price || 0;
                document.getElementById('product-specs').value = Array.isArray(product.specs)
                    ? product.specs.join(', ')
                    : (product.specs || '');
                document.getElementById('product-description').value = product.description || '';
                document.getElementById('product-stock').value = product.stock || 0;
                document.getElementById('product-status').value = product.status || 'active';
            } catch (error) {
                console.error('Error loading product detail:', error);
                alert('Gagal memuat data produk');
            }
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

        // Update Transaction Status (GUI Modal)
        function updateStatus(id) {
            const modal = document.getElementById('status-modal');
            const idInput = document.getElementById('status-transaction-id');
            const statusSelect = document.getElementById('new-status');
            const notesInput = document.getElementById('status-notes');

            idInput.value = id;
            statusSelect.value = transactionStatusMap[String(id)] || 'pending';
            notesInput.value = '';
            modal.style.display = 'block';
        }

        function closeStatusModal() {
            document.getElementById('status-modal').style.display = 'none';
            document.getElementById('status-form').reset();
        }

        document.getElementById('status-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const id = parseInt(document.getElementById('status-transaction-id').value, 10);
            const status = document.getElementById('new-status').value;
            const notes = document.getElementById('status-notes').value.trim() || 'Status updated by admin';

            try {
                const response = await fetch('../api/transactions.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, status, notes })
                });

                const data = await response.json();

                if (data.success) {
                    closeStatusModal();
                    showToast('Status transaksi berhasil diupdate', 'success');

                    const activeFilter = document.getElementById('filter-status')?.value || '';
                    loadTransactions(activeFilter);
                    loadDashboardStats();
                } else {
                    showToast(data.message || 'Gagal update status', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat update status', 'error');
            }
        });

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
        document.addEventListener('DOMContentLoaded', () => {
            loadDashboardStats();

            window.addEventListener('click', (e) => {
                if (e.target?.id === 'transaction-modal') closeTransactionModal();
                if (e.target?.id === 'status-modal') closeStatusModal();
                if (e.target?.id === 'product-modal') closeProductModal();
                if (e.target?.id === 'reply-modal') closeReplyModal();
            });

            document.addEventListener('keydown', (e) => {
                if (e.key !== 'Escape') return;
                closeTransactionModal();
                closeStatusModal();
                closeProductModal();
                closeReplyModal();
            });
        });
    </script>
</body>
</html>
