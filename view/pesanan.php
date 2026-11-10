<div class="page-heading"><p class="muted">Catat transaksi dan pantau status pesanan pelanggan.</p><a class="btn btn-primary" href="index.php?page=pesanan&action=create">＋ Buat Pesanan</a></div>
<?php if ($action === 'create' || $action === 'edit'): ?>
<section class="panel form-panel"><div class="panel-title"><div><span class="eyebrow">TRANSAKSI</span><h2><?= $action === 'edit' ? 'Edit Pesanan' : 'Pesanan Baru' ?></h2></div><a class="btn btn-light" href="index.php?page=pesanan">Kembali</a></div>
<?php if (!$menuOptions): ?><div class="alert warning">Tambahkan menu terlebih dahulu sebelum membuat pesanan.</div><?php endif; ?>
<form method="post" action="index.php?page=pesanan&action=save"><input type="hidden" name="id_pesanan" value="<?= e($editData['id_pesanan'] ?? '') ?>">
<div class="form-grid">
<div class="form-group"><label>Nama pelanggan</label><input name="nama_pelanggan" required maxlength="120" value="<?= e($editData['nama_pelanggan'] ?? '') ?>" placeholder="Nama pelanggan"></div>
<div class="form-group"><label>Menu</label><select name="id_menu" id="menu-select" required><option value="">Pilih menu</option><?php foreach ($menuOptions as $menu): ?><option value="<?= e($menu['id_menu']) ?>" data-price="<?= e($menu['harga']) ?>" <?= (string)($editData['id_menu'] ?? '') === (string)$menu['id_menu'] ? 'selected' : '' ?>><?= e($menu['nama_menu']) ?> — Rp <?= number_format((float)$menu['harga'],0,',','.') ?> (stok <?= e($menu['stok']) ?>)</option><?php endforeach; ?></select></div>
<div class="form-group"><label>Jumlah</label><input type="number" name="jumlah" id="jumlah-input" min="1" required value="<?= e($editData['jumlah'] ?? '1') ?>"></div>
<div class="form-group"><label>Tanggal pesanan</label><input type="datetime-local" name="tanggal_pesanan" required value="<?= e(isset($editData['tanggal_pesanan']) ? date('Y-m-d\TH:i', strtotime($editData['tanggal_pesanan'])) : date('Y-m-d\TH:i')) ?>"></div>
<div class="form-group"><label>Status pesanan</label><select name="status_pesanan"><option value="Menunggu" <?= ($editData['status_pesanan'] ?? 'Menunggu') === 'Menunggu' ? 'selected' : '' ?>>Menunggu</option><option value="Diproses" <?= ($editData['status_pesanan'] ?? '') === 'Diproses' ? 'selected' : '' ?>>Diproses</option><option value="Selesai" <?= ($editData['status_pesanan'] ?? '') === 'Selesai' ? 'selected' : '' ?>>Selesai</option><option value="Dibatalkan" <?= ($editData['status_pesanan'] ?? '') === 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option></select></div>
</div>
<div class="total-preview"><span>Estimasi total harga</span><strong id="total-preview">Rp 0</strong><small>Total dihitung ulang di server berdasarkan harga menu dan jumlah.</small></div>
<button class="btn btn-primary" type="submit" <?= !$menuOptions ? 'disabled' : '' ?>>Simpan Pesanan</button></form>
<script>
const menuSelect = document.getElementById('menu-select');
const jumlahInput = document.getElementById('jumlah-input');
const totalPreview = document.getElementById('total-preview');
function updateTotal() {
    const option = menuSelect.options[menuSelect.selectedIndex];
    const price = Number(option ? option.dataset.price : 0);
    const total = price * Number(jumlahInput.value || 0);
    totalPreview.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
}
menuSelect.addEventListener('change', updateTotal);
jumlahInput.addEventListener('input', updateTotal);
updateTotal();
</script></section>
<?php else: ?>
<section class="panel"><div class="panel-title"><div><span class="eyebrow">TRANSAKSI RESTORAN</span><h2>Daftar Pesanan</h2></div><span class="count-pill"><?= count($pesananList) ?> pesanan</span></div>
<div class="table-wrap"><table><thead><tr><th>ID</th><th>Pelanggan</th><th>Menu</th><th>Jumlah</th><th>Total Harga</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php foreach ($pesananList as $item): ?><tr><td class="id-cell">#<?= e($item['id_pesanan']) ?></td><td><strong><?= e($item['nama_pelanggan']) ?></strong></td><td><?= e($item['nama_menu']) ?></td><td><?= e($item['jumlah']) ?></td><td class="price">Rp <?= number_format((float)$item['total_harga'],0,',','.') ?></td><td><?= date('d/m/Y H:i', strtotime($item['tanggal_pesanan'])) ?></td><td><span class="status <?= strtolower($item['status_pesanan']) ?>"><?= e($item['status_pesanan']) ?></span></td><td class="actions"><a class="action-edit" href="index.php?page=pesanan&action=edit&id=<?= e($item['id_pesanan']) ?>">Edit</a><form method="post" action="index.php?page=pesanan&action=delete" onsubmit="return confirm('Hapus pesanan ini?');"><input type="hidden" name="id" value="<?= e($item['id_pesanan']) ?>"><button class="action-delete" type="submit">Hapus</button></form></td></tr><?php endforeach; ?>
<?php if (!$pesananList): ?><tr><td colspan="8" class="empty">Belum ada pesanan yang tercatat.</td></tr><?php endif; ?>
</tbody></table></div></section>
<?php endif; ?>
