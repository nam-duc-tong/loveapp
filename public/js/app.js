// ============ CHECK-IN ============
let selectedMood = null;

function openCheckinModal() {
    const modal = new bootstrap.Modal(document.getElementById('checkinModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', () => {
    const moodItems = document.querySelectorAll('.mood-item');
    moodItems.forEach(item => {
        item.addEventListener('click', () => {
            moodItems.forEach(m => m.classList.remove('selected'));
            item.classList.add('selected');
            selectedMood = item.dataset.mood;
        });
    });
});

async function saveCheckin() {
    if (!selectedMood) {
        Swal.fire({
            icon: 'warning',
            title: 'Chưa chọn cảm xúc',
            text: 'Vui lòng chọn cảm xúc của bạn!',
            confirmButtonColor: '#FF4D79'
        });
        return;
    }

    const note = document.getElementById('checkinNote').value;
    const formData = new FormData();
    formData.append('mood', selectedMood);
    formData.append('note', note);

    try {
        const res = await fetch(BASE_URL + 'index.php?action=checkin_save', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Đã lưu cảm xúc!',
                text: 'Người ấy sẽ sớm biết bạn thế nào 💕',
                confirmButtonColor: '#FF4D79',
                timer: 2000,
                showConfirmButton: false
            }).then(() => location.reload());
        } else {
            toast('Có lỗi xảy ra!', 'error');
        }
    } catch (e) {
        toast('Lỗi kết nối!', 'error');
    }
}

// ============ MEMORY ============
function openMemoryModal() {
    const modal = new bootstrap.Modal(document.getElementById('memoryModal'));
    modal.show();
}

async function saveMemory() {
    const form = document.getElementById('memoryForm');
    const formData = new FormData(form);

    if (!formData.get('title')) {
        toast('Vui lòng nhập tiêu đề!', 'warning');
        return;
    }

    try {
        const res = await fetch(BASE_URL + 'index.php?action=memory_create', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Đã lưu kỷ niệm!',
                confirmButtonColor: '#FF4D79',
                timer: 1500,
                showConfirmButton: false
            }).then(() => location.reload());
        } else {
            toast('Có lỗi xảy ra!', 'error');
        }
    } catch (e) {
        toast('Lỗi kết nối!', 'error');
    }
}

// ============ SEND HEART ============
function sendHeart() {
    // Hiệu ứng bay tim
    for (let i = 0; i < 12; i++) {
        setTimeout(() => createFloatingHeart(), i * 100);
    }
    toast('Đã gửi tim cho người ấy! 💕', 'success');
}

function createFloatingHeart() {
    const container = document.getElementById('heartContainer');
    const heart = document.createElement('div');
    heart.className = 'floating-heart';
    heart.innerHTML = '❤️';
    heart.style.left = Math.random() * 100 + '%';
    heart.style.fontSize = (20 + Math.random() * 20) + 'px';
    heart.style.animationDuration = (2 + Math.random() * 2) + 's';
    container.appendChild(heart);
    setTimeout(() => heart.remove(), 4000);
}

// ============ TOAST ============
function toast(message, icon = 'info') {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icon,
        title: message,
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
    });
}
