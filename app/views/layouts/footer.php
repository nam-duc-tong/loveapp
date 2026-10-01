<?php
// $hideNav = $hideNav ?? false;
// if (!$hideNav && strpos($_SERVER['REQUEST_URI'], 'logout') === false):
?>
<!-- ============ BOTTOM NAVIGATION ============ -->
<nav class="bottom-nav">
    <a href="<?= base_url('index.php?action=home') ?>" class="nav-item <?= ($activeNav ?? '') === 'home' ? 'active' : '' ?>">
        <i class="bi bi-house-door-fill"></i>
        <span>Trang chủ</span>
    </a>
    <a href="<?= base_url('index.php?action=memories') ?>" class="nav-item <?= ($activeNav ?? '') === 'memories' ? 'active' : '' ?>">
        <i class="bi bi-book"></i>
        <span>Kỷ niệm</span>
    </a>
    <a href="<?= base_url('index.php?action=timeline') ?>" class="nav-item <?= ($activeNav ?? '') === 'timeline' ? 'active' : '' ?>">
        <i class="bi bi-clock-history"></i>
        <span>Timeline</span>
    </a>
    <a href="<?= base_url('index.php?action=letters') ?>" class="nav-item <?= ($activeNav ?? '') === 'letters' ? 'active' : '' ?>">
        <i class="bi bi-envelope-heart"></i>
        <span>Thư tình</span>
    </a>
    <a href="<?= base_url('index.php?action=settings') ?>" class="nav-item <?= ($activeNav ?? '') === 'settings' ? 'active' : '' ?>">
        <i class="bi bi-person-circle"></i>
        <span>Tôi</span>
    </a>
</nav>

<!-- Containers cho animation -->
<div id="heartContainer"></div>
<div id="petalsContainer"></div>
<div id="particlesContainer"></div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    AOS.init({
        duration: 800,
        once: true
    });
    const BASE_URL = '<?= base_url() ?>';

    // ============ SIDEBAR TOGGLE (FIX LỖI) ============
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (!sidebar || !overlay) {
            console.error('Sidebar element không tồn tại!');
            return;
        }
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
        document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
    }

    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebar) sidebar.classList.remove('open');
        if (overlay) overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    // ============ ABOUT ============
    function showAbout(e) {
        if (e) e.preventDefault();
        Swal.fire({
            title: '💕 LoveX',
            html: `
                    <p><strong>Phiên bản:</strong> 1.0</p>
                    <p>Ứng dụng dành cho các cặp đôi yêu nhau</p>
                    <p style="color:#FF4D79">Made with ❤️ in Vietnam</p>
                `,
            confirmButtonColor: '#FF4D79',
            confirmButtonText: 'Đóng'
        });
    }

    // ============ THEME TOGGLE ============
    function toggleTheme(e) {
        if (e) e.preventDefault();
        const current = document.documentElement.getAttribute('data-theme') || 'light';
        const next = current === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', next);
        localStorage.setItem('lovex_theme', next);
        updateThemeUI();
        if (typeof toast === 'function') {
            toast(next === 'dark' ? '🌙 Chế độ tối' : '☀️ Chế độ sáng', 'success');
        }
    }

    function updateThemeUI() {
        const theme = document.documentElement.getAttribute('data-theme') || 'light';
        const icon = document.getElementById('themeIcon');
        const text = document.getElementById('themeText');
        if (icon && text) {
            icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
            text.textContent = theme === 'dark' ? 'Chế độ sáng' : 'Chế độ tối';
        }
    }

    document.addEventListener('DOMContentLoaded', updateThemeUI);

    // ============ TOAST ============
    function toast(message, icon = 'info') {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: icon,
            title: message,
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });
    }

    // ============ FLOATING HEART ============
    function createFloatingHeart() {
        const container = document.getElementById('heartContainer');
        if (!container) return;
        const heart = document.createElement('div');
        heart.className = 'floating-heart';
        const emojis = ['❤️', '💕', '💖', '💗', '💝', '💘', '🩷'];
        heart.innerHTML = emojis[Math.floor(Math.random() * emojis.length)];
        heart.style.left = Math.random() * 100 + '%';
        heart.style.fontSize = (24 + Math.random() * 24) + 'px';
        heart.style.animationDuration = (3 + Math.random() * 2) + 's';
        container.appendChild(heart);
        setTimeout(() => heart.remove(), 6000);
    }

    function sendHeart() {
        for (let i = 0; i < 12; i++) {
            setTimeout(createFloatingHeart, i * 100);
        }
        toast('Đã gửi tim cho người ấy! 💕', 'success');
    }

    // ============ PETALS ============
    function createPetal() {
        const container = document.getElementById('petalsContainer');
        if (!container) return;
        const petal = document.createElement('div');
        petal.className = 'petal';
        petal.innerHTML = ['🌸', '🌺', '💮', '🏵️'][Math.floor(Math.random() * 4)];
        petal.style.left = Math.random() * 100 + '%';
        petal.style.fontSize = (14 + Math.random() * 16) + 'px';
        petal.style.animationDuration = (8 + Math.random() * 8) + 's';
        petal.style.animationDelay = Math.random() * 5 + 's';
        container.appendChild(petal);
        setTimeout(() => petal.remove(), 20000);
    }
    setInterval(createPetal, 2500);
    for (let i = 0; i < 5; i++) setTimeout(createPetal, i * 800);

    // ============ PARTICLES ============
    function createParticle() {
        const container = document.getElementById('particlesContainer');
        if (!container) return;
        const p = document.createElement('div');
        p.className = 'particle';
        p.style.left = Math.random() * 100 + '%';
        p.style.animationDuration = (10 + Math.random() * 15) + 's';
        p.style.animationDelay = Math.random() * 10 + 's';
        p.style.width = p.style.height = (2 + Math.random() * 4) + 'px';
        container.appendChild(p);
        setTimeout(() => p.remove(), 30000);
    }
    for (let i = 0; i < 15; i++) setTimeout(createParticle, i * 500);

    // ============ CONFETTI ============
    function launchConfetti() {
        const colors = ['#FF4D79', '#FF80AB', '#A855F7', '#4D9FFF', '#FFB84D'];
        for (let i = 0; i < 50; i++) {
            setTimeout(() => {
                const conf = document.createElement('div');
                conf.className = 'confetti';
                conf.style.left = Math.random() * 100 + '%';
                conf.style.background = colors[Math.floor(Math.random() * colors.length)];
                conf.style.animationDuration = (2 + Math.random() * 2) + 's';
                document.body.appendChild(conf);
                setTimeout(() => conf.remove(), 4000);
            }, i * 30);
        }
    }
</script>
<script src="<?= base_url('js/app.js') ?>"></script>
<script>
    // Đăng ký Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('<?= base_url('sw.js') ?>')
                .then(reg => console.log('✅ PWA Service Worker đã đăng ký'))
                .catch(err => console.log('❌ Lỗi Service Worker:', err));
        });
    }
</script>
<!-- ============ ONBOARDING OVERLAY ============ -->
<div class="onboarding-overlay" id="onboardingOverlay"></div>

<!-- ============ WELCOME MODAL ============ -->
<div class="onboarding-welcome" id="onboardingWelcome">
    <div class="onboarding-heart">💕</div>
    <h2 class="onboarding-title">Chào mừng đến LoveX!</h2>
    <p class="onboarding-desc">
        Ứng dụng lưu giữ kỷ niệm tình yêu của hai bạn.<br>
        Hãy để chúng tôi hướng dẫn bạn cách sử dụng nhé!
    </p>
    <button class="onboarding-btn" onclick="startOnboarding()">
        <i class="bi bi-play-circle-fill"></i> Bắt đầu hướng dẫn
    </button>
    <button class="onboarding-skip" onclick="skipOnboarding()">
        Bỏ qua, tôi tự khám phá
    </button>
</div>

<!-- ============ TOUR POPUP ============ -->
<div class="tour-popup" id="tourPopup">
    <div class="tour-arrow"></div>
    <div class="tour-step-badge" id="tourStepBadge">Bước 1/5</div>
    <h4 class="tour-title" id="tourTitle"></h4>
    <p class="tour-desc" id="tourDesc"></p>
    <div class="tour-actions">
        <button class="tour-btn tour-btn-skip" onclick="skipOnboarding()">Bỏ qua</button>
        <button class="tour-btn tour-btn-primary" id="tourNextBtn" onclick="tourNext()">
            Tiếp theo →
        </button>
    </div>
    <div class="tour-progress" id="tourProgress"></div>
</div>

<!-- ============ HELP BUTTON (Floating) ============ -->
<?php if (is_logged_in()): ?>
    <button class="help-fab" id="helpFab" onclick="toggleHelpMenu()">
        <i class="bi bi-question-lg"></i>
    </button>

    <div class="help-menu" id="helpMenu">
        <a href="#" class="help-menu-item" onclick="showQuickGuide(); return false;">
            <i class="bi bi-play-circle"></i>
            <span>Hướng dẫn nhanh</span>
        </a>
        <a href="<?= base_url('index.php?action=help') ?>" class="help-menu-item">
            <i class="bi bi-question-circle"></i>
            <span>Câu hỏi thường gặp</span>
        </a>
        <a href="#" class="help-menu-item" onclick="showContact(); return false;">
            <i class="bi bi-chat-dots"></i>
            <span>Liên hệ hỗ trợ</span>
        </a>
        <a href="#" class="help-menu-item" onclick="if(confirm('Xem lại hướng dẫn từ đầu?')){showQuickGuide();} return false;">
            <i class="bi bi-arrow-counterclockwise"></i>
            <span>Xem lại hướng dẫn</span>
        </a>
    </div>
<?php endif; ?>

<!-- ============ ONBOARDING SCRIPTS ============ -->
<script>
    // ✅ Tự động hiện onboarding nếu user chưa xem
    <?php if (is_logged_in() && empty($GLOBALS['user_onboarding_done'])): ?>
        document.addEventListener('DOMContentLoaded', () => {
            // Đợi 1.5s cho trang load xong
            setTimeout(() => {
                document.getElementById('onboardingWelcome')?.classList.add('active');
                document.getElementById('onboardingOverlay')?.classList.add('active');
            }, 1500);
        });
    <?php endif; ?>
</script>
<script src="<?= base_url('js/onboarding.js') ?>?v=<?= time() ?>"></script>
</body>

</html>