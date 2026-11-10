<div class="page-heading"><p class="muted">Kelola katalog makanan dan minuman restoran.</p><a class="btn btn-primary" href="index.php?page=menu&action=create">＋ Tambah Menu</a></div>
<?php if ($action === 'create' || $action === 'edit'): ?>
<section class="panel form-panel"><div class="panel-title"><div><span class="eyebrow">KATALOG RESTORAN</span><h2><?= $action === 'edit' ? 'Edit Menu' : 'Menu Baru' ?></h2></div><a class="btn btn-light" href="index.php?page=menu">Kembali</a></div>
<?php if (!$categoryOptions): ?><div class="alert warning">Buat kategori terlebih dahulu sebelum menambahkan menu.</div><?php endif; ?>
<form method="post" action="index.php?page=menu&action=save"><input type="hidden" name="id_menu" value="<?= e($editData['id_menu'] ?? '') ?>">
<div class="form-grid">
<div class="form-group"><label>Nama menu</label><input name="nama_menu" required maxlength="150" value="<?= e($editData['nama_menu'] ?? '') ?>" placeholder="Contoh: Nasi Goreng Spesial"></div>
<div class="form-group"><label>Kategori</label><select name="id_kategori" required><option value="">Pilih kategori</option><?php foreach ($categoryOptions as $cat): ?><option value="<?= e($cat['id_kategori']) ?>" <?= (string)($editData['id_kategori'] ?? '') === (string)$cat['id_kategori'] ? 'selected' : '' ?>><?= e($cat['nama_kategori']) ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Jenis menu</label><select name="jenis_menu" required><option value="Makanan" <?= ($editData['jenis_menu'] ?? '') === 'Makanan' ? 'selected' : '' ?>>Makanan</option><option value="Minuman" <?= ($editData['jenis_menu'] ?? '') === 'Minuman' ? 'selected' : '' ?>>Minuman</option></select></div>
<div class="form-group"><label>Harga (Rp)</label><input type="number" name="harga" min="0" step="100" required value="<?= e($editData['harga'] ?? '') ?>" placeholder="25000"></div>
<div class="form-group"><label>Stok</label><input type="number" name="stok" min="0" required value="<?= e($editData['stok'] ?? '0') ?>"></div>
</div>
<div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" rows="3"><?= e($editData['deskripsi'] ?? '') ?></textarea></div>
<button class="btn btn-primary" type="submit" <?= !$categoryOptions ? 'disabled' : '' ?>>Simpan Menu</button></form></section>
<?php else: ?>
<section class="panel"><div class="panel-title"><div><span class="eyebrow">DAFTAR PRODUK</span><h2>Menu Restoran</h2></div><span class="count-pill"><?= count($menuList) ?> menu</span></div>
<div class="table-wrap"><table><thead><tr><th>ID</th><th>Nama Menu</th><th>Kategori</th><th>Jenis</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead><tbody>
<?php foreach ($menuList as $item): ?><tr><td class="id-cell">#<?= e($item['id_menu']) ?></td><td><strong><?= e($item['nama_menu']) ?></strong><small class="subtext"><?= e($item['deskripsi']) ?></small></td><td><?= e($item['nama_kategori']) ?></td><td><span class="tag <?= $item['jenis_menu'] === 'Makanan' ? 'food' : 'drink' ?>"><?= e($item['jenis_menu']) ?></span></td><td class="price">Rp <?= number_format((float)$item['harga'], 0, ',', '.') ?></td><td><?= e($item['stok']) ?></td><td class="actions"><a class="action-edit" href="index.php?page=menu&action=edit&id=<?= e($item['id_menu']) ?>">Edit</a><form method="post" action="index.php?page=menu&action=delete" onsubmit="return confirm('Hapus menu ini? Menu yang sudah memiliki pesanan tidak dapat dihapus.');"><input type="hidden" name="id" value="<?= e($item['id_menu']) ?>"><button class="action-delete" type="submit">Hapus</button></form></td></tr><?php endforeach; ?>
<?php if (!$menuList): ?><tr><td colspan="7" class="empty">Belum ada menu. Tambahkan menu pertama.</td></tr><?php endif; ?>
</tbody></table></div></section>
<?php endif; ?>
