// For Owners Page Animations
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15
    };

    // Observer callback
    const observerCallback = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal');
            }
        });
    };

    const observer = new IntersectionObserver(observerCallback, observerOptions);

    // Observe content rows (sections with image + text)
    const contentRows = document.querySelectorAll('.content-row');
    contentRows.forEach(row => {
        observer.observe(row);

        // Add typing effect to paragraphs
        const paragraphs = row.querySelectorAll('.content-text p');
        paragraphs.forEach(p => {
            const text = p.textContent;
            p.setAttribute('data-text', text);
            p.textContent = '';

            // Start typing when row is revealed
            const typingObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !p.classList.contains('typed')) {
                        p.classList.add('typed');
                        typeText(p, text, 30); // 30ms per character
                        typingObserver.unobserve(p);
                    }
                });
            }, observerOptions);

            typingObserver.observe(row);
        });
    });

    // Observe benefit cards with staggered delay
    const benefitCards = document.querySelectorAll('.benefit-card');
    benefitCards.forEach((card, index) => {
        card.style.transitionDelay = `${0.1 * index}s`;
        observer.observe(card);
    });

    // Observe step cards with staggered delay
    const stepCards = document.querySelectorAll('.step-card');
    stepCards.forEach((card, index) => {
        card.style.transitionDelay = `${0.15 * index}s`;
        observer.observe(card);
    });

    // Observe stats grid
    const statsGrid = document.querySelector('.stats-grid');
    if (statsGrid) {
        observer.observe(statsGrid);
    }
});

// Typing effect function
function typeText(element, text, speed) {
    let index = 0;
    const words = text.split(' ');

    function typeWord() {
        if (index < words.length) {
            element.textContent += (index > 0 ? ' ' : '') + words[index];
            index++;
            setTimeout(typeWord, speed);
        }
    }

    // Start typing after a small delay
    setTimeout(typeWord, 300);
}
