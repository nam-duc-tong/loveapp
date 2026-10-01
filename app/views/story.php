<?php 
$pageTitle = 'Story';
$activeNav = 'story';

// ============ KHAI BÁO BIẾN AN TOÀN ============
/** @var array|null $partner */
$partner = $partner ?? null;

/** @var array $grouped */
$grouped = $grouped ?? [];

/** @var array $myStories */
$myStories = $grouped[current_user_id()] ?? [];

/** @var array $partnerStories */
$partnerStories = ($partner && isset($partner['id'])) ? ($grouped[$partner['id']] ?? []) : [];

// ============ TÍNH CÁC BIẾN PHỤ ============
$myInitial      = strtoupper(mb_substr($_SESSION['display_name'] ?? 'U', 0, 1));
$partnerName    = $partner['display_name'] ?? 'Người ấy';
$partnerInitial = strtoupper(mb_substr($partnerName, 0, 1));

require_once __DIR__ . '/layouts/header.php'; 
?>

<header class="app-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?= base_url('index.php?action=home') ?>" class="btn-icon">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="logo">⚡ Story</div>
            <button class="btn-icon" onclick="openStoryModal()">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>
    </div>
</header>

<main class="container pb-5">

    <!-- Story của tôi -->
    <div class="section-header">
        <h5>Story của bạn</h5>
        <small><?= count($myStories) ?> story</small>
    </div>

    <div class="story-row" data-aos="fade-up">
        <!-- Nút tạo story mới -->
        <div class="story-item" onclick="openStoryModal()">
            <div class="story-ring story-ring-add">
                <div class="story-avatar">
                    <i class="bi bi-plus-lg"></i>
                </div>
            </div>
            <span>Tạo mới</span>
        </div>

        <?php foreach ($myStories as $s): ?>
            <div class="story-item" onclick="viewStory(<?= $s['id'] ?>, '<?= htmlspecialchars($s['content'], ENT_QUOTES) ?>', '<?= $s['image_url'] ? base_url($s['image_url']) : '' ?>', '<?= $s['background_color'] ?>', '<?= date('H:i d/m', strtotime($s['created_at'])) ?>', '<?= htmlspecialchars($_SESSION['display_name'], ENT_QUOTES) ?>')">
                <div class="story-ring active">
                    <div class="story-avatar">
                        <?php if ($s['image_url']): ?>
                            <img src="<?= base_url($s['image_url']) ?>" alt="">
                        <?php else: ?>
                            <span style="background: <?= $s['background_color'] ?>;"><?= $myInitial ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <span><?= date('H:i', strtotime($s['created_at'])) ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Story của partner -->
    <?php if (!empty($partnerStories)): ?>
        <div class="section-header mt-4">
            <h5>Story của <?= $partner ? htmlspecialchars($partner['display_name']) : 'người ấy' ?></h5>
            <small><?= count($partnerStories) ?> story</small>
        </div>

        <div class="story-row" data-aos="fade-up">
            <?php foreach ($partnerStories as $s): ?>
                <div class="story-item" onclick="viewStory(<?= $s['id'] ?>, '<?= htmlspecialchars($s['content'], ENT_QUOTES) ?>', '<?= $s['image_url'] ? base_url($s['image_url']) : '' ?>', '<?= $s['background_color'] ?>', '<?= date('H:i d/m', strtotime($s['created_at'])) ?>', '<?= htmlspecialchars($partner['display_name'], ENT_QUOTES) ?>')">
                    <div class="story-ring active">
                        <div class="story-avatar">
                            <?php if ($s['image_url']): ?>
                                <img src="<?= base_url($s['image_url']) ?>" alt="">
                            <?php else: ?>
                                <span style="background: <?= $s['background_color'] ?>;">
                                    <?= strtoupper(mb_substr($partner['display_name'], 0, 1)) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <span><?= date('H:i', strtotime($s['created_at'])) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state text-center py-5" data-aos="fade-up">
            <i class="bi bi-lightning-charge"></i>
            <h5 class="mt-3">Người ấy chưa đăng story</h5>
            <p class="text-muted">Story sẽ tự động biến mất sau 24 giờ</p>
        </div>
    <?php endif; ?>

</main>

<!-- ============ MODAL TẠO STORY ============ -->
<div class="modal fade" id="storyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content checkin-modal">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">⚡ Đăng story</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nội dung</label>
                    <textarea id="storyContent" class="form-control" rows="3" 
                              placeholder="Hôm nay bạn muốn chia sẻ điều gì?" 
                              maxlength="200"></textarea>
                    <small class="text-muted"><span id="charCount">0</span>/200 ký tự</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ảnh (tùy chọn)</label>
                    <input type="file" id="storyImage" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label">Màu nền</label>
                    <div class="color-picker">
                        <?php 
                        $colors = ['#FF4D79', '#FF80AB', '#A855F7', '#4D9FFF', '#FFB84D', '#4CAF50', '#333333'];
                        foreach ($colors as $i => $c): 
                        ?>
                            <div class="color-option <?= $i === 0 ? 'selected' : '' ?>" 
                                 data-color="<?= $c ?>" 
                                 style="background: <?= $c ?>"
                                 onclick="selectColor(this)"></div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Preview -->
                <div class="mb-3">
                    <label class="form-label">Xem trước</label>
                    <div class="story-preview" id="storyPreview" style="background: #FF4D79;">
                        <p id="previewText">Hôm nay bạn muốn chia sẻ điều gì?</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-lovex w-100" onclick="saveStory()">
                    <i class="bi bi-send"></i> Đăng story (24h)
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL XEM STORY ============ -->
<div class="modal fade" id="viewStoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content story-viewer" id="storyViewer">
            <div class="story-progress">
                <div class="story-progress-bar" id="storyProgressBar"></div>
            </div>
            <div class="story-header">
                <div class="story-user">
                    <div class="story-user-avatar" id="viewStoryInitial"></div>
                    <div>
                        <strong id="viewStoryName"></strong>
                        <small id="viewStoryTime"></small>
                    </div>
                </div>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="story-body" id="viewStoryBody">
                <p id="viewStoryContent"></p>
                <img id="viewStoryImage" style="display:none;">
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>

<script>
let selectedColor = '#FF4D79';

// Character count
document.getElementById('storyContent')?.addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length;
    document.getElementById('previewText').textContent = this.value || 'Hôm nay bạn muốn chia sẻ điều gì?';
});

// Chọn màu
function selectColor(el) {
    document.querySelectorAll('.color-option').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    selectedColor = el.dataset.color;
    document.getElementById('storyPreview').style.background = selectedColor;
}

// Mở modal tạo story
function openStoryModal() {
    new bootstrap.Modal(document.getElementById('storyModal')).show();
}

// Lưu story
async function saveStory() {
    const content = document.getElementById('storyContent').value.trim();
    const image = document.getElementById('storyImage').files[0];

    if (!content && !image) {
        toast('Vui lòng nhập nội dung hoặc chọn ảnh!', 'warning');
        return;
    }

    const formData = new FormData();
    formData.append('content', content);
    formData.append('bg_color', selectedColor);
    if (image) formData.append('image', image);

    const res = await fetch(BASE_URL + 'index.php?action=story_create', {
        method: 'POST', body: formData
    });
    const data = await res.json();

    if (data.success) {
        Swal.fire({
            icon: 'success',
            title: 'Đã đăng story! ⚡',
            text: 'Story sẽ tự động biến mất sau 24 giờ',
            confirmButtonColor: '#FF4D79',
            timer: 1500,
            showConfirmButton: false
        }).then(() => location.reload());
    } else {
        toast(data.message || 'Có lỗi xảy ra!', 'error');
    }
}

// Xem story
function viewStory(id, content, imageUrl, bgColor, time, userName) {
    document.getElementById('viewStoryContent').textContent = content;
    document.getElementById('viewStoryName').textContent = userName;
    document.getElementById('viewStoryTime').textContent = time;
    document.getElementById('viewStoryInitial').textContent = userName.charAt(0).toUpperCase();
    document.getElementById('storyViewer').style.background = bgColor;

    const img = document.getElementById('viewStoryImage');
    if (imageUrl) {
        img.src = imageUrl;
        img.style.display = 'block';
    } else {
        img.style.display = 'none';
    }

    new bootstrap.Modal(document.getElementById('viewStoryModal')).show();

    // Animation progress bar
    const bar = document.getElementById('storyProgressBar');
    bar.style.animation = 'none';
    bar.offsetHeight;
    bar.style.animation = 'storyProgress 5s linear forwards';
}
</script>