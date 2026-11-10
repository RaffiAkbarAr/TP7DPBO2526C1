<?php
session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/class/Database.php';
require_once __DIR__ . '/class/Kategori.php';
require_once __DIR__ . '/class/Menu.php';
require_once __DIR__ . '/class/Pesanan.php';

try {
    $database = new Database();
    $pdo = $database->getConnection();
    $kategori = new Kategori($pdo);
    $menu = new Menu($pdo);
    $pesanan = new Pesanan($pdo);
} catch (PDOException $e) {
    http_response_code(500);
    die('<h2>Koneksi database gagal</h2><p>Pastikan MySQL aktif dan database db_restoran sudah diimpor.</p><small>' .
        htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</small>');
}

function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}
function redirectTo($url) {
    header('Location: ' . $url);
    exit;
}

$page = $_GET['page'] ?? 'dashboard';
$allowedPages = ['dashboard', 'kategori', 'menu', 'pesanan'];
if (!in_array($page, $allowedPages, true)) $page = 'dashboard';
$action = $_GET['action'] ?? 'list';
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($page === 'kategori') {
            if ($action === 'save') {
                $categoryId = filter_input(INPUT_POST, 'id_kategori', FILTER_VALIDATE_INT);
                $data = ['nama_kategori' => $_POST['nama_kategori'] ?? '', 'deskripsi' => $_POST['deskripsi'] ?? ''];
                if (trim($data['nama_kategori']) === '') throw new InvalidArgumentException('Nama kategori wajib diisi.');
                if ($categoryId) $kategori->update($categoryId, $data);
                else $kategori->create($data);
                setFlash('success', $categoryId ? 'Kategori berhasil diperbarui.' : 'Kategori berhasil ditambahkan.');
            } elseif ($action === 'delete' && $id) {
                $kategori->delete($id);
                setFlash('success', 'Kategori berhasil dihapus.');
            }
        } elseif ($page === 'menu') {
            if ($action === 'save') {
                $menuId = filter_input(INPUT_POST, 'id_menu', FILTER_VALIDATE_INT);
                $data = [
                    'id_kategori' => $_POST['id_kategori'] ?? '',
                    'nama_menu' => $_POST['nama_menu'] ?? '',
                    'jenis_menu' => $_POST['jenis_menu'] ?? '',
                    'harga' => $_POST['harga'] ?? 0,
                    'stok' => $_POST['stok'] ?? 0,
                    'deskripsi' => $_POST['deskripsi'] ?? ''
                ];
                if (trim($data['nama_menu']) === '' || !$data['id_kategori'] || !in_array($data['jenis_menu'], ['Makanan','Minuman'], true) || !is_numeric($data['harga']) || $data['harga'] < 0 || filter_var($data['stok'], FILTER_VALIDATE_INT) === false || $data['stok'] < 0) {
                    throw new InvalidArgumentException('Periksa kembali data menu. Semua kolom wajib diisi dengan benar.');
                }
                if ($menuId) $menu->update($menuId, $data);
                else $menu->create($data);
                setFlash('success', $menuId ? 'Menu berhasil diperbarui.' : 'Menu berhasil ditambahkan.');
            } elseif ($action === 'delete' && $id) {
                $menu->delete($id);
                setFlash('success', 'Menu berhasil dihapus.');
            }
        } elseif ($page === 'pesanan') {
            if ($action === 'save') {
                $orderId = filter_input(INPUT_POST, 'id_pesanan', FILTER_VALIDATE_INT);
                $data = [
                    'id_menu' => $_POST['id_menu'] ?? '',
                    'nama_pelanggan' => $_POST['nama_pelanggan'] ?? '',
                    'jumlah' => $_POST['jumlah'] ?? 0,
                    'tanggal_pesanan' => $_POST['tanggal_pesanan'] ?? '',
                    'status_pesanan' => $_POST['status_pesanan'] ?? 'Menunggu'
                ];
                $validStatuses = ['Menunggu', 'Diproses', 'Selesai', 'Dibatalkan'];
                if (!$data['id_menu'] || trim($data['nama_pelanggan']) === '' || filter_var($data['jumlah'], FILTER_VALIDATE_INT) === false || $data['jumlah'] < 1 || !in_array($data['status_pesanan'], $validStatuses, true) || !$data['tanggal_pesanan']) {
                    throw new InvalidArgumentException('Periksa kembali data pesanan.');
                }
                $data['tanggal_pesanan'] = str_replace('T', ' ', $data['tanggal_pesanan']) . (strlen($data['tanggal_pesanan']) === 16 ? ':00' : '');
                if ($orderId) $pesanan->update($orderId, $data);
                else $pesanan->create($data);
                setFlash('success', $orderId ? 'Pesanan berhasil diperbarui.' : 'Pesanan berhasil ditambahkan.');
            } elseif ($action === 'delete' && $id) {
                $pesanan->delete($id);
                setFlash('success', 'Pesanan berhasil dihapus.');
            }
        }
        redirectTo('index.php?page=' . urlencode($page));
    }

    $editData = null;
    if ($action === 'edit') {
        $editId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$editId) throw new InvalidArgumentException('ID data tidak valid.');
        if ($page === 'kategori') $editData = $kategori->getById($editId);
        if ($page === 'menu') $editData = $menu->getById($editId);
        if ($page === 'pesanan') $editData = $pesanan->getById($editId);
        if (!$editData) throw new RuntimeException('Data yang akan diedit tidak ditemukan.');
    }

    if ($page === 'dashboard') {
        $categoryCount = count($kategori->getAll());
        $menuList = $menu->getAll();
        $pesananList = $pesanan->getAll();
        $menuCount = count($menuList);
        $orderCount = count($pesananList);
        $revenue = 0;
        foreach ($pesananList as $order) {
            if ($order['status_pesanan'] !== 'Dibatalkan') $revenue += (float)$order['total_harga'];
        }
        $recentOrders = array_slice($pesananList, 0, 5);
        $pageTitle = 'Dashboard';
        $activePage = 'dashboard';
        require __DIR__ . '/view/header.php';
        ?>
        <section class="dashboard-welcome"><div><span class="eyebrow">SELAMAT DATANG KEMBALI</span><h2>Kelola restoran lebih mudah.</h2><p>Pantau menu, kategori, dan pesanan dalam satu tempat.</p></div><div class="welcome-icon">✦</div></section>
        <div class="stats-grid">
            <article class="stat-card"><span class="stat-label">Total Kategori</span><strong><?= $categoryCount ?></strong><small>Kategori terdaftar</small></article>
            <article class="stat-card"><span class="stat-label">Total Menu</span><strong><?= $menuCount ?></strong><small>Produk makanan & minuman</small></article>
            <article class="stat-card"><span class="stat-label">Total Pesanan</span><strong><?= $orderCount ?></strong><small>Transaksi tercatat</small></article>
            <article class="stat-card"><span class="stat-label">Pendapatan Pesanan</span><strong style="font-size:21px">Rp <?= number_format($revenue,0,',','.') ?></strong><small>Tidak termasuk pesanan dibatalkan</small></article>
        </div>
        <div class="dashboard-grid">
        <section class="panel"><div class="panel-title"><div><span class="eyebrow">AKSES CEPAT</span><h2>Kelola Data</h2></div></div><div class="quick-links"><a class="quick-link" href="index.php?page=kategori"><span>◫</span> Kelola Kategori</a><a class="quick-link" href="index.php?page=menu"><span>☷</span> Kelola Menu</a><a class="quick-link" href="index.php?page=pesanan&action=create"><span>＋</span> Buat Pesanan</a><a class="quick-link" href="index.php?page=pesanan"><span>▤</span> Lihat Pesanan</a></div></section>
        <section class="panel"><div class="panel-title"><div><span class="eyebrow">AKTIVITAS</span><h2>Pesanan Terbaru</h2></div><a class="action-edit" href="index.php?page=pesanan">Lihat semua</a></div><div class="recent-list">
        <?php foreach ($recentOrders as $order): ?><div class="recent-item"><div><strong><?= htmlspecialchars($order['nama_pelanggan'], ENT_QUOTES, 'UTF-8') ?></strong><small><?= htmlspecialchars($order['nama_menu'], ENT_QUOTES, 'UTF-8') ?> · <?= (int)$order['jumlah'] ?> item</small></div><div style="text-align:right"><strong>Rp <?= number_format((float)$order['total_harga'],0,',','.') ?></strong><small><?= htmlspecialchars($order['status_pesanan'], ENT_QUOTES, 'UTF-8') ?></small></div></div><?php endforeach; ?>
        <?php if (!$recentOrders): ?><p class="muted">Belum ada aktivitas pesanan.</p><?php endif; ?>
        </div></section></div>
        <?php
        require __DIR__ . '/view/footer.php';
        exit;
    }

    $pageTitle = ucfirst($page);
    $activePage = $page;
    if ($page === 'kategori') {
        $kategoriList = $kategori->getAll();
        require __DIR__ . '/view/header.php';
        require __DIR__ . '/view/kategori.php';
    } elseif ($page === 'menu') {
        $menuList = $menu->getAll();
        $categoryOptions = $menu->getCategories();
        require __DIR__ . '/view/header.php';
        require __DIR__ . '/view/menu.php';
    } elseif ($page === 'pesanan') {
        $pesananList = $pesanan->getAll();
        $menuOptions = $pesanan->getMenus();
        require __DIR__ . '/view/header.php';
        require __DIR__ . '/view/pesanan.php';
    }
    require __DIR__ . '/view/footer.php';
} catch (PDOException $e) {
    $message = $e->getCode() === '23000'
        ? 'Data tidak dapat dihapus karena masih digunakan oleh data lain. Hapus data terkait terlebih dahulu.'
        : 'Terjadi kesalahan saat mengakses database.';
    setFlash('error', $message);
    redirectTo('index.php?page=' . urlencode($page));
} catch (Throwable $e) {
    setFlash('error', $e->getMessage());
    redirectTo('index.php?page=' . urlencode($page));
}
