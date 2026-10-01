<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'LoveX' ?> - LoveX</title>

    <!-- ✅ PWA META TAGS -->
    <meta name="theme-color" content="#FF4D79">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="LoveX">
    <meta name="description" content="Ứng dụng lưu giữ kỷ niệm tình yêu cho các cặp đôi">
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('icons/icon-192.png') ?>">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= base_url('css/onboarding.css') ?>?v=<?= time() ?>">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="<?= (strpos($_SERVER['REQUEST_URI'], 'auth') !== false ||
                    strpos($_SERVER['REQUEST_URI'], 'login') !== false ||
                    strpos($_SERVER['REQUEST_URI'], 'register') !== false ||
                    strpos($_SERVER['REQUEST_URI'], 'logout') !== false) ? 'auth-page' : '' ?>">

    <script>
        // Áp dụng theme trước khi render để tránh flash
        (function() {
            const theme = localStorage.getItem('lovex_theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    <!-- ============ SIDEBAR OVERLAY ============ -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- ============ SIDEBAR ============ -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-avatar">
                <?php
                if (is_logged_in()) {
                    require_once __DIR__ . '/../../models/User.php';
                    require_once __DIR__ . '/../../../config/database.php';
                    $sidebarUser = (new User((new Database())->getConnection()))->findById(current_user_id());
                    $initial = strtoupper(mb_substr($_SESSION['display_name'] ?? 'U', 0, 1));
                }
                ?>
                <?php if (!empty($sidebarUser['avatar_url'])): ?>
                    <img src="<?= base_url($sidebarUser['avatar_url']) ?>" alt="">
                <?php else: ?>
                    <span><?= $initial ?? 'U' ?></span>
                <?php endif; ?>
            </div>
            <h5 class="sidebar-name"><?= htmlspecialchars($_SESSION['display_name'] ?? 'User') ?></h5>
            <!-- <small class="sidebar-email"><?= htmlspecialchars($sidebarUser['email'] ?? '') ?></small> -->
            <small class="sidebar-email">
                @<?= htmlspecialchars($sidebarUser['username'] ?? 'user') ?>
                <?php if (!empty($sidebarUser['email'])): ?>
                    <br><span style="font-size: 11px; opacity: 0.7;"><?= htmlspecialchars($sidebarUser['email']) ?></span>
                <?php endif; ?>
            </small>
        </div>

        <nav class="sidebar-nav">
            <a href="<?= base_url('index.php?action=home') ?>" class="sidebar-link">
                <i class="bi bi-house-door-fill"></i>
                <span>Trang chủ</span>
            </a>
            <a href="<?= base_url('index.php?action=memories') ?>" class="sidebar-link">
                <i class="bi bi-book-fill"></i>
                <span>Kỷ niệm</span>
            </a>
            <a href="<?= base_url('index.php?action=timeline') ?>" class="sidebar-link">
                <i class="bi bi-clock-history"></i>
                <span>Timeline</span>
            </a>
            <a href="<?= base_url('index.php?action=story') ?>" class="sidebar-link">
                <i class="bi bi-lightning-charge-fill"></i>
                <span>Story</span>
            </a>
            <a href="<?= base_url('index.php?action=letters') ?>" class="sidebar-link">
                <i class="bi bi-envelope-heart-fill"></i>
                <span>Thư tình</span>
            </a>
            <a href="<?= base_url('index.php?action=emotions') ?>" class="sidebar-link">
                <i class="bi bi-bar-chart-fill"></i>
                <span>Cảm xúc</span>
            </a>
            <a href="<?= base_url('index.php?action=settings') ?>" class="sidebar-link">
                <i class="bi bi-gear-fill"></i>
                <span>Cài đặt</span>
            </a>

            <hr class="sidebar-divider">

            <a href="#" class="sidebar-link" onclick="toggleTheme(event)">
                <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                <span id="themeText">Chế độ tối</span>
            </a>
            <a href="#" class="sidebar-link" onclick="showAbout(event)">
                <i class="bi bi-info-circle-fill"></i>
                <span>Về LoveX</span>
            </a>
            <a href="<?= base_url('index.php?action=logout') ?>" class="sidebar-link text-danger">
                <i class="bi bi-box-arrow-right"></i>
                <span>Đăng xuất</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <small>Made with <i class="bi bi-heart-fill text-pink"></i> LoveX v1.0</small>
        </div>
    </aside>