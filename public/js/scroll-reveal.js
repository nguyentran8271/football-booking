// Scroll Reveal Animation
document.addEventListener('DOMContentLoaded', function() {
    // Lấy tất cả các phần tử cần animate (chỉ trang chủ)
    const elements = document.querySelectorAll('.stat-card, .info-card, .field-card, .section-title, .about-card, .stat-item, .featured-post, .post-item');

    // Intersection Observer options
    const options = {
        root: null,
        rootMargin: '0px',
        threshold: 0.2 // Hiện khi 20% phần tử vào viewport
    };

    // Callback khi phần tử vào viewport
    const callback = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Thêm class 'visible' để trigger animation
                entry.target.classList.add('visible');
                // Unobserve để animation chỉ chạy 1 lần
                observer.unobserve(entry.target);
            }
        });
    };

    // Tạo observer
    const observer = new IntersectionObserver(callback, options);

    // Observe tất cả các phần tử
    elements.forEach(element => {
        observer.observe(element);
    });
});
