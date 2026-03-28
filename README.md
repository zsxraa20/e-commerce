# e-PHONE E-Commerce System

Sistem e-commerce lengkap untuk penjualan smartphone POCO dengan fitur keranjang belanja, checkout, admin dashboard, dan manajemen produk.

## 📋 Fitur

### Frontend
- ✅ Landing page dengan katalog produk
- ✅ Filter produk berdasarkan seri (C, F, M, X)
- ✅ Detail produk dengan pilihan warna
- ✅ Keranjang belanja (add, edit quantity, remove)
- ✅ Checkout dengan berbagai metode pembayaran
- ✅ Form kontak
- ✅ Responsive design

### Backend
- ✅ Login/Register dengan password hashing (bcrypt)
- ✅ Session management
- ✅ CRUD Produk (Admin)
- ✅ Manajemen Transaksi
- ✅ Manajemen User
- ✅ Contact Messages
- ✅ Dashboard Statistik

### Keamanan
- ✅ SQL Injection prevention (Prepared Statements)
- ✅ Password hashing
- ✅ Input sanitization
- ✅ Session-based authentication
- ✅ Role-based access control

## 🗂️ Struktur Folder

```
e-commerce/
├── index.html                 # Landing page
├── script.js                  # Frontend JavaScript
├── style.css                  # Styling
├── config/
│   ├── db.php                # Database configuration
│   └── session.php           # Session management
├── api/
│   ├── login.php             # Login API
│   ├── register.php          # Register API
│   ├── logout.php            # Logout API
│   ├── products.php          # Products CRUD API
│   ├── checkout.php          # Checkout API
│   ├── contact.php           # Contact form API
│   ├── transactions.php      # Transactions API
│   ├── users.php             # Users API
│   ├── admin-stats.php       # Admin statistics API
│   └── contacts.php          # Contacts API
├── admin/
│   └── index.php             # Admin dashboard
├── dashboard-admin/          # Legacy admin (redirects)
├── login-register/
│   ├── Login/
│   │   ├── index.html
│   │   ├── script.js
│   │   └── style.css
│   └── Register/
│       ├── index.register.html
│       ├── script.register.js
│       └── style.register.css
├── assets/
│   ├── images/               # Product images
│   └── icons/                # Icons
├── database/
│   └── schema.sql            # Database schema
└── note.txt                  # Legacy database setup
```

## 🚀 Setup

### 1. Database Setup

Jalankan file SQL untuk membuat database dan tabel:

```bash
mysql -u root -p < database/schema.sql
```

Atau copy-paste isi `database/schema.sql` ke phpMyAdmin.

### 2. Konfigurasi Database

Edit `config/db.php` jika perlu mengubah kredensial database:

```php
$host = "localhost";
$user = "ephoneuser";
$pass = "123456";
$db = "ephone";
```

### 3. Default Login

**Admin:**
- Email: `admin@ephone.com`
- Password: `admin123`

**User:**
- Register via halaman register

### 4. Menjalankan Project

Letakkan folder di web server (XAMPP htdocs, dll):
```
http://localhost/e-commerce/
```

## 📊 Database Schema

### Tabel: `users`
- id, username, email, password, role, phone, address, created_at

### Tabel: `products`
- id, name, series, price, specs, description, stock, status

### Tabel: `product_colors`
- id, product_id, color_name, image_url, stock

### Tabel: `product_specs`
- id, product_id, spec_name, spec_value

### Tabel: `transactions`
- id, user_id, customer_name, customer_phone, customer_address, payment_method, payment_proof, additional_notes, total_amount, status, created_at

### Tabel: `transaction_items`
- id, transaction_id, product_id, product_name, color_name, price, quantity

### Tabel: `contacts`
- id, name, email, message, status, created_at

### Tabel: `order_history`
- id, transaction_id, status, notes, created_at

## 🔒 Keamanan yang Diterapkan

1. **SQL Injection Prevention**: Menggunakan prepared statements di semua query
2. **Password Hashing**: Menggunakan `password_hash()` dengan bcrypt
3. **Input Sanitization**: Semua input di-sanitize dengan `htmlspecialchars()` dan `strip_tags()`
4. **Session Management**: Session-based authentication dengan role checking
5. **CSRF Protection**: Dapat ditambahkan token CSRF untuk form

## 🐛 Troubleshooting

### Error: "Database connection failed"
- Pastikan MySQL running
- Cek kredensial di `config/db.php`
- Pastikan database `ephone` sudah dibuat

### Error: "Failed to fetch products"
- Cek console browser untuk detail error
- Pastikan file `api/products.php` ada dan readable
- Cek error log PHP

### Tidak bisa login
- Pastikan session PHP aktif
- Cek apakah tabel `users` sudah terisi
- Admin default password sudah di-hash

## 📝 API Endpoints

### Authentication
- `POST /api/login.php` - Login
- `POST /api/register.php` - Register
- `GET /api/logout.php` - Logout

### Products
- `GET /api/products.php` - Get all products
- `GET /api/products.php?id={id}` - Get single product
- `POST /api/products.php` - Create product (Admin)
- `PUT /api/products.php` - Update product (Admin)
- `DELETE /api/products.php?id={id}` - Delete product (Admin)

### Checkout & Transactions
- `POST /api/checkout.php` - Create order
- `GET /api/transactions.php` - Get transactions
- `PUT /api/transactions.php` - Update status (Admin)

### Users
- `GET /api/users.php` - Get all users (Admin)
- `GET /api/users.php?profile=1` - Get profile
- `PUT /api/users.php` - Update profile

### Admin
- `GET /api/admin-stats.php` - Get dashboard statistics (Admin)
- `GET /api/contacts.php` - Get contact messages (Admin)

## 🎯 Fitur yang Sudah Diperbaiki

✅ SQL Injection vulnerability di login & register
✅ Password sekarang di-hash (tidak plain text)
✅ Products di-load dari database (tidak hardcoded)
✅ Checkout sekarang menyimpan ke database
✅ Contact form menyimpan ke database
✅ Admin dashboard menampilkan data real-time
✅ Session management untuk login
✅ API response dalam format JSON konsisten
✅ Error handling yang proper

## 📱 Screenshots

Tersedia di folder `assets/images/`

## 👨‍💻 Developer

Project ini dibuat sebagai project pembelajaran e-commerce dengan PHP, MySQL, dan JavaScript.

## 📄 License

MIT License - Free for personal and commercial use.
