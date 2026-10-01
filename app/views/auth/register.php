<?php
$pageTitle = 'Đăng ký';
require_once __DIR__ . '/../layouts/header.php';
?>

<link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">

<div class="auth-hearts-bg"></div>

<div class="auth-card-love">
    <div class="auth-heart-logo">
        <div class="heart-shape"></div>
    </div>

    <div class="auth-welcome">
        <h2>Bắt đầu câu chuyện 💕</h2>
        <p>Tạo tài khoản để lưu giữ tình yêu</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="auth-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <form method="POST">
        <?= csrf_field() ?>

        <div class="auth-input-group">
            <input type="text" name="display_name" class="auth-input"
                placeholder="Tên hiển thị (VD: Nam, Linh)" required autofocus>
            <i class="bi bi-person-fill"></i>
        </div>

        <div class="auth-input-group">
            <input type="text" name="username" class="auth-input"
                placeholder="Biệt danh (VD: nam123, linhxinh)"
                required minlength="3" maxlength="20"
                pattern="[a-z0-9_]+"
                title="Chỉ dùng chữ thường, số và dấu gạch dưới"
                autocapitalize="none" autocomplete="username">
            <i class="bi bi-person-badge-fill"></i>
        </div>

        <div class="auth-input-group">
            <input type="password" name="password" class="auth-input"
                placeholder="Mật khẩu (tối thiểu 6 ký tự)" required minlength="6">
            <i class="bi bi-lock-fill"></i>
        </div>

        <div class="auth-input-group">
            <input type="email" name="email" class="auth-input"
                placeholder="Email (không bắt buộc)">
            <i class="bi bi-envelope-fill"></i>
        </div>

        <button type="submit" class="auth-btn">
            <i class="bi bi-heart-fill"></i>
            Đăng ký ngay
        </button>
    </form>

    <p class="auth-footer">
        Đã có tài khoản?
        <a href="<?= base_url('index.php?action=login') ?>">Đăng nhập</a>
    </p>
</div>

<script src="<?= base_url('js/auth.js') ?>"></script>
<!-- ============ HELP BUTTON (Register) ============ -->
<!-- <button class="help-fab help-fab-auth" id="helpFab" onclick="toggleAuthHelp()" title="Trợ giúp">
    <i class="bi bi-question-lg"></i>
</button> -->

<div class="help-menu help-menu-auth" id="helpMenu">
    <div class="help-menu-header">
        <i class="bi bi-heart-fill"></i>
        <strong>Trợ giúp LoveX</strong>
    </div>
    <a href="#" class="help-menu-item" onclick="showRegisterHelpStepByStep(); return false;">
        <i class="bi bi-play-circle"></i>
        <span>Hướng dẫn đăng ký</span>
    </a>
    <a href="#" class="help-menu-item" onclick="showUsernameHelp(); return false;">
        <i class="bi bi-person-badge"></i>
        <span>Chọn biệt danh thế nào?</span>
    </a>
    <a href="#" class="help-menu-item" onclick="showContactHelp(); return false;">
        <i class="bi bi-chat-dots"></i>
        <span>Liên hệ hỗ trợ</span>
    </a>
</div>

<script>
    function toggleAuthHelp() {
        document.getElementById('helpMenu')?.classList.toggle('active');
    }

    document.addEventListener('click', function(e) {
        const menu = document.getElementById('helpMenu');
        const fab = document.getElementById('helpFab');
        if (menu && fab && !menu.contains(e.target) && !fab.contains(e.target)) {
            menu.classList.remove('active');
        }
    });

    function showRegisterHelpStepByStep() {
        document.getElementById('helpMenu')?.classList.remove('active');
        Swal.fire({
            title: '📝 Cách đăng ký tài khoản',
            html: `
            <div style="text-align: left; font-size: 14px; line-height: 1.8;">
                <p><strong>1. Tên hiển thị:</strong> Nhập tên thật của bạn (VD: <code>Nguyễn Văn A</code>)</p>
                <p><strong>2. Biệt danh:</strong> Tên ngắn để đăng nhập, chỉ dùng chữ thường và số (VD: <code>vana123</code>)</p>
                <p><strong>3. Mật khẩu:</strong> Tối thiểu 6 ký tự, nên có chữ và số</p>
                <p><strong>4. Email:</strong> Không bắt buộc, nhưng nên điền để khôi phục tài khoản</p>
                <p style="margin-top: 12px; padding: 12px; background: #FFF5F8; border-radius: 12px; color: #FF4D79;">
                    💕 Sau khi đăng ký, bạn sẽ nhận được mã code để ghép đôi với người ấy!
                </p>
            </div>
        `,
            confirmButtonColor: '#FF4D79',
            confirmButtonText: 'Đã hiểu'
        });
    }

    function showUsernameHelp() {
        document.getElementById('helpMenu')?.classList.remove('active');
        Swal.fire({
            title: '👤 Biệt danh là gì?',
            html: `
            <div style="text-align: left; font-size: 14px; line-height: 1.8;">
                <p><strong>Biệt danh</strong> là tên bạn dùng để đăng nhập — ngắn gọn, dễ nhớ hơn email.</p>
                <p style="margin-top: 12px;"><strong>Ví dụ hợp lệ:</strong></p>
                <ul style="padding-left: 20px;">
                    <li>✅ <code>nam123</code></li>
                    <li>✅ <code>linhxinh</code></li>
                    <li>✅ <code>my_love_2024</code></li>
                </ul>
                <p><strong>Không hợp lệ:</strong></p>
                <ul style="padding-left: 20px;">
                    <li>❌ <code>Nguyễn Văn A</code> (có dấu, có khoảng trắng)</li>
                    <li>❌ <code>Nam</code> (chữ hoa)</li>
                    <li>❌ <code>a</code> (quá ngắn, tối thiểu 3 ký tự)</li>
                </ul>
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
            html: `<p>📧 support@lovex.vn</p><p>💬 fb.com/lovex.vn</p>`,
            confirmButtonColor: '#FF4D79'
        });
    }
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>