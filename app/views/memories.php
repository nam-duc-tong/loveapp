<?php 
$pageTitle = 'Kỷ niệm';
$activeNav = 'memories';
require_once __DIR__ . '/layouts/header.php'; 
?>

<header class="app-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?= base_url('index.php?action=home') ?>" class="btn-icon">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="logo">📖 Kỷ niệm</div>
            <button class="btn-icon" onclick="openMemoryModal()">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>
    </div>
</header>

<main class="container pb-5">
    <?php if (empty($memories)): ?>
        <div class="empty-state text-center py-5" data-aos="zoom-in">
            <i class="bi bi-book"></i>
            <h5 class="mt-3">Chưa có kỷ niệm nào</h5>
            <p class="text-muted">Hãy lưu lại những khoảnh khắc đáng nhớ của hai bạn!</p>
            <button class="btn btn-lovex mt-2" onclick="openMemoryModal()">
                <i class="bi bi-plus-circle"></i> Thêm kỷ niệm đầu tiên
            </button>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($memories as $index => $mem): ?>
                <div class="col-12" data-aos="fade-up" data-aos-delay="<?= $index * 50 ?>">
                    <div class="memory-card">
                        <?php if ($mem['image_url']): ?>
                            <div class="memory-img-wrapper">
                                <img src="<?= base_url($mem['image_url']) ?>" class="memory-img" alt="">
                                <div class="memory-date-badge">
                                    <i class="bi bi-calendar-heart"></i>
                                    <?= date('d/m/Y', strtotime($mem['event_date'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="memory-content">
                            <h5 class="fw-bold mb-1"><?= htmlspecialchars($mem['title']) ?></h5>
                            <?php if (!$mem['image_url']): ?>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($mem['event_date'])) ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($mem['description']): ?>
                                <p class="mb-0 text-secondary"><?= nl2br(htmlspecialchars($mem['description'])) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<!-- Modal Thêm kỷ niệm -->
<div class="modal fade" id="memoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content checkin-modal">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">📸 Thêm kỷ niệm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="memoryForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề</label>
                        <input type="text" name="title" class="form-control" placeholder="Ví dụ: Lần đầu gặp nhau" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày kỷ niệm</label>
                        <input type="date" name="event_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Kể lại câu chuyện..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ảnh kỷ niệm</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-lovex w-100" onclick="saveMemory()">
                    <i class="bi bi-bookmark-heart"></i> Lưu kỷ niệm
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>