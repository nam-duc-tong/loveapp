<?php 
$pageTitle = 'Ghép đôi';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="auth-container">
    <div class="auth-card" data-aos="zoom-in">
        <div class="text-center mb-4">
            <div class="auth-logo">
                <i class="bi bi-people-fill"></i>
            </div>
            <h2 class="fw-bold mt-3">Ghép đôi</h2>
            <p class="text-muted">Kết nối với người ấy của bạn</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <ul class="nav nav-pills nav-fill mb-4" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#createTab">Tạo cặp đôi</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#joinTab">Tham gia</button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="createTab">
                <?php if (!empty($user['pair_code'])): ?>
                    <div class="alert alert-info">
                        <strong>Mã ghép đôi của bạn:</strong> 
                        <span class="fs-4 fw-bold text-pink"><?= htmlspecialchars($user['pair_code']) ?></span>
                        <br><small>Gửi mã này cho người ấy để họ tham gia</small>
                    </div>
                <?php endif; ?>

                <?php if (empty($user['couple_id'])): ?>
                    <form method="POST">
                        <input type="hidden" name="pair_action" value="create">
                        <div class="mb-3">
                            <label class="form-label">Ngày bắt đầu yêu</label>
                            <input type="date" name="start_date" class="form-control form-control-lg" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <button type="submit" class="btn btn-lovex w-100 btn-lg">Tạo cặp đôi</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning">
                        Bạn đã tạo cặp đôi. Hãy đợi người ấy tham gia bằng mã trên.
                    </div>
                <?php endif; ?>
            </div>

            <div class="tab-pane fade" id="joinTab">
                <form method="POST">
                    <input type="hidden" name="pair_action" value="join">
                    <div class="mb-3">
                        <label class="form-label">Mã ghép đôi của người ấy</label>
                        <input type="text" name="pair_code" class="form-control form-control-lg text-uppercase" maxlength="6" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày bắt đầu yêu</label>
                        <input type="date" name="start_date" class="form-control form-control-lg" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <button type="submit" class="btn btn-lovex w-100 btn-lg">Tham gia</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>