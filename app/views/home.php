<?php
$pageTitle = 'Trang chủ';
$activeNav = 'home';
// ============ KHAI BÁO BIẾN MẶC ĐỊNH ============
// Các biến này sẽ được Controller truyền vào, nhưng khai báo ở đây để:
// 1. VS Code hết gạch chân đỏ
// 2. Tránh lỗi "Undefined variable" nếu Controller quên truyền

/** @var int $daysTogether Số ngày yêu nhau */
$daysTogether = $daysTogether ?? 0;

/** @var int $monthsTogether Số tháng yêu nhau */
$monthsTogether = $monthsTogether ?? 0;

/** @var string $startDate Ngày bắt đầu yêu (Y-m-d) */
$startDate = $startDate ?? date('Y-m-d');

/** @var int $memoryCount Số kỷ niệm */
$memoryCount = $memoryCount ?? 0;

/** @var int $photoCount Số ảnh */
$photoCount = $photoCount ?? 0;

/** @var int $unreadLetters Số thư chưa đọc */
$unreadLetters = $unreadLetters ?? 0;

/** @var array|null $partner Thông tin người ấy */
$partner = $partner ?? null;

/** @var array $todayCheckins Danh sách check-in hôm nay */
$todayCheckins = $todayCheckins ?? [];

/** @var array|null $myCheckin Check-in của mình */
$myCheckin = $myCheckin ?? null;

/** @var array|null $partnerCheckin Check-in của partner */
$partnerCheckin = $partnerCheckin ?? null;

/** @var array $allMemories Danh sách kỷ niệm */
$allMemories = $allMemories ?? [];

// ============ TỰ TÍNH NẾU CONTROLLER CHƯA TRUYỀN ============
// Nếu Controller chỉ truyền $todayCheckins mà không truyền $myCheckin
if ($myCheckin === null && !empty($todayCheckins)) {
    foreach ($todayCheckins as $c) {
        if (isset($c['user_id']) && $c['user_id'] == current_user_id()) {
            $myCheckin = $c;
        }
        if ($partner && isset($c['user_id']) && $c['user_id'] == $partner['id']) {
            $partnerCheckin = $c;
        }
    }
}
require_once __DIR__ . '/layouts/header.php';



// ============ KHAI BÁO BIẾN (đảm bảo không lỗi Undefined) ============
// Nếu Controller đã truyền $myCheckin và $partnerCheckin rồi thì giữ nguyên,
// nếu chưa thì gán mặc định null
$myCheckin      = $myCheckin ?? null;
$partnerCheckin = $partnerCheckin ?? null;
$todayCheckins  = $todayCheckins ?? [];
$partner        = $partner ?? null;
$memoryCount    = $memoryCount ?? 0;
$photoCount     = $photoCount ?? 0;
$unreadLetters  = $unreadLetters ?? 0;

// Nếu Controller chưa truyền $myCheckin/$partnerCheckin thì tự tính từ $todayCheckins
if (empty($myCheckin) && !empty($todayCheckins)) {
    foreach ($todayCheckins as $c) {
        if ($c['user_id'] == current_user_id()) $myCheckin = $c;
        if ($partner && $c['user_id'] == $partner['id']) $partnerCheckin = $c;
    }
}
// Helper functions cho mood
if (!function_exists('getMoodEmoji')) {
    function getMoodEmoji($mood)
    {
        $map = ['happy' => '😄', 'love' => '🥰', 'normal' => '😐', 'tired' => '😴', 'sad' => '😢', 'angry' => '😡'];
        return $map[$mood] ?? '😐';
    }
}
if (!function_exists('getMoodText')) {
    function getMoodText($mood)
    {
        $map = ['happy' => 'Vui vẻ', 'love' => 'Yêu đời', 'normal' => 'Bình thường', 'tired' => 'Mệt mỏi', 'sad' => 'Buồn', 'angry' => 'Giận'];
        return $map[$mood] ?? 'Bình thường';
    }
}

// Chữ cái đầu của tên

$myInitial      = strtoupper(mb_substr($_SESSION['display_name'], 0, 1));
$partnerInitial = $partner ? strtoupper(mb_substr($partner['display_name'], 0, 1)) : '?';
$partnerName    = $partner ? htmlspecialchars($partner['display_name']) : '...';
$myName         = htmlspecialchars($_SESSION['display_name']);

// Thứ trong tuần bằng tiếng Việt
$days = ['Sunday' => 'Chủ nhật', 'Monday' => 'Thứ Hai', 'Tuesday' => 'Thứ Ba', 'Wednesday' => 'Thứ Tư', 'Thursday' => 'Thứ Năm', 'Friday' => 'Thứ Sáu', 'Saturday' => 'Thứ Bảy'];
$today = $days[date('l')] . ', ' . date('d/m');
?>

<!-- ============ HEADER ============ -->
<header class="app-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <button class="btn-icon" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div class="logo">
                <i class="bi bi-heart-fill"></i> LoveX
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('index.php?action=letters') ?>" class="btn-icon position-relative">
                    <i class="bi bi-bell"></i>
                    <?php if ($unreadLetters > 0): ?>
                        <span class="badge-dot"><?= $unreadLetters ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- ============ MAIN ============ -->
<main class="container pb-5">

    <!-- HERO CARD -->
    <div class="hero-card" data-aos="fade-up">
        <div class="hero-bg-decoration"></div>

        <div class="d-flex align-items-center mb-3">
            <div class="avatar-stack">
                <div class="avatar-circle"><?= $myInitial ?></div>
                <div class="avatar-circle"><?= $partnerInitial ?></div>
            </div>
            <span class="ms-3 fw-bold text-white">
                <?= $myName ?> & <?= $partnerName ?>
            </span>
        </div>

        <p class="text-white opacity-75 mb-1">Đã yêu nhau</p>
        <div class="d-flex align-items-end">
            <h1 class="days-counter mb-0"><?= $daysTogether ?></h1>
            <span class="fs-3 ms-2 mb-3 text-white">ngày</span>
        </div>
        <p class="text-white opacity-75">từ <?= date('d/m/Y', strtotime($startDate)) ?></p>

        <div class="d-flex flex-wrap gap-2 mt-4">
            <span class="stat-badge">
                <i class="bi bi-book"></i> <?= $memoryCount ?> kỷ niệm
            </span>
            <span class="stat-badge">
                <i class="bi bi-image"></i> <?= $photoCount ?> ảnh
            </span>
            <span class="stat-badge">
                <i class="bi bi-calendar-heart"></i> <?= $monthsTogether ?> tháng
            </span>
            <span class="stat-badge">
                <i class="bi bi-envelope-heart"></i> <?= $unreadLetters ?> thư mới
            </span>
        </div>

        <div class="hero-illustration">
            <i class="bi bi-hearts"></i>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="quick-actions" data-aos="fade-up" data-aos-delay="100">
        <div class="quick-action" onclick="openCheckinModal()">
            <div class="qa-icon qa-yellow">
                <i class="bi bi-emoji-smile"></i>
            </div>
            <span>Check-in</span>
        </div>
        <a href="<?= base_url('index.php?action=memories') ?>" class="quick-action">
            <div class="qa-icon qa-pink">
                <i class="bi bi-camera"></i>
            </div>
            <span>Kỷ niệm</span>
        </a>
        <a href="<?= base_url('index.php?action=story') ?>" class="quick-action">
            <div class="qa-icon qa-purple">
                <i class="bi bi-lightning-charge"></i>
            </div>
            <span>Story</span>
        </a>
        <div class="quick-action" onclick="sendHeart()">
            <div class="qa-icon qa-red">
                <i class="bi bi-heart"></i>
            </div>
            <span>Thả tim</span>
        </div>
        <a href="<?= base_url('index.php?action=letters') ?>" class="quick-action">
            <div class="qa-icon qa-blue">
                <i class="bi bi-envelope"></i>
            </div>
            <span>Thư tình</span>
        </a>
    </div>

    <!-- HÔM NAY CỦA HAI BẠN -->
    <div class="section-header" data-aos="fade-up" data-aos-delay="200">
        <h5>Hôm nay của hai bạn</h5>
        <small><?= $today ?></small>
    </div>

    <div class="today-card" data-aos="fade-up" data-aos-delay="250">
        <div class="row g-0">
            <!-- Bản thân -->
            <div class="col-6 text-center py-3 border-end">
                <div class="avatar-circle mx-auto mb-2" style="background: #FFE4E9; color: #FF4D79;">
                    <?= $myInitial ?>
                </div>
                <?php if ($myCheckin): ?>
                    <div class="mood-display">
                        <span class="mood-emoji-sm"><?= getMoodEmoji($myCheckin['mood']) ?></span>
                        <p class="small mb-0 fw-bold text-pink"><?= getMoodText($myCheckin['mood']) ?></p>
                    </div>
                <?php else: ?>
                    <p class="small mb-0 text-muted">Chưa check-in</p>
                <?php endif; ?>
            </div>

            <!-- Partner -->
            <div class="col-6 text-center py-3">
                <div class="avatar-circle mx-auto mb-2" style="background: #E4F0FF; color: #4D9FFF;">
                    <?= $partnerInitial ?>
                </div>
                <?php if ($partnerCheckin): ?>
                    <div class="mood-display">
                        <span class="mood-emoji-sm"><?= getMoodEmoji($partnerCheckin['mood']) ?></span>
                        <p class="small mb-0 fw-bold" style="color: #4D9FFF;"><?= getMoodText($partnerCheckin['mood']) ?></p>
                    </div>
                <?php else: ?>
                    <p class="small mb-0 text-muted">Chưa cập nhật</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="checkin-cta" onclick="openCheckinModal()">
            <i class="bi bi-hand-index-thumb"></i>
            <span><?= $myCheckin ? 'Cập nhật lại cảm xúc hôm nay' : 'Hôm nay bạn thấy thế nào? Chạm để check-in' ?></span>
        </div>
    </div>

    <!-- SẮP TỚI (Kỷ niệm gần nhất) -->
    <?php if (!empty($allMemories)): ?>
        <div class="section-header mt-4" data-aos="fade-up">
            <h5>Kỷ niệm gần đây</h5>
            <a href="<?= base_url('index.php?action=memories') ?>" class="text-pink small fw-bold text-decoration-none">
                Xem tất cả <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        <div class="row g-2" data-aos="fade-up">
            <?php foreach (array_slice($allMemories, 0, 2) as $mem): ?>
                <div class="col-6">
                    <a href="<?= base_url('index.php?action=memories') ?>" class="text-decoration-none">
                        <div class="mini-memory-card">
                            <?php if ($mem['image_url']): ?>
                                <img src="<?= base_url($mem['image_url']) ?>" alt="">
                            <?php else: ?>
                                <div class="mini-memory-placeholder">
                                    <i class="bi bi-book"></i>
                                </div>
                            <?php endif; ?>
                            <div class="mini-memory-content">
                                <h6 class="mb-0 text-dark fw-bold"><?= htmlspecialchars(mb_substr($mem['title'], 0, 20)) ?></h6>
                                <small class="text-muted"><?= date('d/m/Y', strtotime($mem['event_date'])) ?></small>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <!-- ĐẾM NGƯỢC SỰ KIỆN ĐẶC BIỆT -->
    <div class="countdown-section" data-aos="fade-up">
        <div class="countdown-card">
            <div class="countdown-header">
                <i class="bi bi-hourglass-split"></i>
                <span>Kỷ niệm tiếp theo</span>
            </div>
            <div class="countdown-timer" id="countdown">
                <div class="countdown-item">
                    <span class="countdown-num" id="cdDays">--</span>
                    <small>Ngày</small>
                </div>
                <div class="countdown-sep">:</div>
                <div class="countdown-item">
                    <span class="countdown-num" id="cdHours">--</span>
                    <small>Giờ</small>
                </div>
                <div class="countdown-sep">:</div>
                <div class="countdown-item">
                    <span class="countdown-num" id="cdMins">--</span>
                    <small>Phút</small>
                </div>
                <div class="countdown-sep">:</div>
                <div class="countdown-item">
                    <span class="countdown-num" id="cdSecs">--</span>
                    <small>Giây</small>
                </div>
            </div>
            <div class="countdown-label" id="countdownLabel">
                Đang tính toán...
            </div>
        </div>
    </div>

    <script>
        // Đếm ngược đến kỷ niệm tiếp theo (mỗi tháng vào ngày bắt đầu yêu)
        (function() {
            const startDate = new Date('<?= $startDate ?>');
            const today = new Date();

            // Tính kỷ niệm tiếp theo
            let nextAnniv = new Date(today.getFullYear(), today.getMonth(), startDate.getDate());
            if (nextAnniv <= today) {
                nextAnniv = new Date(today.getFullYear(), today.getMonth() + 1, startDate.getDate());
            }

            const monthsSince = Math.round((today - startDate) / (1000 * 60 * 60 * 24 * 30));

            function update() {
                const now = new Date();
                const diff = nextAnniv - now;

                if (diff <= 0) {
                    document.getElementById('countdownLabel').textContent = '🎉 Chúc mừng kỷ niệm!';
                    return;
                }

                const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((diff % (1000 * 60)) / 1000);

                document.getElementById('cdDays').textContent = String(d).padStart(2, '0');
                document.getElementById('cdHours').textContent = String(h).padStart(2, '0');
                document.getElementById('cdMins').textContent = String(m).padStart(2, '0');
                document.getElementById('cdSecs').textContent = String(s).padStart(2, '0');

                document.getElementById('countdownLabel').textContent =
                    `Còn ${d} ngày nữa là kỷ niệm ${monthsSince + 1} tháng 💕`;
            }

            update();
            setInterval(update, 1000);
        })();
    </script>
</main>

<!-- ============ MODAL CHECK-IN ============ -->
<div class="modal fade" id="checkinModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content checkin-modal">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Hôm nay bạn thấy thế nào?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mood-grid" id="moodGrid">
                    <?php
                    $moods = [
                        'happy'  => ['😄', 'Vui vẻ'],
                        'love'   => ['🥰', 'Yêu đời'],
                        'normal' => ['😐', 'Bình thường'],
                        'tired'  => ['😴', 'Mệt mỏi'],
                        'sad'    => ['😢', 'Buồn'],
                        'angry'  => ['😡', 'Giận']
                    ];
                    foreach ($moods as $key => $m):
                        $selected = ($myCheckin && $myCheckin['mood'] === $key) ? 'selected' : '';
                    ?>
                        <div class="mood-item <?= $selected ?>" data-mood="<?= $key ?>">
                            <div class="mood-emoji"><?= $m[0] ?></div>
                            <span><?= $m[1] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <textarea class="form-control mt-3" id="checkinNote" rows="2"
                    placeholder="Ghi chú (không bắt buộc)..."><?= $myCheckin['note'] ?? '' ?></textarea>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-lovex w-100" onclick="saveCheckin()">
                    <i class="bi bi-heart-fill"></i> Lưu cảm xúc
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>