<?php 
$pageTitle = 'Trợ giúp';
require_once __DIR__ . '/layouts/header.php'; 
?>

<header class="app-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?= base_url('index.php?action=home') ?>" class="btn-icon">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div class="logo">💬 Trợ giúp</div>
            <div style="width:42px;"></div>
        </div>
    </div>
</header>

<main class="container help-page">

    <div class="help-hero" data-aos="fade-up">
        <h2>💕 Trung tâm trợ giúp</h2>
        <p>Tìm câu trả lời cho mọi thắc mắc của bạn</p>
    </div>

    <h5 class="fw-bold mb-3" data-aos="fade-up">Câu hỏi thường gặp</h5>

    <div class="faq-item" data-aos="fade-up" onclick="toggleFAQ(this)">
        <div class="faq-question">
            <span>Làm sao để ghép đôi với người ấy?</span>
            <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-answer">
            Vào Cài đặt → nhấn "Tạo cặp đôi" → Hệ thống sẽ cấp cho bạn một mã ghép đôi (VD: ABC123). Gửi mã này cho người ấy. Người ấy vào Cài đặt → "Tham gia cặp đôi" → nhập mã → Xong!
        </div>
    </div>

    <div class="faq-item" data-aos="fade-up" onclick="toggleFAQ(this)">
        <div class="faq-question">
            <span>Check-in cảm xúc là gì?</span>
            <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-answer">
            Mỗi ngày, bạn có thể chọn 1 trong 6 cảm xúc (Vui vẻ, Yêu đời, Bình thường, Mệt mỏi, Buồn, Giận) để chia sẻ với người ấy. Người ấy sẽ thấy cảm xúc của bạn trên trang chủ → Hiểu được tâm trạng của bạn trong ngày.
        </div>
    </div>

    <div class="faq-item" data-aos="fade-up" onclick="toggleFAQ(this)">
        <div class="faq-question">
            <span>Timeline là gì?</span>
            <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-answer">
            Timeline là dòng thời gian tình yêu của hai bạn. Mọi kỷ niệm, thư tình sẽ được sắp xếp theo thứ tự thời gian. Bạn có thể xem lại hành trình từ khi bắt đầu đến hiện tại.
        </div>
    </div>

    <div class="faq-item" data-aos="fade-up" onclick="toggleFAQ(this)">
        <div class="faq-question">
            <span>Làm sao để thêm kỷ niệm?</span>
            <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-answer">
            Vào Trang chủ → Bấm "Kỷ niệm" → Nhấn nút + ở góc phải → Điền tiêu đề, ngày, mô tả → Upload ảnh (nếu muốn) → Bấm "Lưu kỷ niệm". Kỷ niệm sẽ xuất hiện trong danh sách và Timeline.
        </div>
    </div>

    <div class="faq-item" data-aos="fade-up" onclick="toggleFAQ(this)">
        <div class="faq-question">
            <span>Story hết hạn sau bao lâu?</span>
            <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-answer">
            Story sẽ tự động biến mất sau 24 giờ. Bạn có thể đăng story với text, ảnh, và chọn màu nền tùy thích. Người ấy sẽ xem được story của bạn.
        </div>
    </div>

    <div class="faq-item" data-aos="fade-up" onclick="toggleFAQ(this)">
        <div class="faq-question">
            <span>Làm sao để cài LoveX như app trên điện thoại?</span>
            <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-answer">
            <strong>Android:</strong> Mở Chrome → Bấm ⋮ → Chọn "Install app" hoặc "Add to Home screen" → Icon LoveX sẽ xuất hiện trên màn hình chính.<br><br>
            <strong>iOS:</strong> Mở Safari → Bấm nút Share (hình vuông có mũi tên) → Chọn "Add to Home Screen" → Đặt tên → Bấm "Add".
        </div>
    </div>

    <div class="faq-item" data-aos="fade-up" onclick="toggleFAQ(this)">
        <div class="faq-question">
            <span>Quên mật khẩu thì làm sao?</span>
            <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-answer">
            Hiện tại LoveX chưa có chức năng reset mật khẩu tự động. Vui lòng liên hệ hỗ trợ qua email support@lovex.vn để được giúp đỡ. Trong tương lai, chúng tôi sẽ bổ sung tính năng này.
        </div>
    </div>

    <div class="faq-item" data-aos="fade-up" onclick="toggleFAQ(this)">
        <div class="faq-question">
            <span>Dữ liệu của tôi có an toàn không?</span>
            <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-answer">
            Có! Tất cả dữ liệu của bạn được mã hóa qua HTTPS và lưu trữ an toàn. Chỉ có bạn và người ấy mới xem được kỷ niệm, thư tình và story. Chúng tôi không bao giờ chia sẻ dữ liệu của bạn cho bên thứ ba.
        </div>
    </div>

    <div class="help-hero mt-4" data-aos="fade-up" style="background: linear-gradient(135deg, #A855F7 0%, #FF4D79 100%);">
        <h5>💬 Vẫn cần hỗ trợ?</h5>
        <p>Liên hệ với chúng tôi qua:</p>
        <p style="margin-top: 12px;">
            <strong>📧 support@lovex.vn</strong><br>
            <strong>💬 fb.com/lovex.vn</strong>
        </p>
    </div>

</main>

<script>
function toggleFAQ(el) {
    el.classList.toggle('open');
}
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>