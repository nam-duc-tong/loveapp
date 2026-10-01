/* ============================================
   ONBOARDING TOUR + TOOLTIPS
   ============================================ */

// ============ TOUR STEPS ============
const tourSteps = [
    {
        element: '.hero-card',
        title: '💕 Đếm ngày yêu nhau',
        desc: 'Đây là trái tim của LoveX — hiển thị số ngày hai bạn đã bên nhau. Con số này sẽ tăng lên mỗi ngày!',
        position: 'below'
    },
    {
        element: '.quick-actions',
        title: '⚡ 5 tính năng chính',
        desc: 'Check-in cảm xúc, Thêm kỷ niệm, Đăng Story, Thả tim, và Viết thư tình — tất cả trong tầm tay bạn.',
        position: 'below'
    },
    {
        element: '.today-card',
        title: '📅 Hôm nay của hai bạn',
        desc: 'Xem cảm xúc của người ấy hôm nay. Chạm để check-in cảm xúc của bạn.',
        position: 'above'
    },
    {
        element: '.bottom-nav',
        title: '🧭 Menu điều hướng',
        desc: 'Chuyển nhanh giữa Trang chủ, Kỷ niệm, Timeline, Thư tình và Cài đặt.',
        position: 'above'
    },
    {
        element: '.app-header .btn-icon',
        title: '☰ Menu chính',
        desc: 'Mở menu để truy cập Story, Cảm xúc, Cài đặt, Dark Mode và nhiều hơn nữa!',
        position: 'below'
    }
];

let currentStep = 0;

// ============ START ONBOARDING ============
function startOnboarding() {
    // Ẩn welcome modal
    document.getElementById('onboardingWelcome')?.classList.remove('active');
    
    // Bắt đầu tour
    currentStep = 0;
    showTourStep();
}

// ============ SKIP ONBOARDING ============
function skipOnboarding() {
    document.getElementById('onboardingWelcome')?.classList.remove('active');
    document.getElementById('onboardingOverlay')?.classList.remove('active');
    document.getElementById('tourPopup')?.classList.remove('active');
    removeHighlights();
    
    // Gọi API đánh dấu đã xem
    markOnboardingDone();
}

// ============ SHOW TOUR STEP ============
function showTourStep() {
    const step = tourSteps[currentStep];
    if (!step) {
        endTour();
        return;
    }
    
    // Hiện overlay
    document.getElementById('onboardingOverlay')?.classList.add('active');
    
    // Xóa highlight cũ
    removeHighlights();
    
    // Tìm element
    const element = document.querySelector(step.element);
    if (!element) {
        console.warn('Không tìm thấy element:', step.element);
        currentStep++;
        showTourStep();
        return;
    }
    
    // Highlight element
    element.classList.add('tour-highlight');
    
    // Cập nhật popup
    const popup = document.getElementById('tourPopup');
    if (!popup) return;
    
    document.getElementById('tourStepBadge').textContent = `Bước ${currentStep + 1}/${tourSteps.length}`;
    document.getElementById('tourTitle').textContent = step.title;
    document.getElementById('tourDesc').textContent = step.desc;
    
    // Cập nhật progress dots
    const dotsContainer = document.getElementById('tourProgress');
    if (dotsContainer) {
        dotsContainer.innerHTML = tourSteps.map((_, i) => 
            `<div class="tour-dot ${i === currentStep ? 'active' : ''}"></div>`
        ).join('');
    }
    
    // Đổi text nút Next
    const nextBtn = document.getElementById('tourNextBtn');
    if (nextBtn) {
        nextBtn.textContent = currentStep === tourSteps.length - 1 ? 'Hoàn tất 🎉' : 'Tiếp theo →';
    }
    
    // Định vị popup
    popup.classList.remove('active', 'above', 'below');
    popup.classList.add(step.position === 'above' ? 'above' : 'below');
    
    // Tính toán vị trí
    setTimeout(() => {
        const rect = element.getBoundingClientRect();
        const popupRect = popup.getBoundingClientRect();
        
        let top, left;
        
        if (step.position === 'above') {
            top = rect.top - popupRect.height - 20;
        } else {
            top = rect.bottom + 20;
        }
        
        left = rect.left + (rect.width / 2) - (popupRect.width / 2);
        
        // Giới hạn trong màn hình
        left = Math.max(16, Math.min(left, window.innerWidth - popupRect.width - 16));
        top = Math.max(16, Math.min(top, window.innerHeight - popupRect.height - 16));
        
        popup.style.top = top + 'px';
        popup.style.left = left + 'px';
        popup.classList.add('active');
    }, 50);
}

// ============ NEXT STEP ============
function tourNext() {
    currentStep++;
    if (currentStep >= tourSteps.length) {
        endTour();
    } else {
        showTourStep();
    }
}

// ============ END TOUR ============
function endTour() {
    document.getElementById('onboardingOverlay')?.classList.remove('active');
    document.getElementById('tourPopup')?.classList.remove('active');
    removeHighlights();
    
    // Hiệu ứng chúc mừng
    if (typeof launchConfetti === 'function') {
        launchConfetti();
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: '🎉 Hoàn tất hướng dẫn!',
            html: `
                <p>Chúc mừng bạn đã sẵn sàng sử dụng LoveX!</p>
                <p style="font-size: 13px; color: #999; margin-top: 12px;">
                    Nếu cần trợ giúp, bấm nút <strong>?</strong> ở góc dưới màn hình nhé!
                </p>
            `,
            confirmButtonColor: '#FF4D79',
            confirmButtonText: 'Bắt đầu 💕'
        });
    }
    
    markOnboardingDone();
}

// ============ REMOVE HIGHLIGHTS ============
function removeHighlights() {
    document.querySelectorAll('.tour-highlight').forEach(el => {
        el.classList.remove('tour-highlight');
    });
}

// ============ MARK DONE ============
async function markOnboardingDone() {
    try {
        await fetch(BASE_URL + 'index.php?action=onboarding_done', {
            method: 'POST'
        });
    } catch (e) {
        console.log('Không thể lưu trạng thái onboarding');
    }
}

// ============ HELP MENU ============
function toggleHelpMenu() {
    document.getElementById('helpMenu')?.classList.toggle('active');
}

function closeHelpMenu() {
    document.getElementById('helpMenu')?.classList.remove('active');
}

function showQuickGuide() {
    closeHelpMenu();
    document.getElementById('onboardingWelcome')?.classList.add('active');
    document.getElementById('onboardingOverlay')?.classList.add('active');
}

function showFAQ() {
    closeHelpMenu();
    window.location.href = BASE_URL + 'index.php?action=help';
}

function showContact() {
    closeHelpMenu();
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '💬 Liên hệ hỗ trợ',
            html: `
                <p>Nếu bạn cần giúp đỡ, hãy liên hệ:</p>
                <p style="margin-top: 16px;">
                    <strong>Email:</strong> support@lovex.vn<br>
                    <strong>Facebook:</strong> fb.com/lovex.vn
                </p>
            `,
            confirmButtonColor: '#FF4D79',
            confirmButtonText: 'Đã hiểu'
        });
    }
}

// ============ CLOSE HELP MENU KHI CLICK RA NGOÀI ============
document.addEventListener('click', function(e) {
    const menu = document.getElementById('helpMenu');
    const fab = document.getElementById('helpFab');
    
    if (menu && fab && !menu.contains(e.target) && !fab.contains(e.target)) {
        menu.classList.remove('active');
    }
});

// ============ TOOLTIP ============
function initTooltips() {
    document.querySelectorAll('[data-tooltip]').forEach(el => {
        el.addEventListener('mouseenter', showTooltip);
        el.addEventListener('mouseleave', hideTooltip);
        el.addEventListener('click', toggleTooltip);
    });
}

function showTooltip(e) {
    const text = e.currentTarget.dataset.tooltip;
    if (!text) return;
    
    let tooltip = document.getElementById('globalTooltip');
    if (!tooltip) {
        tooltip = document.createElement('div');
        tooltip.id = 'globalTooltip';
        tooltip.className = 'tooltip-content';
        document.body.appendChild(tooltip);
    }
    
    tooltip.textContent = text;
    tooltip.classList.add('active');
    
    const rect = e.currentTarget.getBoundingClientRect();
    const tooltipRect = tooltip.getBoundingClientRect();
    
    let top = rect.top - tooltipRect.height - 10;
    let left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
    
    left = Math.max(10, Math.min(left, window.innerWidth - tooltipRect.width - 10));
    top = Math.max(10, top);
    
    tooltip.style.top = top + 'px';
    tooltip.style.left = left + 'px';
}

function hideTooltip() {
    document.getElementById('globalTooltip')?.classList.remove('active');
}

function toggleTooltip(e) {
    e.stopPropagation();
    const tooltip = document.getElementById('globalTooltip');
    if (tooltip?.classList.contains('active')) {
        hideTooltip();
    } else {
        showTooltip(e);
    }
}

// ============ FAQ TOGGLE (Trang help) ============
function toggleFAQ(element) {
    const item = element.closest('.faq-item');
    item.classList.toggle('open');
}

// ============ AUTO INIT ============
document.addEventListener('DOMContentLoaded', () => {
    initTooltips();
});