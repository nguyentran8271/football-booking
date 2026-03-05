// Reviews Page Scroll Reveal Animations
document.addEventListener('DOMContentLoaded', function() {
    // Intersection Observer options
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1 // Trigger when 10% of element is visible
    };

    // Callback function for intersection observer
    const observerCallback = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal');
            }
        });
    };

    // Create observer
    const observer = new IntersectionObserver(observerCallback, observerOptions);

    // Observe review overview
    const reviewOverview = document.querySelector('.review-overview');
    if (reviewOverview) {
        observer.observe(reviewOverview);
    }

    // Observe detailed ratings
    const detailedRatings = document.querySelector('.detailed-ratings');
    if (detailedRatings) {
        observer.observe(detailedRatings);
    }

    // Observe filters
    const reviewFilters = document.querySelector('.review-filters');
    if (reviewFilters) {
        observer.observe(reviewFilters);
    }

    // Observe review cards with staggered animation
    const reviewCards = document.querySelectorAll('.review-card');
    reviewCards.forEach((card, index) => {
        // Add staggered delay
        card.style.transitionDelay = `${0.1 * (index % 4)}s`;
        observer.observe(card);
    });

    // Animate rating bars when they come into view
    const ratingBars = document.querySelectorAll('.rating-bar');
    if (ratingBars.length > 0) {
        const barsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const bar = entry.target;
                    const width = bar.style.width;
                    bar.style.width = '0';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 300);
                    barsObserver.unobserve(bar);
                }
            });
        }, observerOptions);

        ratingBars.forEach(bar => barsObserver.observe(bar));
    }

    // Animate load more button
    const loadMoreContainer = document.querySelector('.load-more-container');
    if (loadMoreContainer) {
        loadMoreContainer.style.opacity = '0';
        loadMoreContainer.style.transform = 'translateY(20px)';
        loadMoreContainer.style.transition = 'all 0.6s ease-out';

        const loadMoreObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        loadMoreObserver.observe(loadMoreContainer);
    }
});
