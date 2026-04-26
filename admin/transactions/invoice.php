<?php
/**
 * Invoice Print Page (Admin)
 * e-PHONE E-Commerce System
 */

require_once '../../config/db.php';
require_once '../../config/session.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../../index.html');
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "Invoice tidak valid.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM transactions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "Transaksi tidak ditemukan.";
    exit;
}

$t = $res->fetch_assoc();

$itemStmt = $conn->prepare("SELECT product_name, color_name, price, quantity FROM transaction_items WHERE transaction_id = ?");
$itemStmt->bind_param("i", $id);
$itemStmt->execute();
$itemsRes = $itemStmt->get_result();
$items = [];
while ($row = $itemsRes->fetch_assoc()) {
    $items[] = $row;
}

function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function rupiah($v) {
    return 'Rp ' . number_format((float)$v, 0, ',', '.');
}

$createdAt = $t['created_at'] ? date('d/m/Y H:i', strtotime($t['created_at'])) : '-';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo h($t['id']); ?> - e-PHONE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../dashboard-admin/admin.css?v=20260426-invoice-print">
    <style>
        @media print {
            body { background: #fff !important; }
            .invoice-actions { display: none !important; }
            .invoice-container { box-shadow: none !important; border-radius: 0 !important; }
        }
    </style>
</head>
<body class="admin-form-page">
    <main class="product-create-page">
        <div class="modal-content invoice-container">
            <div class="invoice-box">
                <div class="invoice-header">
                    <div>
                        <h2>Invoice <span>#<?php echo h($t['id']); ?></span></h2>
                        <div class="address">
                            e-PHONE Store<br>
                            Malang, Indonesia
                        </div>
                    </div>
                    <p class="watermark">e-PHONE</p>
                </div>

                <div class="invoice-info-bar">
                    <div class="info-row"><span>Pelanggan</span><strong><?php echo h($t['customer_name']); ?></strong></div>
                    <div class="info-row"><span>Telepon</span><strong><?php echo h($t['customer_phone']); ?></strong></div>
                    <div class="info-row"><span>Metode Bayar</span><strong><?php echo h($t['payment_method']); ?></strong></div>
                    <div class="info-row"><span>Tanggal</span><strong><?php echo h($createdAt); ?></strong></div>
                    <div class="info-row"><span>Status</span><strong><?php echo h($t['status']); ?></strong></div>
                </div>

                <div class="detail-block" style="margin-top: 0;">
                    <h3 style="color:#111827;">Alamat Pengiriman</h3>
                    <p class="address" style="margin-top:0;"><?php echo nl2br(h($t['customer_address'])); ?> (<?php echo h($t['kode_pos']); ?>)</p>
                </div>

                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Warna</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Harga</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($items) === 0): ?>
                            <tr><td colspan="5" style="text-align:center;">Tidak ada item</td></tr>
                        <?php else: ?>
                            <?php foreach ($items as $it): ?>
                                <?php
                                    $qty = (int)($it['quantity'] ?? 0);
                                    $price = (float)($it['price'] ?? 0);
                                    $subtotal = $qty * $price;
                                ?>
                                <tr>
                                    <td><?php echo h($it['product_name']); ?></td>
                                    <td><?php echo h($it['color_name'] ?: '-'); ?></td>
                                    <td class="text-right"><?php echo h($qty); ?></td>
                                    <td class="text-right"><?php echo h(rupiah($price)); ?></td>
                                    <td class="text-right"><?php echo h(rupiah($subtotal)); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <tr>
                            <td colspan="4" class="total-amount">Total</td>
                            <td class="total-amount"><?php echo h(rupiah($t['total_amount'])); ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="invoice-footer">
                    <p>Terima kasih sudah berbelanja di e-PHONE.</p>
                    <?php if (!empty($t['additional_notes'])): ?>
                        <p>Catatan: <?php echo h($t['additional_notes']); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="invoice-actions">
                <button type="button" class="btn-print" onclick="window.print()">Cetak</button>
                <button type="button" class="btn-close-inv" onclick="window.close()">Tutup</button>
            </div>
        </div>
    </main>
</body>
</html>

