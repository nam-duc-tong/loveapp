<?php 
$pageTitle = 'Cài đặt';
// ✅ Khai báo biến mặc định để tránh lỗi "Undefined variable $user"
$user = $user ?? null;
$couple = $couple ?? null;

// ✅ Nếu Controller quên truyền $user → tự load từ DB
if ($user === null && is_logged_in()) {
    require_once __DIR__ . '/../models/User.php';
    $userModel = new User((new Database())->getConnection());
    $user = $userModel->findById(current_user_id());
}

// ✅ Nếu Controller quên truyền $couple → tự load từ DB
if ($couple === null && $user && !empty($user['couple_id'])) {
    require_once __DIR__ . '/../models/Couple.php';
    $coupleModel = new Couple((new Database())->getConnection());
    $couple = $coupleModel->findById($user['couple_id']);
}

// ✅ Nếu vẫn không có user → về login
if (!$user) {
    redirect(base_url('index.php?action=login'));
}

require_once __DIR__ . '/layouts/header.php'; 

$initial = strtoupper(mb_substr($_SESSION['display_name'], 0, 1));
?>

<header class="app-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?= base_url('index.php?action=home') ?>" class="btn-icon">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="logo">⚙️ Cài đặt</div>
            <div style="width:42px;"></div>
        </div>
    </div>
</header>

<main class="container pb-5">

    <!-- Avatar + tên -->
    <div class="settings-profile" data-aos="fade-up">
        <div class="settings-avatar-wrapper" onclick="document.getElementById('avatarInput').click()">
            <?php if (!empty($user['avatar_url'])): ?>
                <img src="<?= base_url($user['avatar_url']) ?>" alt="" id="avatarPreview">
            <?php else: ?>
                <div class="settings-avatar-default" id="avatarDefault"><?= $initial ?></div>
                <img src="" alt="" id="avatarPreview" style="display:none;">
            <?php endif; ?>
            <div class="avatar-edit-icon">
                <i class="bi bi-camera-fill"></i>
            </div>
        </div>
        <input type="file" id="avatarInput" accept="image/*" style="display:none;" onchange="previewAvatar(this)">
        <h4 class="mt-3 mb-0 fw-bold"><?= htmlspecialchars($user['display_name'] ?? '') ?></h4>
        <small class="text-muted"><?= htmlspecialchars($user['email'] ?? '') ?></small>
    </div>

    <!-- Form thông tin cá nhân -->
    <div class="settings-card" data-aos="fade-up">
        <h6 class="settings-title"><i class="bi bi-person-fill"></i> Thông tin cá nhân</h6>
        
        <div class="mb-3">
            <label class="form-label">Tên hiển thị</label>
            <input type="text" id="displayName" class="form-control" value="<?= htmlspecialchars($user['display_name'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Giới thiệu</label>
            <textarea id="bio" class="form-control" rows="2" placeholder="Vài dòng về bạn..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Ngày sinh</label>
            <input type="date" id="birthday" class="form-control" value="<?= $user['birthday'] ?? '' ?>">
        </div>

        <button class="btn btn-lovex w-100" onclick="saveProfile()">
            <i class="bi bi-check-circle"></i> Lưu thay đổi
        </button>
    </div>

    <!-- Cài đặt cặp đôi -->
    <?php if ($couple): ?>
    <div class="settings-card" data-aos="fade-up">
        <h6 class="settings-title"><i class="bi bi-heart-fill"></i> Cặp đôi</h6>
        
        <div class="mb-3">
            <label class="form-label">Ngày bắt đầu yêu</label>
            <input type="date" id="startDate" class="form-control" value="<?= $couple['start_date'] ?>">
        </div>

        <button class="btn btn-lovex w-100" onclick="saveStartDate()">
            <i class="bi bi-calendar-heart"></i> Cập nhật ngày yêu
        </button>
    </div>
    <?php endif; ?>

    <!-- Thông tin app -->
    <div class="settings-card" data-aos="fade-up">
        <h6 class="settings-title"><i class="bi bi-info-circle-fill"></i> Thông tin</h6>
        <div class="settings-row">
            <span>Phiên bản</span>
            <strong>1.0.0</strong>
        </div>
        <div class="settings-row">
            <span>Tài khoản tạo</span>
            <strong><?= date('d/m/Y', strtotime($user['created_at'])) ?></strong>
        </div>
    </div>

    <!-- Nút đăng xuất -->
    <a href="<?= base_url('index.php?action=logout') ?>" class="btn btn-outline-danger w-100 mt-3" data-aos="fade-up">
        <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>

</main>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('avatarPreview');
            const def = document.getElementById('avatarDefault');
            img.src = e.target.result;
            img.style.display = 'block';
            if (def) def.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

async function saveProfile() {
    const name = document.getElementById('displayName').value.trim();
    if (!name) {
        toast('Tên không được để trống!', 'warning');
        return;
    }

    const formData = new FormData();
    formData.append('display_name', name);
    formData.append('bio', document.getElementById('bio').value);
    formData.append('birthday', document.getElementById('birthday').value);
    
    const avatarInput = document.getElementById('avatarInput');
    if (avatarInput.files[0]) {
        formData.append('avatar', avatarInput.files[0]);
    }

    const res = await fetch(BASE_URL + 'index.php?action=update_profile', {
        method: 'POST', body: formData
    });
    const data = await res.json();

    if (data.success) {
        Swal.fire({
            icon: 'success', title: 'Đã lưu!',
            confirmButtonColor: '#FF4D79',
            timer: 1500, showConfirmButton: false
        }).then(() => location.reload());
    } else {
        toast(data.message || 'Có lỗi xảy ra!', 'error');
    }
}

async function saveStartDate() {
    const formData = new FormData();
    formData.append('start_date', document.getElementById('startDate').value);

    const res = await fetch(BASE_URL + 'index.php?action=update_start_date', {
        method: 'POST', body: formData
    });
    const data = await res.json();

    if (data.success) {
        Swal.fire({
            icon: 'success', title: 'Đã cập nhật ngày yêu! 💕',
            confirmButtonColor: '#FF4D79',
            timer: 1500, showConfirmButton: false
        }).then(() => location.reload());
    } else {
        toast('Có lỗi xảy ra!', 'error');
    }
}
</script>