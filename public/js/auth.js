// ============ FLOATING HEARTS BACKGROUND ============
function initAuthHearts() {
    const container = document.querySelector('.auth-hearts-bg');
    if (!container) return;
    
    const hearts = ['❤️', '💕', '💖', '💗', '💝', '🩷', '💘'];
    for (let i = 0; i < 15; i++) {
        const heart = document.createElement('div');
        heart.className = 'auth-heart-bg';
        heart.innerHTML = hearts[Math.floor(Math.random() * hearts.length)];
        heart.style.left = Math.random() * 100 + '%';
        heart.style.fontSize = (16 + Math.random() * 24) + 'px';
        heart.style.animationDelay = Math.random() * 12 + 's';
        heart.style.animationDuration = (8 + Math.random() * 8) + 's';
        container.appendChild(heart);
    }
}

// ============ LOGIN SUCCESS ANIMATION ============

/* ============================================
   LOGIN SUCCESS - MODERN ANIMATION
   ============================================ */

// Tạo particles nền cho overlay
/* ============================================
   LOGIN SUCCESS - MODERN ANIMATION
   ============================================ */

/* ============ LOGIN SUCCESS ANIMATION ============ */

function initLoginParticles() {
    const container = document.getElementById('lsParticles');
    if (!container) return;
    if (container.dataset.init === '1') return;
    container.dataset.init = '1';
    for (let i = 0; i < 25; i++) {
        const p = document.createElement('div');
        p.className = 'ls-particle';
        const size = 4 + Math.random() * 8;
        p.style.width = size + 'px';
        p.style.height = size + 'px';
        p.style.left = Math.random() * 100 + '%';
        p.style.top = Math.random() * 100 + '%';
        p.style.animationDelay = (Math.random() * 5) + 's';
        p.style.animationDuration = (4 + Math.random() * 4) + 's';
        container.appendChild(p);
    }
}

function createLoginSparkles() {
    const emojis = ['✨', '⭐', '💫', '🌟', '💕', '💖'];
    const cx = window.innerWidth / 2;
    const cy = window.innerHeight / 2 - 100;
    for (let i = 0; i < 18; i++) {
        setTimeout(() => {
            const s = document.createElement('div');
            s.className = 'ls-sparkle';
            s.innerHTML = emojis[Math.floor(Math.random() * emojis.length)];
            s.style.left = cx + 'px';
            s.style.top = cy + 'px';
            const a = (Math.PI * 2 * i / 18) + (Math.random() - 0.5) * 0.6;
            const d = 80 + Math.random() * 180;
            s.style.setProperty('--tx', (Math.cos(a) * d) + 'px');
            s.style.setProperty('--ty', (Math.sin(a) * d) + 'px');
            document.body.appendChild(s);
            setTimeout(() => s.remove(), 2000);
        }, i * 40);
    }
}

function animateProgress() {
    const bar = document.getElementById('lsProgressBar');
    const text = document.getElementById('lsProgressText');
    if (!bar || !text) return;

    const duration = 2000; // 2 giây
    const startTime = performance.now();

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        let progress = (elapsed / duration) * 100;

        if (progress >= 100) {
            progress = 100;
            bar.style.width = '100%';
            text.textContent = '100%';
            
            // Kích hoạt callback
            setTimeout(() => {
                if (typeof onProgressComplete === 'function') {
                    onProgressComplete();
                }
            }, 200);
            return;
        }

        bar.style.width = progress + '%';
        text.textContent = Math.floor(progress) + '%';
        requestAnimationFrame(update);
    }

    requestAnimationFrame(update);
}

function playLoginSuccess(callback) {
    const overlay = document.getElementById('loginSuccessOverlay');
    if (!overlay) {
        if (callback) callback();
        return;
    }
    initLoginParticles();
    overlay.classList.add('active');
    setTimeout(() => createLoginSparkles(), 900);
    setTimeout(() => animateProgress(), 1300);
    setTimeout(() => { if (callback) callback(); }, 2800);
}
// ============ LOGOUT HEART BREAK ANIMATION ============
function playLogoutBreak() {
    const hearts = ['💔', '💕', '❤️', '💗'];
    const startX = window.innerWidth / 2;
    const startY = window.innerHeight / 2 - 100;
    
    for (let i = 0; i < 20; i++) {
        const fragment = document.createElement('div');
        fragment.className = 'heart-fragment';
        fragment.innerHTML = hearts[Math.floor(Math.random() * hearts.length)];
        fragment.style.left = startX + 'px';
        fragment.style.top = startY + 'px';
        fragment.style.setProperty('--tx', (Math.random() - 0.5) * 600 + 'px');
        fragment.style.setProperty('--ty', (Math.random() - 0.8) * 600 + 'px');
        fragment.style.animationDelay = (i * 50) + 'ms';
        document.body.appendChild(fragment);
        
        setTimeout(() => fragment.remove(), 3500);
    }
}

// ============ AUTO INIT ============
document.addEventListener('DOMContentLoaded', () => {
    initAuthHearts();
    
    // Nếu ở trang logout → phát animation
    if (document.body.classList.contains('logout-page')) {
        setTimeout(playLogoutBreak, 500);
        startLogoutCountdown(3);
    }
});

// ============ LOGOUT COUNTDOWN ============
function startLogoutCountdown(seconds) {
    const el = document.getElementById('countdownNumber');
    if (!el) return;
    
    let remaining = seconds;
    const interval = setInterval(() => {
        remaining--;
        if (el) el.textContent = remaining;
        if (remaining <= 0) {
            clearInterval(interval);
            window.location.href = BASE_URL + 'index.php?action=login';
        }
    }, 1000);
}
/* ============================================
   LOGOUT PAGE - MODERN ANIMATION
   ============================================ */

// Tạo particles nền
function initLogoutParticles() {
    const container = document.getElementById('logoutParticles');
    if (!container) return;
    
    for (let i = 0; i < 30; i++) {
        const p = document.createElement('div');
        p.className = 'logout-particle';
        p.style.left = Math.random() * 100 + '%';
        p.style.top = Math.random() * 100 + '%';
        p.style.animationDelay = (Math.random() * 6) + 's';
        p.style.animationDuration = (4 + Math.random() * 4) + 's';
        const size = 4 + Math.random() * 6;
        p.style.width = size + 'px';
        p.style.height = size + 'px';
        container.appendChild(p);
    }
}

// Tạo mảnh vỡ bay ra khi tim vỡ
function createHeartFragments() {
    const centerX = window.innerWidth / 2;
    const centerY = window.innerHeight / 2 - 80;
    const colors = ['#FF4D79', '#FF80AB', '#A855F7', '#FF6B9D', '#EC4899'];
    
    for (let i = 0; i < 40; i++) {
        const fragment = document.createElement('div');
        fragment.className = 'heart-fragment';
        const size = 6 + Math.random() * 12;
        fragment.style.width = size + 'px';
        fragment.style.height = size + 'px';
        fragment.style.left = centerX + 'px';
        fragment.style.top = centerY + 'px';
        fragment.style.background = `linear-gradient(135deg, ${colors[i % colors.length]}, ${colors[(i + 2) % colors.length]})`;
        
        // Góc bay: 360 độ, mạnh hơn ở hướng 2 bên
        const angle = (Math.PI * 2 * i / 40) + (Math.random() - 0.5) * 0.5;
        const distance = 150 + Math.random() * 250;
        const tx = Math.cos(angle) * distance;
        const ty = Math.sin(angle) * distance;
        
        fragment.style.setProperty('--tx', tx + 'px');
        fragment.style.setProperty('--ty', ty + 'px');
        fragment.style.setProperty('--rot', (Math.random() * 720 - 360) + 'deg');
        fragment.style.animationDelay = (Math.random() * 0.3) + 's';
        
        document.body.appendChild(fragment);
        setTimeout(() => fragment.remove(), 3000);
    }
}

// Tạo sparkles lấp lánh
function createSparkles() {
    const emojis = ['✨', '⭐', '💫', '🌟'];
    const centerX = window.innerWidth / 2;
    const centerY = window.innerHeight / 2 - 80;
    
    for (let i = 0; i < 15; i++) {
        const sparkle = document.createElement('div');
        sparkle.className = 'sparkle';
        sparkle.innerHTML = emojis[Math.floor(Math.random() * emojis.length)];
        sparkle.style.left = centerX + 'px';
        sparkle.style.top = centerY + 'px';
        
        const angle = Math.PI * 2 * i / 15 + (Math.random() - 0.5) * 0.5;
        const distance = 80 + Math.random() * 150;
        sparkle.style.setProperty('--tx', (Math.cos(angle) * distance) + 'px');
        sparkle.style.setProperty('--ty', (Math.sin(angle) * distance) + 'px');
        sparkle.style.animationDelay = (0.4 + Math.random() * 0.3) + 's';
        
        document.body.appendChild(sparkle);
        setTimeout(() => sparkle.remove(), 3000);
    }
}

// Timeline animation chính
function playLogoutAnimation() {
    const page = document.querySelector('.logout-page');
    if (!page) return;
    
    // Bước 1: Sau 0.8s - bắt đầu nứt
    setTimeout(() => {
        page.classList.add('cracking');
    }, 800);
    
    // Bước 2: Sau 1.6s - tim vỡ
    setTimeout(() => {
        page.classList.remove('cracking');
        page.classList.add('broken');
        createHeartFragments();
        createSparkles();
        playVibration();
    }, 1600);
    
    // Bước 3: Sau 2.2s - hiện chữ "Hẹn gặp lại"
    setTimeout(() => {
        document.getElementById('goodbyeTitle')?.classList.add('show');
    }, 2200);
}

// Rung nhẹ khi vỡ (nếu thiết bị hỗ trợ)
function playVibration() {
    if ('vibrate' in navigator) {
        navigator.vibrate([50, 30, 100]);
    }
}

// Đếm ngược
function startCountdown(seconds) {
    const el = document.getElementById('countdownNumber');
    if (!el) return;
    
    let remaining = seconds;
    const interval = setInterval(() => {
        remaining--;
        if (el) el.textContent = remaining;
        if (remaining <= 0) {
            clearInterval(interval);
            window.location.href = BASE_URL + 'index.php?action=login';
        }
    }, 1000);
}

// ============ AUTO INIT ============
document.addEventListener('DOMContentLoaded', () => {
    initAuthHearts();
    initLogoutParticles();
    
    // Nếu ở trang logout → phát animation
    if (document.querySelector('.logout-page')) {
        playLogoutAnimation();
        startCountdown(8); // 8 giây
    }
});