<?php 
$pageTitle = 'Thư tình';
$activeNav = 'letters';
require_once __DIR__ . '/layouts/header.php'; 
?>

<header class="app-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?= base_url('index.php?action=home') ?>" class="btn-icon">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="logo">Thư tình 💌</div>
            <button class="btn-icon" onclick="openLetterModal()">
                <i class="bi bi-pencil-square"></i>
            </button>
        </div>
    </div>
</header>

<main class="container pb-5">
    <div class="row g-3">
        <?php if (empty($letters)): ?>
            <div class="col-12 text-center py-5">
                <div class="empty-state">
                    <i class="bi bi-envelope-heart"></i>
                    <h5 class="mt-3">Chưa có thư tình nào</h5>
                    <p class="text-muted">Hãy viết lá thư đầu tiên gửi đến người ấy!</p>
                    <button class="btn btn-lovex mt-2" onclick="openLetterModal()">
                        <i class="bi bi-pencil"></i> Viết thư ngay
                    </button>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($letters as $letter): ?>
                <div class="col-12" data-aos="fade-up">
                    <div class="letter-card <?= ($letter['sender_id'] != current_user_id() && !$letter['is_read']) ? 'unread' : '' ?>" 
                         onclick="openLetter(<?= $letter['id'] ?>, '<?= htmlspecialchars($letter['title'], ENT_QUOTES) ?>', '<?= htmlspecialchars($letter['content'], ENT_QUOTES) ?>', '<?= htmlspecialchars($letter['display_name'], ENT_QUOTES) ?>', '<?= date('d/m/Y H:i', strtotime($letter['created_at'])) ?>')">
                        <div class="letter-icon">
                            <i class="bi bi-envelope-heart-fill"></i>
                        </div>
                        <div class="letter-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6 class="fw-bold mb-1"><?= htmlspecialchars($letter['title']) ?></h6>
                                <?php if ($letter['sender_id'] != current_user_id() && !$letter['is_read']): ?>
                                    <span class="badge-new">Mới</span>
                                <?php endif; ?>
                            </div>
                            <p class="letter-preview"><?= htmlspecialchars(mb_substr($letter['content'], 0, 60)) ?>...</p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">
                                    <i class="bi bi-person"></i> <?= htmlspecialchars($letter['display_name']) ?>
                                </small>
                                <small class="text-muted"><?= date('d/m/Y', strtotime($letter['created_at'])) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<!-- Modal Viết thư -->
<div class="modal fade" id="letterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content checkin-modal">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">💌 Viết thư tình</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tiêu đề</label>
                    <input type="text" id="letterTitle" class="form-control form-control-lg" placeholder="Gửi em yêu...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nội dung</label>
                    <textarea id="letterContent" class="form-control" rows="8" placeholder="Viết những điều từ trái tim bạn..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-lovex w-100" onclick="saveLetter()">
                    <i class="bi bi-send"></i> Gửi thư
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Đọc thư -->
<div class="modal fade" id="readLetterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content read-letter-modal">
            <div class="modal-header border-0">
                <span class="letter-stamp">💌</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="letter-paper">
                    <h3 class="letter-title" id="readLetterTitle"></h3>
                    <p class="letter-meta">
                        Từ: <strong id="readLetterSender"></strong> • <span id="readLetterDate"></span>
                    </p>
                    <div class="letter-divider">❦</div>
                    <p class="letter-body" id="readLetterContent"></p>
                    <div class="letter-signature">
                        <i class="bi bi-heart-fill text-pink"></i> Yêu thương
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

<script>
function openLetterModal() {
    new bootstrap.Modal(document.getElementById('letterModal')).show();
}

async function saveLetter() {
    const title = document.getElementById('letterTitle').value.trim();
    const content = document.getElementById('letterContent').value.trim();

    if (!title || !content) {
        toast('Vui lòng nhập đầy đủ tiêu đề và nội dung!', 'warning');
        return;
    }

    const formData = new FormData();
    formData.append('title', title);
    formData.append('content', content);

    const res = await fetch(BASE_URL + 'index.php?action=letter_create', {
        method: 'POST', body: formData
    });
    const data = await res.json();

    if (data.success) {
        Swal.fire({
            icon: 'success', title: 'Đã gửi thư! 💌',
            text: 'Người ấy sẽ nhận được thư của bạn',
            timer: 1500, showConfirmButton: false,
            confirmButtonColor: '#FF4D79'
        }).then(() => location.reload());
    } else {
        toast('Có lỗi xảy ra!', 'error');
    }
}

async function openLetter(id, title, content, sender, date) {
    document.getElementById('readLetterTitle').textContent = title;
    document.getElementById('readLetterContent').textContent = content;
    document.getElementById('readLetterSender').textContent = sender;
    document.getElementById('readLetterDate').textContent = date;
    
    new bootstrap.Modal(document.getElementById('readLetterModal')).show();

    // Đánh dấu đã đọc
    const formData = new FormData();
    formData.append('id', id);
    await fetch(BASE_URL + 'index.php?action=letter_read', {
        method: 'POST', body: formData
    });
}
</script>