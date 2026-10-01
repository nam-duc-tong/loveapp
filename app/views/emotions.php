<?php 
$pageTitle = 'Cảm xúc';
$activeNav = 'emotions';

$stats = $stats ?? [];
$moodCount = $moodCount ?? [];

$moods = [
    'happy'  => ['😄', 'Vui vẻ', '#FFB84D'],
    'love'   => ['🥰', 'Yêu đời', '#FF4D79'],
    'normal' => ['😐', 'Bình thường', '#999999'],
    'tired'  => ['😴', 'Mệt mỏi', '#A855F7'],
    'sad'    => ['😢', 'Buồn', '#4D9FFF'],
    'angry'  => ['😡', 'Giận', '#E53935']
];

$total = array_sum($moodCount);

require_once __DIR__ . '/layouts/header.php'; 
?>

<header class="app-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?= base_url('index.php?action=home') ?>" class="btn-icon">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="logo">📊 Cảm xúc</div>
            <div style="width:42px;"></div>
        </div>
    </div>
</header>

<main class="container pb-5">

    <div class="text-center mb-4" data-aos="fade-up">
        <h5 class="fw-bold">30 ngày qua</h5>
        <p class="text-muted">Cảm xúc của hai bạn</p>
    </div>

    <?php if ($total === 0): ?>
        <div class="empty-state text-center py-5">
            <i class="bi bi-emoji-smile"></i>
            <h5 class="mt-3">Chưa có dữ liệu</h5>
            <p class="text-muted">Hãy check-in cảm xúc mỗi ngày nhé!</p>
        </div>
    <?php else: ?>

        <!-- Biểu đồ tròn -->
        <div class="chart-card" data-aos="zoom-in">
            <div class="mood-pie" id="moodPie">
                <?php 
                $gradient_parts = [];
                $cumulative = 0;
                foreach ($moods as $key => $info):
                    $count = $moodCount[$key];
                    $percent = $total > 0 ? ($count / $total) * 100 : 0;
                    if ($percent > 0):
                        $gradient_parts[] = $info[2] . ' ' . $cumulative . '% ' . ($cumulative + $percent) . '%';
                        $cumulative += $percent;
                    endif;
                endforeach;
                ?>
                <div class="pie-chart" style="background: conic-gradient(<?= implode(', ', $gradient_parts) ?>);">
                    <div class="pie-center">
                        <div class="pie-total"><?= $total ?></div>
                        <small>lần check-in</small>
                    </div>
                </div>
            </div>

            <div class="mood-legend">
                <?php foreach ($moods as $key => $info): ?>
                    <?php if ($moodCount[$key] > 0): ?>
                        <div class="legend-item">
                            <span class="legend-color" style="background: <?= $info[2] ?>;"></span>
                            <span class="legend-emoji"><?= $info[0] ?></span>
                            <span class="legend-label"><?= $info[1] ?></span>
                            <strong class="legend-count"><?= $moodCount[$key] ?></strong>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Mood timeline -->
        <div class="section-header mt-4" data-aos="fade-up">
            <h5>Lịch sử gần đây</h5>
        </div>

        <div class="mood-history" data-aos="fade-up">
            <?php foreach (array_slice(array_reverse($stats), 0, 15) as $s): ?>
                <?php $info = $moods[$s['mood']] ?? $moods['normal']; ?>
                <div class="mood-history-item">
                    <span class="mh-emoji"><?= $info[0] ?></span>
                    <span class="mh-date"><?= date('d/m', strtotime($s['checkin_date'])) ?></span>
                    <span class="mh-mood" style="color: <?= $info[2] ?>;"><?= $info[1] ?></span>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>