<?php
$pageTitle = 'Đăng nhập';
$hideNav = true;
$hideHeader = true;
require_once __DIR__ . '/../layouts/header.php';
?>

<link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">

<div class="auth-hearts-bg"></div>

<div class="auth-card-love">
    <div class="auth-heart-logo">
        <div class="heart-shape"></div>
    </div>

    <div class="auth-welcome">
        <h2>Chào mừng trở lại! 💕</h2>
        <p>Đăng nhập để tiếp tục với người ấy</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="auth-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" id="loginForm" onsubmit="handleLogin(event)">
        <?= csrf_field() ?>

        <div class="auth-input-group">
            <input type="text" name="username" class="auth-input"
                placeholder="Biệt danh của bạn" required autofocus
                autocapitalize="none" autocomplete="username">
            <i class="bi bi-person-badge-fill"></i>
        </div>

        <div class="auth-input-group">
            <input type="password" name="password" class="auth-input"
                placeholder="Mật khẩu" required>
            <i class="bi bi-lock-fill"></i>
        </div>

        <button type="submit" class="auth-btn">
            <i class="bi bi-box-arrow-in-right"></i>
            Đăng nhập
        </button>
    </form>

    <p class="auth-footer">
        Chưa có tài khoản?
        <a href="<?= base_url('index.php?action=register') ?>">Đăng ký ngay</a>
    </p>
</div>

<!-- LOGIN SUCCESS OVERLAY - MODERN ANIMATION -->
<div class="login-success-overlay" id="loginSuccessOverlay">
    <!-- Aurora background -->
    <div class="ls-aurora"></div>

    <!-- Particles nền -->
    <div class="ls-particles" id="lsParticles"></div>

    <!-- Trái tim ghép lại -->
    <div class="reunite-stage">
        <!-- Vòng sáng lan ra khi ghép -->
        <div class="reunite-shockwave"></div>
        <div class="reunite-shockwave reunite-shockwave-2"></div>

        <!-- 2 mảnh tim -->
        <div class="reunite-heart-wrapper">
            <svg class="reunite-half reunite-left" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="rGradL" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#FF4D79" />
                        <stop offset="100%" stop-color="#FF6B9D" />
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
                    fill="url(#rGradL)" />
            </svg>

            <svg class="reunite-half reunite-right" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="rGradR" x1="100%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#FF6B9D" />
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
                    fill="url(#rGradR)" />
            </svg>
        </div>
    </div>

    <!-- Text và progress -->
    <div class="ls-content">
        <h2 class="ls-title" id="lsTitle">Đang kết nối trái tim</h2>
        <p class="ls-subtitle" id="lsSubtitle">Chờ chút nhé, tình yêu đang đến...</p>

        <div class="ls-progress">
            <div class="ls-progress-bar" id="lsProgressBar"></div>
        </div>
        <div class="ls-progress-text" id="lsProgressText">0%</div>
    </div>
</div>

<script>
    function handleLogin(e) {
        e.preventDefault();
        const form = e.target;
        const overlay = document.getElementById('loginSuccessOverlay');

        if (!overlay) {
            form.submit();
            return;
        }

        // Hiện overlay + animation
        overlay.classList.add('active');

        // Init particles
        if (typeof initLoginParticles === 'function') {
            initLoginParticles();
        }

        // Chạy animation
        setTimeout(() => {
            if (typeof createLoginSparkles === 'function') createLoginSparkles();
        }, 900);

        setTimeout(() => {
            if (typeof animateProgress === 'function') animateProgress();
        }, 1300);

        // ✅ Submit form sau 2.8s (đủ để progress chạy 100%)
        setTimeout(() => {
            form.submit();
        }, 2800);
    }

    // ✅ Chạy khi trang load để init particles sẵn
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof initLoginParticles === 'function') {
            initLoginParticles();
        }
    });
</script>

<script src="<?= base_url('js/auth.js') ?>"></script>
<!-- ============ HELP BUTTON (Login) ============ -->
<!-- <button class="help-fab help-fab-auth" id="helpFab" onclick="toggleAuthHelp()" title="Trợ giúp">
    <i class="bi bi-question-lg"></i>
</button> -->

<!-- ============ HELP MENU (Login) ============ -->
<div class="help-menu help-menu-auth" id="helpMenu">
    <div class="help-menu-header">
        <i class="bi bi-heart-fill"></i>
        <strong>Trợ giúp LoveX</strong>
    </div>
    <a href="#" class="help-menu-item" onclick="showLoginHelp(); return false;">
        <i class="bi bi-play-circle"></i>
        <span>Hướng dẫn đăng nhập</span>
    </a>
    <a href="#" class="help-menu-item" onclick="showRegisterHelp(); return false;">
        <i class="bi bi-person-plus"></i>
        <span>Chưa có tài khoản?</span>
    </a>
    <a href="#" class="help-menu-item" onclick="showForgotPasswordHelp(); return false;">
        <i class="bi bi-key"></i>
        <span>Quên mật khẩu</span>
    </a>
    <a href="#" class="help-menu-item" onclick="showContactHelp(); return false;">
        <i class="bi bi-chat-dots"></i>
        <span>Liên hệ hỗ trợ</span>
    </a>
</div>

<style>
    /* Help FAB cho trang auth */
    .help-fab-auth {
        position: fixed !important;
        bottom: 24px !important;
        right: 24px !important;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF4D79 0%, #FF80AB 100%);
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 8px 24px rgba(255, 77, 121, 0.5);
        transition: all 0.3s;
        z-index: 9998;
        animation: fabPulseAuth 2s ease-in-out infinite;
    }

    .help-fab-auth:hover {
        transform: scale(1.1) rotate(15deg);
        box-shadow: 0 12px 32px rgba(255, 77, 121, 0.7);
    }

    @keyframes fabPulseAuth {

        0%,
        100% {
            box-shadow: 0 8px 24px rgba(255, 77, 121, 0.5);
        }

        50% {
            box-shadow: 0 8px 24px rgba(255, 77, 121, 0.5),
                0 0 0 12px rgba(255, 77, 121, 0.15);
        }
    }

    /* Help Menu cho trang auth */
    .help-menu-auth {
        position: fixed !important;
        bottom: 96px !important;
        right: 24px !important;
        background: white;
        border-radius: 20px;
        padding: 8px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px) scale(0.95);
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        min-width: 240px;
        max-width: 90vw;
    }

    .help-menu-auth.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    .help-menu-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px 8px;
        color: #FF4D79;
        font-size: 14px;
        border-bottom: 1px solid #F0F0F0;
        margin-bottom: 4px;
    }

    .help-menu-header i {
        font-size: 16px;
    }

    .help-menu-auth .help-menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        color: #333;
        font-size: 14px;
        font-weight: 500;
    }

    .help-menu-auth .help-menu-item:hover {
        background: #FFF5F8;
        color: #FF4D79;
        transform: translateX(4px);
    }

    .help-menu-auth .help-menu-item i {
        font-size: 18px;
        color: #FF4D79;
        width: 24px;
        text-align: center;
    }

    /* Responsive mobile */
    @media (max-width: 480px) {
        .help-fab-auth {
            bottom: 100px !important;
            /* Tránh bottom nav nếu có */
            right: 16px !important;
            width: 50px;
            height: 50px;
            font-size: 22px;
        }

        .help-menu-auth {
            bottom: 164px !important;
            right: 16px !important;
            left: 16px !important;
            min-width: auto;
        }
    }
</style>

<script>
    // ============ HELP MENU CHO TRANG LOGIN ============
    function toggleAuthHelp() {
        document.getElementById('helpMenu')?.classList.toggle('active');
    }

    // Đóng menu khi click ra ngoài
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('helpMenu');
        const fab = document.getElementById('helpFab');

        if (menu && fab && !menu.contains(e.target) && !fab.contains(e.target)) {
            menu.classList.remove('active');
        }
    });

    // ============ CÁC HÀM HIỂN THỊ HELP ============
    function showLoginHelp() {
        document.getElementById('helpMenu')?.classList.remove('active');
        Swal.fire({
            title: '🔐 Hướng dẫn đăng nhập',
            html: `
            <div style="text-align: left; font-size: 14px; line-height: 1.8;">
                <p><strong>Bước 1:</strong> Nhập <strong>biệt danh</strong> bạn đã đăng ký (VD: <code>admin</code>).</p>
                <p><strong>Bước 2:</strong> Nhập <strong>mật khẩu</strong> (tối thiểu 6 ký tự).</p>
                <p><strong>Bước 3:</strong> Bấm nút <strong>Đăng nhập</strong>.</p>
                <p style="margin-top: 12px; padding: 12px; background: #FFF5F8; border-radius: 12px; color: #FF4D79;">
                    💡 <strong>Mẹo:</strong> Biệt danh là tên ngắn gọn không dấu (VD: <code>nam123</code>, <code>linhxinh</code>) — dễ nhớ hơn email!
                </p>
            </div>
        `,
            confirmButtonColor: '#FF4D79',
            confirmButtonText: 'Đã hiểu',
            width: 450
        });
    }

    function showRegisterHelp() {
        document.getElementById('helpMenu')?.classList.remove('active');
        Swal.fire({
            title: '✨ Đăng ký tài khoản mới',
            html: `
            <div style="text-align: left; font-size: 14px; line-height: 1.8;">
                <p>Bấm vào link <strong>"Đăng ký ngay"</strong> ở cuối form đăng nhập.</p>
                <p>Sau đó điền:</p>
                <ul style="padding-left: 20px;">
                    <li><strong>Tên hiển thị:</strong> Tên thật của bạn (VD: Nguyễn Văn A)</li>
                    <li><strong>Biệt danh:</strong> Tên ngắn để đăng nhập (chữ thường, không dấu)</li>
                    <li><strong>Mật khẩu:</strong> Tối thiểu 6 ký tự</li>
                    <li><strong>Email:</strong> Không bắt buộc</li>
                </ul>
                <p style="margin-top: 12px; padding: 12px; background: #FFF5F8; border-radius: 12px; color: #FF4D79;">
                    💕 Sau khi đăng ký, bạn cần ghép đôi với người ấy bằng mã code!
                </p>
            </div>
        `,
            confirmButtonColor: '#FF4D79',
            confirmButtonText: 'Đăng ký ngay',
            showCancelButton: true,
            cancelButtonText: 'Để sau',
            cancelButtonColor: '#999'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = BASE_URL + 'index.php?action=register';
            }
        });
    }

    function showForgotPasswordHelp() {
        document.getElementById('helpMenu')?.classList.remove('active');
        Swal.fire({
            title: '🔑 Quên mật khẩu',
            html: `
            <div style="text-align: left; font-size: 14px; line-height: 1.8;">
                <p>Hiện tại LoveX chưa hỗ trợ reset mật khẩu tự động.</p>
                <p>Vui lòng liên hệ hỗ trợ qua:</p>
                <p style="margin-top: 12px;">
                    📧 <strong>support@lovex.vn</strong><br>
                    💬 <strong>fb.com/lovex.vn</strong>
                </p>
                <p style="margin-top: 12px; font-size: 13px; color: #999;">
                    Chúng tôi sẽ giúp bạn khôi phục tài khoản trong 24h.
                </p>
            </div>
        `,
            confirmButtonColor: '#FF4D79',
            confirmButtonText: 'Đã hiểu'
        });
    }

    function showContactHelp() {
        document.getElementById('helpMenu')?.classList.remove('active');
        Swal.fire({
            title: '💬 Liên hệ hỗ trợ',
            html: `
            <p>Chúng tôi luôn sẵn sàng giúp đỡ bạn!</p>
            <p style="margin-top: 16px; font-size: 15px;">
                📧 <strong>support@lovex.vn</strong><br><br>
                💬 <strong>fb.com/lovex.vn</strong>
            </p>
        `,
            confirmButtonColor: '#FF4D79',
            confirmButtonText: 'Đóng'
        });
    }
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>