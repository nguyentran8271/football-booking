// Scroll Reveal Animation
document.addEventListener('DOMContentLoaded', function() {
    const options = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const callback = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    };

    const observer = new IntersectionObserver(callback, options);

    function observeElements(scope) {
        var selector = '.stat-card, .info-card, .field-card, .section-title, .about-card, .stat-item, .featured-post, .post-item';
        var elements = (scope || document).querySelectorAll(selector);
        elements.forEach(function(el) {
            observer.observe(el);
        });
    }

    // Observe lần đầu
    observeElements(document);

    // Expose để gọi sau khi inject AJAX
    window.observeNewPosts = function(container) {
        var items = container.querySelectorAll('.post-item');
        items.forEach(function(el) {
            el.classList.add('visible'); // Thêm thẳng visible vì đã trong viewport
        });
    };
});
