<?php
$pageTitle = $pageTitle ?? 'Restora';
$activePage = $activePage ?? 'dashboard';
function e($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | Restora</title>
    <link rel="stylesheet" href="view/style.css">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <a class="brand" href="index.php"><span class="brand-mark">R</span><span>restora<small>RESTAURANT MANAGER</small></span></a>
        <div class="nav-label">MENU UTAMA</div>
        <nav class="side-nav">
            <a class="<?= $activePage === 'dashboard' ? 'active' : '' ?>" href="index.php"><span>▦</span> Dashboard</a>
            <a class="<?= $activePage === 'kategori' ? 'active' : '' ?>" href="index.php?page=kategori"><span>◫</span> Kategori</a>
            <a class="<?= $activePage === 'menu' ? 'active' : '' ?>" href="index.php?page=menu"><span>☷</span> Menu</a>
            <a class="<?= $activePage === 'pesanan' ? 'active' : '' ?>" href="index.php?page=pesanan"><span>▤</span> Pesanan</a>
        </nav>
        <div class="sidebar-bottom"><div class="avatar">A</div><div><strong>Administrator</strong><small>Pengelola Restoran</small></div></div>
    </aside>
    <main class="main-area">
        <header class="topbar"><div><span class="eyebrow">RESTORAN / <?= strtoupper(e($activePage)) ?></span><h1><?= e($pageTitle) ?></h1></div><div class="topbar-right"><span class="live-dot"></span> Sistem aktif <span class="date-label"><?= date('d M Y') ?></span></div></header>
        <section class="content">
            <?php if (!empty($flash)): ?>
                <div class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
            <?php endif; ?>
