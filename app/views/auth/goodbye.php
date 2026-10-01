<?php
// KHÔNG require header.php nữa!
// Trang này hoàn toàn độc lập, không dùng layout
$css_version = @filemtime(__DIR__ . '/../../../public/css/auth.css') ?: time();
$js_version  = @filemtime(__DIR__ . '/../../../public/js/auth.js') ?: time();
$base = dirname($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hẹn gặp lại - LoveX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base ?>/css/auth.css?v=<?= $css_version ?>">
    <style>
        /* Force fullscreen, ẩn mọi thứ khác */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="logout-body">

    <div class="logout-page">
        <div class="logout-aurora"></div>
        <div class="logout-particles" id="logoutParticles"></div>

        <div class="heart-stage">
            <div class="shockwave"></div>
            <div class="shockwave shockwave-2"></div>

            <div class="heart-svg-wrapper">
                <svg class="heart-piece heart-left" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="heartGradLeft" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#FF4D79" />
                            <stop offset="50%" stop-color="#FF6B9D" />
                            <stop offset="100%" stop-color="#FF80AB" />
                        </linearGradient>
                    </defs>
                    <path d="M 100 30 
                         C 90 10, 60 0, 40 10
                         C 10 25, 0 60, 20 90
                         L 100 175
                         L 100 100
                         L 95 80
                         L 100 60
                         L 98 40 Z"
                        fill="url(#heartGradLeft)" />
                </svg>

                <svg class="heart-piece heart-right" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="heartGradRight" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#FF80AB" />
                            <stop offset="50%" stop-color="#FF6B9D" />
                            <stop offset="100%" stop-color="#FF4D79" />
                        </linearGradient>
                    </defs>
                    <path d="M 100 30 
                         C 110 10, 140 0, 160 10
                         C 190 25, 200 60, 180 90
                         L 100 175
                         L 100 100
                         L 105 80
                         L 100 60
                         L 102 40 Z"
                        fill="url(#heartGradRight)" />
                </svg>

                <div class="crack-line"></div>
            </div>
        </div>

        <div class="goodbye-content">
            <h1 class="goodbye-title" id="goodbyeTitle">Hẹn gặp lại</h1>
            <div class="goodbye-heart-emoji">💔</div>
            <p class="goodbye-subtext" id="goodbyeSubtext">Trái tim này sẽ đợi bạn quay về</p>
        </div>

        <a href="<?= $base ?>/index.php?action=login" class="btn-back-login" id="btnBackLogin">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                <polyline points="10 17 15 12 10 7" />
                <line x1="15" y1="12" x2="3" y2="12" />
            </svg>
            Đăng nhập lại ngay
        </a>

        <div class="countdown-redirect" id="countdownBox">
            Tự động chuyển sau <strong id="countdownNumber">8</strong>s
        </div>
    </div>

    <script>
        const BASE_URL = '<?= $base ?>/';
    </script>
    <script src="<?= $base ?>/js/auth.js?v=<?= $js_version ?>"></script>
    <!-- ============ HELP BUTTON (Logout) ============ -->
    <!-- <button class="help-fab help-fab-auth" id="helpFab" onclick="toggleLogoutHelp()" title="Trợ giúp">
        <i class="bi bi-question-lg"></i>
    </button> -->

    <div class="help-menu help-menu-auth" id="helpMenu">
        <div class="help-menu-header">
            <i class="bi bi-heart-fill"></i>
            <strong>Bạn cần giúp?</strong>
        </div>
        <a href="#" class="help-menu-item" onclick="showLogoutInfo(); return false;">
            <i class="bi bi-info-circle"></i>
            <span>Tại sao tim bị vỡ?</span>
        </a>
        <a href="#" class="help-menu-item" onclick="showLoginAgainHelp(); return false;">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>Đăng nhập lại thế nào?</span>
        </a>
        <a href="#" class="help-menu-item" onclick="showContactHelp(); return false;">
            <i class="bi bi-chat-dots"></i>
            <span>Liên hệ hỗ trợ</span>
        </a>
    </div>

    <script>
        function toggleLogoutHelp() {
            document.getElementById('helpMenu')?.classList.toggle('active');
        }

        document.addEventListener('click', function(e) {
            const menu = document.getElementById('helpMenu');
            const fab = document.getElementById('helpFab');
            if (menu && fab && !menu.contains(e.target) && !fab.contains(e.target)) {
                menu.classList.remove('active');
            }
        });

        function showLogoutInfo() {
            document.getElementById('helpMenu')?.classList.remove('active');
            Swal.fire({
                title: '💔 Về trái tim vỡ',
                html: `
            <div style="text-align: left; font-size: 14px; line-height: 1.8;">
                <p>Khi bạn đăng xuất, trái tim sẽ tạm "vỡ đôi" — đây chỉ là hiệu ứng hình ảnh để thể hiện sự chia xa tạm thời.</p>
                <p style="margin-top: 12px; padding: 12px; background: #FFF5F8; border-radius: 12px; color: #FF4D79;">
                    💕 Đừng lo! Khi bạn đăng nhập lại, trái tim sẽ được ghép lại nguyên vẹn!
                </p>
            </div>
        `,
                confirmButtonColor: '#FF4D79'
            });
        }

        function showLoginAgainHelp() {
            document.getElementById('helpMenu')?.classList.remove('active');
            Swal.fire({
                title: '🔐 Đăng nhập lại',
                html: `
            <div style="text-align: left; font-size: 14px; line-height: 1.8;">
                <p><strong>Cách 1:</strong> Bấm nút <strong>"Đăng nhập lại ngay"</strong> trên màn hình.</p>
                <p><strong>Cách 2:</strong> Đợi 8 giây, hệ thống sẽ tự động chuyển về trang đăng nhập.</p>
                <p style="margin-top: 12px; font-size: 13px; color: #999;">
                    💡 Trang này sẽ tự đóng sau vài giây.
                </p>
            </div>
        `,
                confirmButtonColor: '#FF4D79'
            });
        }

        function showContactHelp() {
            document.getElementById('helpMenu')?.classList.remove('active');
            Swal.fire({
                title: '💬 Liên hệ hỗ trợ',
                html: `<p>📧 support@lovex.vn</p><p>💬 fb.com/lovex.vn</p>`,
                confirmButtonColor: '#FF4D79'
            });
        }
    </script>
</body>

</html>