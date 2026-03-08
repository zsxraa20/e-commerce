// DATABASE SIMULASI (Data Awal)
let db = {
    produk: [
        { id: 1, nama: "POCO F6 Pro", kategori: "F Series", harga: 7499000, stok: 3, status: "Aktif", ram: "12GB", storage: "512GB", warna: "Hitam, Putih", gambar: "https://via.placeholder.com/50" },
        { id: 2, nama: "POCO X6 Pro 5G", kategori: "X Series", harga: 4599000, stok: 15, status: "Aktif", ram: "8GB", storage: "256GB", warna: "Kuning, Abu", gambar: "https://via.placeholder.com/50" }
    ],
    transaksi: [
        { 
            inv: "EP-9921", 
            customer: "Andi Wijaya", 
            items: [
                { nama: "POCO F6 Pro", qty: 1, harga: 7499000 },
                { nama: "POCO Buds Pro", qty: 2, harga: 500000 }
            ],
            total: 8499000, 
            status: "Proses",
            buktiBayar: "https://via.placeholder.com/300x500?text=Bukti+Andi" 
        },
        { 
            inv: "EP-9922", 
            customer: "Siti Aminah", 
            items: [
                { nama: "POCO X6 Pro 5G", qty: 1, harga: 4599000 }
            ],
            total: 4599000, 
            status: "Proses",
            buktiBayar: "https://via.placeholder.com/300x500?text=Bukti+Siti" 
        }
    ],
    users: [
        { id: "USR001", nama: "Admin Toko", email: "admin@ephone.com", role: "Admin", status: "Aktif" },
        { id: "USR002", nama: "Zahratu Syita", email: "syita@mail.com", role: "Customer", status: "Aktif" }
    ],
    riwayat: [],
};

let editIndexProduk = -1; 
let editIndexUser = -1; 

// --- 1. FUNGSI NAVIGASI TAB ---
function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
    
    document.getElementById(tabId).classList.add('active');
    
    // Cari elemen menu dan aktifkan
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        if(item.getAttribute('onclick').includes(tabId)) {
            item.classList.add('active');
        }
    });
    
    renderAll();
}

// --- 2. FUNGSI MODAL ---
function openModal(id, mode = 'tambah', index = -1) {
    const modal = document.getElementById(id);
    modal.style.display = "block";

    if (id === 'modal-produk') {
        editIndexProduk = index;
        if (mode === 'edit') {
            document.getElementById('modal-produk-title').innerText = "Edit Produk";
            const p = db.produk[index];
            document.getElementById('p-nama').value = p.nama;
            document.getElementById('p-kat').value = p.kategori;
            document.getElementById('p-harga').value = p.harga;
            document.getElementById('p-stok').value = p.stok;
            document.getElementById('p-ram').value = p.ram;
            document.getElementById('p-storage').value = p.storage;
            document.getElementById('p-warna').value = p.warna;
        } else {
            document.getElementById('modal-produk-title').innerText = "Tambah Produk";
            document.getElementById('formProduk').reset();
        }
        // Di dalam function openModal(id, mode, index)
if (id === 'modal-produk') {
    editIndexProduk = index;
    if (mode === 'edit') {
        const p = db.produk[index];
        // ... isi input lainnya ...
        document.getElementById('p-warna').value = p.warna;
        
        // TAMBAHKAN INI agar tombol upload muncul saat edit
        generateColorUploads(); 
    }
}
    } else if (id === 'modal-user') {
        editIndexUser = index;
        if (mode === 'edit') {
            document.getElementById('modal-user-title').innerText = "Edit User";
            const u = db.users[index];
            document.getElementById('u-id').value = u.id;
            document.getElementById('u-nama').value = u.nama;
            document.getElementById('u-email').value = u.email;
            document.getElementById('u-role').value = u.role;
            document.getElementById('u-status').value = u.status;
        } else {
            document.getElementById('modal-user-title').innerText = "Tambah User";
            document.getElementById('formUser').reset();
        }
    }
}

function closeModal(id) {
    document.getElementById(id).style.display = "none";
}

// --- 3. LOGIKA SIMPAN PRODUK (DENGAN FOTO) ---
// Tambahkan kata 'async' di depan function agar bisa menunggu proses baca gambar
async function simpanProduk() {
    // 1. Ambil data teks biasa
    const nama = document.getElementById('p-nama').value;
    const kategori = document.getElementById('p-kat').value;
    const harga = parseInt(document.getElementById('p-harga').value);
    const stok = parseInt(document.getElementById('p-stok').value);
    const warnaInput = document.getElementById('p-warna').value;
    const ram = document.getElementById('p-ram').value;
    const storage = document.getElementById('p-storage').value;

    // 2. Ambil semua input file gambar varian
    const inputFiles = document.querySelectorAll('.variant-image-input');
    let gambarVarian = {};

    // Fungsi pembantu untuk membaca file menjadi Base64
    const readFile = (file) => {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.onload = (e) => resolve(e.target.result);
            reader.readAsDataURL(file);
        });
    };

    // 3. Loop untuk membaca semua gambar yang diupload
    for (let input of inputFiles) {
        const warna = input.getAttribute('data-warna');
        if (input.files[0]) {
            // Menunggu gambar selesai dibaca
            const base64Image = await readFile(input.files[0]);
            gambarVarian[warna] = base64Image;
        } else {
            // Jika tidak ada gambar, pakai placeholder
            gambarVarian[warna] = "https://via.placeholder.com/150?text=No+Image";
        }
    }

    // 4. Susun objek data produk
    const dataBaru = {
        id: editIndexProduk > -1 ? db.produk[editIndexProduk].id : Date.now(), // ID unik
        nama: nama,
        kategori: kategori,
        harga: harga,
        stok: stok,
        warna: warnaInput,
        ram: ram,
        storage: storage,
        varianGambar: gambarVarian, // Menyimpan objek gambar {Warna: Foto}
        status: "Aktif"
    };

    // 5. Simpan ke "Database"
    if (editIndexProduk > -1) {
        db.produk[editIndexProduk] = dataBaru;
    } else {
        db.produk.push(dataBaru);
    }

    // 6. Tutup modal dan refresh tabel
    closeModal('modal-produk');
    renderAll();
    
    // Reset form agar bersih kembali
    document.getElementById('formProduk').reset();
    document.getElementById('variant-uploads-container').innerHTML = "";
}

// --- 4. LOGIKA SIMPAN USER ---
function simpanUser() {
    const newUser = {
        id: document.getElementById('u-id').value,
        nama: document.getElementById('u-nama').value,
        email: document.getElementById('u-email').value,
        password: document.getElementById('u-pass').value, // Simpan password
        role: document.getElementById('u-role').value,
        status: document.getElementById('u-status').value
    };

    if (editIndexUser > -1) {
        db.users[editIndexUser] = newUser;
    } else {
        db.users.push(newUser);
    }
    
    closeModal('modal-user');
    renderAll();
    alert("Data user berhasil diperbarui!");
}

function hapusUser(index) {
    if (confirm(`Apakah Anda yakin ingin menghapus user ${db.users[index].nama}?`)) {
        db.users.splice(index, 1); // Hapus dari array
        renderAll();
    }
}

// --- 5. RENDER SEMUA DATA ---
function renderAll() {
    // A. Update Dashboard Stats
    if(document.getElementById('stat-produk')) 
        document.getElementById('stat-produk').innerText = db.produk.length;
    if(document.getElementById('stat-transaksi')) 
        document.getElementById('stat-transaksi').innerText = db.transaksi.length;
    if(document.getElementById('stat-user')) 
        document.getElementById('stat-user').innerText = db.users.length;
    if(document.getElementById('stat-low')) {
        const stokRendah = db.produk.filter(p => p.stok < 5).length;
        document.getElementById('stat-low').innerText = stokRendah;
    }

    // B. Render Tabel Dashboard Pesanan Terbaru
    const tableDash = document.getElementById('table-dash-order');
    if(tableDash) {
        tableDash.innerHTML = db.transaksi.map(t => `
            <tr><td>#${t.inv}</td><td>${t.customer}</td><td>Rp ${t.total.toLocaleString()}</td>
            <td><span class="status-badge ${t.status === 'Selesai' ? 'success' : 'pending'}">${t.status}</span></td></tr>
        `).join('');
    }

    // C. Render Produk Hampir Habis di Dashboard
    const tableDashStock = document.getElementById('table-dash-stock');
    if(tableDashStock) {
        tableDashStock.innerHTML = db.produk.filter(p => p.stok < 5).map(p => `
            <tr><td>${p.nama}</td><td style="color:red; font-weight:bold">${p.stok} Left</td></tr>
        `).join('');
    }

   // D. Render Tab Manajemen Produk
    const tableProduk = document.getElementById('table-produk');
    if(tableProduk) {
        tableProduk.innerHTML = db.produk.map((p, i) => {
            // Ambil warna pertama untuk dijadikan foto sampul
            const daftarWarna = p.warna.split(',').map(w => w.trim());
            const warnaUtama = daftarWarna[0];
            
            // Cek apakah ada foto untuk warna tersebut, jika tidak ada pakai placeholder
            let fotoTampil = "https://via.placeholder.com/50";
            if (p.varianGambar && p.varianGambar[warnaUtama]) {
                fotoTampil = p.varianGambar[warnaUtama];
            } else if (p.gambar) { // Backup jika masih pakai data lama
                fotoTampil = p.gambar;
            }

            return `
                <tr>
                    <td><img src="${fotoTampil}" width="50" style="border-radius:5px; height:50px; object-fit:cover;"></td>
                    <td>${p.nama}</td>
                    <td>${p.kategori}</td>
                    <td>Rp ${p.harga.toLocaleString()}</td>
                    <td>${p.stok}</td>
                    <td><span class="status-badge success">${p.status}</span></td>
                    <td>
                        <button class="action-btn" onclick="openModal('modal-produk', 'edit', ${i})"><i class="fas fa-edit"></i></button>
                        <button class="action-btn" style="background:#ff4444;" onclick="hapusProduk(${i})"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // E. Render Tab Manajemen User
    function renderUsers() {
    const tableUser = document.getElementById('table-user');
    if(tableUser) {
        tableUser.innerHTML = db.users.map((u, i) => `
            <tr>
                <td>${u.id}</td>
                <td>${u.nama}</td>
                <td>${u.email}</td>
                <td>••••••••</td>
                <td>${u.role}</td>
                <td><span class="status-badge ${u.status === 'Aktif' ? 'success' : 'pending'}">${u.status}</span></td>
                <td>
                    <div class="user-actions">
                        <button class="action-btn" onclick="openModal('modal-user', 'edit', ${i})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-delete-user" onclick="hapusUser(${i})" title="Hapus User">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }
}
renderUsers();

// F. Render Tab Manajemen Transaksi
const tableTrans = document.getElementById('table-transaksi');
    if(!tableTrans) return;

    tableTrans.innerHTML = db.transaksi.map((t, i) => `
        <tr>
            <td>#${t.inv}</td>
            <td>${t.customer}</td>
            <td>${t.items[0].nama}${t.items.length > 1 ? ` (+${t.items.length - 1})` : ''}</td>
            <td>Rp ${t.total.toLocaleString()}</td>
            <td><span class="status-badge ${t.status === 'Selesai' ? 'success' : 'pending'}">${t.status}</span></td>
            <td>
                <div class="action-group">
                    ${t.status === 'Proses' ? `
                        <button class="action-btn" onclick="selesaikanPesanan(${i})"><i class="fas fa-check"></i></button>
                    ` : ''}
                    <button class="btn-view-bukti" onclick="viewDetail('${t.inv}', 'manajemen')">
                        <i class="fas fa-info-circle"></i> Detail
                    </button>
                </div>
            </td>
        </tr>`).join('');
}

// G. Render Tab Riwayat
function renderRiwayat() {
    const tableRiwayat = document.getElementById('table-riwayat');
    if(!tableRiwayat) return;

    tableRiwayat.innerHTML = db.riwayat.map(r => `
        <tr>
            <td>#${r.inv}</td>
            <td>${r.customer}</td>
            <td><small>${r.waktuSelesai || '-'}</small></td>
            <td>${r.items[0].nama}</td>
            <td>Rp ${r.total.toLocaleString()}</td>
            <td>
                <button class="btn-view-bukti" onclick="viewDetail('${r.inv}', 'riwayat')" style="background:#28a745; border:none; color:white;">
                    <i class="fas fa-file-invoice"></i> Invoice
                </button>
            </td>
        </tr>`).join('');
}


// FUNGSI UNTUK MELIHAT BUKTI (Ditaruh di luar renderAll)
function viewDetail(invId, mode) {
    const data = db.transaksi.find(t => t.inv === invId) || db.riwayat.find(r => r.inv === invId);
    if (!data) return;

    document.getElementById('det-inv-id').innerText = data.inv;
    document.getElementById('det-nama-cust').innerText = data.customer;
    document.getElementById('det-waktu-selesai').innerText = data.waktuSelesai || '';

    document.getElementById('det-list-barang').innerHTML = data.items.map(item => `
        <tr>
            <td style="padding:10px; border-bottom:1px solid #eee;">
                ${item.nama} <br><small>Warna: ${item.warna || '-'}</small>
            </td>
            <td align="center">${item.qty}</td>
            <td align="right">Rp ${(item.harga * item.qty).toLocaleString()}</td>
        </tr>`).join('');

    document.getElementById('det-total-akhir').innerText = "Rp " + data.total.toLocaleString();

    const areaAksi = document.getElementById('modal-action-area');
    if (mode === 'manajemen') {
        areaAksi.innerHTML = `
            <button class="btn-view-bukti" style="width:100%; justify-content:center;" onclick="toggleBukti()">
                <i class="fas fa-image"></i> Lihat Bukti Pembayaran
            </button>
            <div id="wrapper-bukti" style="display:none; margin-top:15px;">
                <img src="${data.buktiBayar}" style="width:100%; border-radius:8px;">
            </div>`;
    } else {
        areaAksi.innerHTML = `
            <button class="btn-view-bukti" style="width:100%; justify-content:center; background:#007bff; color:white;" onclick="alert('Fitur Unduh PDF Aktif')">
                <i class="fas fa-download"></i> Unduh Invoice
            </button>`;
    }

    document.getElementById('modal-detail').style.display = "block";
}

// Fungsi untuk buka-tutup gambar bukti
function toggleBukti() {
    const wrapper = document.getElementById('wrapper-bukti');
    const btn = event.currentTarget;
    
    if (wrapper.style.display === "none") {
        wrapper.style.display = "block";
        btn.innerHTML = '<i class="fas fa-eye-slash"></i> Sembunyikan Bukti';
    } else {
        wrapper.style.display = "none";
        btn.innerHTML = '<i class="fas fa-image"></i> Lihat Bukti Pembayaran';
    }
}

// 2. Fungsi Lihat Invoice (Tanpa Bukti, Ada Nama Customer & Tombol Cetak)
function viewInvoice(invId) {
    const cleanId = invId.replace('#', '');
    const data = db.riwayat.find(r => r.id.replace('#', '') === cleanId) || db.transaksi.find(t => t.inv === cleanId);
    
    if (data) {
        document.getElementById('inv-id-text').innerText = "#" + cleanId;
        document.getElementById('inv-date').innerText = data.tanggal || "03 Maret 2026";
        
        // TAMBAHKAN NAMA CUSTOMER DI INVOICE
        const infoBar = document.querySelector('.invoice-info-bar');
        const customerRow = `<div class="info-row"><span>Customer</span><strong>${data.customer}</strong></div>`;
        
        // Update isi info bar agar ada nama pelanggan
        infoBar.innerHTML = `
            <div class="info-row"><span>Invoice</span><strong id="inv-id-text">#${cleanId}</strong></div>
            ${customerRow}
            <div class="info-row"><span>Date</span><strong id="inv-date">${data.tanggal || '03 Maret 2026'}</strong></div>
            <div class="info-row"><span>Status</span><span id="inv-status-label" class="badge" style="background:#28a745; color:white; padding:2px 8px; border-radius:4px;">Lunas</span></div>
        `;
        
        document.getElementById('invoice-item-list').innerHTML = `
            <tr>
                <td><strong>${data.produk}</strong></td>
                <td align="center">1</td>
                <td align="right">Rp ${(data.harga || data.total).toLocaleString()}</td>
                <td align="right">Rp ${(data.harga || data.total).toLocaleString()}</td>
            </tr>
        `;
        
        document.getElementById('inv-grand-total').innerText = "Rp " + (data.harga || data.total).toLocaleString();
        openModal('modal-invoice');
    }
} 

    // 4. Isi ID Invoice di modal
    const displayId = t.inv || (t.id ? t.id.replace('#', '') : cleanId);
    document.getElementById('inv-id-text').innerText = "#" + displayId;

    // 5. Isi Tanggal (pakai tanggal riwayat kalau ada, kalau nggak pakai hari ini)
    const tgl = t.tanggal || new Date().toLocaleDateString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
    });
    document.getElementById('inv-date').innerText = tgl;
    
    // 6. Atur Status Label (Lunas atau Pending)
    const statusLabel = document.getElementById('inv-status-label');
    const isSelesai = t.status === 'Selesai';
    statusLabel.innerText = isSelesai ? 'Lunas' : 'Pending';
    statusLabel.style.background = isSelesai ? '#28a745' : '#ffc107';

    // 7. Isi Tabel Barang di dalam Invoice
    // Pakai t.total atau t.harga (karena nama variabel di transaksi & riwayat beda)
    const hargaFinal = t.total || t.harga || 0;
    
    document.getElementById('invoice-item-list').innerHTML = `
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;">
                <strong>${t.produk}</strong><br>
                <small style="color: #666;">Original POCO Smartphone</small>
            </td>
            <td align="center" style="padding: 10px; border: 1px solid #ddd;">1</td>
            <td align="right" style="padding: 10px; border: 1px solid #ddd;">Rp ${hargaFinal.toLocaleString()}</td>
            <td align="right" style="padding: 10px; border: 1px solid #ddd;">Rp ${hargaFinal.toLocaleString()}</td>
        </tr>
    `;

    // 8. Isi Total Paling Bawah
    document.getElementById('inv-grand-total').innerText = "Rp " + hargaFinal.toLocaleString();
    
    // 9. Munculkan Modal-nya
    openModal('modal-invoice');



// Fungsi Download JPG
function downloadJPG() {
    html2canvas(document.getElementById('printable-invoice')).then(canvas => {
        const link = document.createElement('a');
        link.download = 'invoice-ephone.jpg';
        link.href = canvas.toDataURL();
        link.click();
    });
}

// Tambahkan di baris paling bawah sebelum window.onload
function formatRupiah(angka) {
    return "Rp " + angka.toLocaleString('id-ID');
}

function hapusProduk(index) {
    if (confirm("Apakah Anda yakin ingin menghapus produk " + db.produk[index].nama + "?")) {
        db.produk.splice(index, 1); 
        renderAll(); 
    }
}

function generateColorUploads() {
    const warnaInput = document.getElementById('p-warna').value;
    const container = document.getElementById('variant-uploads-container');
    
    // Pecah string warna berdasarkan koma dan hilangkan spasi kosong
    const daftarWarna = warnaInput.split(',').map(w => w.trim()).filter(w => w !== "");
    
    // Simpan konten lama agar tidak hilang saat mengetik (opsional, tapi lebih baik dikosongkan dulu untuk refresh)
    container.innerHTML = "";

    daftarWarna.forEach((warna, index) => {
        const div = document.createElement('div');
        div.style.background = "#333";
        div.style.padding = "10px";
        div.style.borderRadius = "8px";
        div.style.border = "1px solid #444";
        
        div.innerHTML = `
            <label style="font-size: 12px; color: #ffd700; display: block; margin-bottom: 5px;">Foto untuk warna: <strong>${warna}</strong></label>
            <input type="file" class="variant-image-input" data-warna="${warna}" accept="image/*">
        `;
        container.appendChild(div);
    });
}

function selesaikanPesanan(index) {
    const pesanan = db.transaksi[index];
    if (confirm(`Selesaikan pesanan #${pesanan.inv}?`)) {
        const sekarang = new Date();
        const waktuSelesai = sekarang.toLocaleDateString('id-ID') + ' ' + 
                            sekarang.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

        pesanan.status = "Selesai";
        pesanan.waktuSelesai = waktuSelesai;

        // Tambahkan ke riwayat tanpa menghapus dari transaksi
        db.riwayat.unshift({...pesanan});
        
        saveData();
        renderAll();
        renderRiwayat();
        alert("Pesanan berhasil dikonfirmasi!");
    }
}

if(localStorage.getItem('ephone_db')) {
    db = JSON.parse(localStorage.getItem('ephone_db'));
}

function saveData() {
    localStorage.setItem('ephone_db', JSON.stringify(db));
}

// Panggil render pertama kali
renderAll();
renderRiwayat();

window.onload = renderAll;