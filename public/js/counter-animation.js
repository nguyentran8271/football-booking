// Counter Animation for Stats
function animateCounter(element, target, duration = 2000) {
    const start = 0;
    const increment = target / (duration / 16); // 60fps
    let current = start;

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            // Format number with commas if needed
            const displayValue = Math.floor(current);
            if (displayValue >= 1000) {
                element.textContent = displayValue.toLocaleString('vi-VN');
            } else {
                element.textContent = displayValue;
            }
        }
    }, 16);
}

// Initialize counter animation when stats come into view
document.addEventListener('DOMContentLoaded', function() {
    const statNumbers = document.querySelectorAll('.stat-number');

    if (statNumbers.length === 0) return;

    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.3
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                entry.target.classList.add('animated');

                // Get the target value from data attribute or text content
                const targetText = entry.target.getAttribute('data-target') || entry.target.textContent;

                // Remove any non-numeric characters except + and numbers
                const hasPlus = targetText.includes('+');
                const targetValue = parseInt(targetText.replace(/[^0-9]/g, ''));

                if (!isNaN(targetValue)) {
                    // Set initial value to 0
                    entry.target.textContent = '0';

                    // Start animation
                    setTimeout(() => {
                        animateCounter(entry.target, targetValue, 2000);

                        // Add + sign back after animation if needed
                        if (hasPlus) {
                            setTimeout(() => {
                                entry.target.textContent = entry.target.textContent + '+';
                            }, 2000);
                        }
                    }, 100);
                }

                // Stop observing after animation starts
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all stat numbers
    statNumbers.forEach(stat => {
        // Store original value in data attribute
        stat.setAttribute('data-target', stat.textContent);
        observer.observe(stat);
    });
});
