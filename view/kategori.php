<div class="page-heading"><div><p class="muted">Atur pengelompokan makanan dan minuman.</p></div><a class="btn btn-primary" href="index.php?page=kategori&action=create">＋ Tambah Kategori</a></div>
<?php if ($action === 'create' || $action === 'edit'): ?>
<section class="panel form-panel"><div class="panel-title"><div><span class="eyebrow">FORM KATEGORI</span><h2><?= $action === 'edit' ? 'Edit Kategori' : 'Kategori Baru' ?></h2></div><a class="btn btn-light" href="index.php?page=kategori">Kembali</a></div>
<form method="post" action="index.php?page=kategori&action=save">
    <input type="hidden" name="id_kategori" value="<?= e($editData['id_kategori'] ?? '') ?>">
    <div class="form-group"><label>Nama kategori</label><input type="text" name="nama_kategori" required maxlength="100" value="<?= e($editData['nama_kategori'] ?? '') ?>" placeholder="Contoh: Makanan Utama"></div>
    <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" rows="4" placeholder="Keterangan kategori..."><?= e($editData['deskripsi'] ?? '') ?></textarea></div>
    <button class="btn btn-primary" type="submit">Simpan Kategori</button>
</form></section>
<?php else: ?>
<section class="panel"><div class="panel-title"><div><span class="eyebrow">DATA MASTER</span><h2>Daftar Kategori</h2></div><span class="count-pill"><?= count($kategoriList) ?> kategori</span></div>
<div class="table-wrap"><table><thead><tr><th>ID</th><th>Nama Kategori</th><th>Deskripsi</th><th>Jumlah Menu</th><th>Aksi</th></tr></thead><tbody>
<?php foreach ($kategoriList as $item): ?><tr><td class="id-cell">#<?= e($item['id_kategori']) ?></td><td><strong><?= e($item['nama_kategori']) ?></strong></td><td><?= e($item['deskripsi'] ?: '-') ?></td><td><span class="count-pill"><?= e($item['jumlah_menu']) ?> menu</span></td><td class="actions"><a class="action-edit" href="index.php?page=kategori&action=edit&id=<?= e($item['id_kategori']) ?>">Edit</a><form method="post" action="index.php?page=kategori&action=delete" onsubmit="return confirm('Hapus kategori ini? Kategori yang masih memiliki menu tidak dapat dihapus.');"><input type="hidden" name="id" value="<?= e($item['id_kategori']) ?>"><button class="action-delete" type="submit">Hapus</button></form></td></tr><?php endforeach; ?>
<?php if (!$kategoriList): ?><tr><td colspan="5" class="empty">Belum ada kategori. Tambahkan kategori pertama.</td></tr><?php endif; ?>
</tbody></table></div></section>
<?php endif; ?>
