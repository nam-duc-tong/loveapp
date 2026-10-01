<?php 
$pageTitle = 'Timeline';
$activeNav = 'timeline';

$events = $events ?? [];

$days = ['Sunday'=>'Chủ nhật','Monday'=>'Thứ Hai','Tuesday'=>'Thứ Ba','Wednesday'=>'Thứ Tư','Thursday'=>'Thứ Năm','Friday'=>'Thứ Sáu','Saturday'=>'Thứ Bảy'];

require_once __DIR__ . '/layouts/header.php'; 
?>

<header class="app-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?= base_url('index.php?action=home') ?>" class="btn-icon">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="logo">💕 Timeline</div>
            <div style="width:42px;"></div>
        </div>
    </div>
</header>

<main class="container pb-5">

    <div class="text-center mb-4" data-aos="fade-up">
        <h4 class="fw-bold">Câu chuyện tình yêu của chúng ta</h4>
        <p class="text-muted">Mỗi khoảnh khắc là một kỷ niệm đẹp</p>
    </div>

    <?php if (empty($events)): ?>
        <div class="empty-state text-center py-5" data-aos="zoom-in">
            <i class="bi bi-clock-history"></i>
            <h5 class="mt-3">Chưa có sự kiện nào</h5>
            <p class="text-muted">Hãy thêm kỷ niệm hoặc viết thư tình để bắt đầu!</p>
        </div>
    <?php else: ?>
        <div class="timeline">
            <?php foreach ($events as $index => $e): ?>
                <div class="timeline-item <?= $index % 2 === 0 ? 'left' : 'right' ?>" 
                     data-aos="<?= $index % 2 === 0 ? 'fade-right' : 'fade-left' ?>">
                    <div class="timeline-dot">
                        <?php if ($e['type'] === 'memory'): ?>
                            <i class="bi bi-bookmark-heart-fill"></i>
                        <?php else: ?>
                            <i class="bi bi-envelope-heart-fill"></i>
                        <?php endif; ?>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">
                            <i class="bi bi-calendar3"></i>
                            <?= date('d/m/Y', strtotime($e['date'])) ?>
                        </div>
                        <?php if ($e['image_url']): ?>
                            <img src="<?= base_url($e['image_url']) ?>" class="timeline-image" alt="">
                        <?php endif; ?>
                        <h6 class="timeline-title"><?= htmlspecialchars($e['title']) ?></h6>
                        <?php if ($e['description']): ?>
                            <p class="timeline-desc"><?= htmlspecialchars(mb_substr($e['description'], 0, 120)) ?><?= mb_strlen($e['description']) > 120 ? '...' : '' ?></p>
                        <?php endif; ?>
                        <span class="timeline-type">
                            <?= $e['type'] === 'memory' ? '📖 Kỷ niệm' : '💌 Thư tình' ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>