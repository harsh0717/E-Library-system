/**
 * BookVerse 3D Glassmorphic Engine
 * Instant Live Filtering, AJAX Voting, Interactive Star Ratings & UI Micro-Interactions
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Ambient Background Atmosphere
    initAmbientScene();

    // 2. Back To Top Button
    initBackToTop();

    // 3. Expandable Description Toggles (Public & Admin)
    initShowMore();

    // 4. Live Search Input Listener
    initLiveSearch();

    // 5. Star Rating Selector (Feedback Page)
    initStarRating();

    // 6. Auto-dismiss flash alerts
    initFlashAlerts();
    // 7. Theme Engine
    initTheme();
});

/**
 * Injects glowing ambient orbs into background
 */
function initAmbientScene() {
    if (!document.querySelector('.ambient-scene')) {
        const scene = document.createElement('div');
        scene.className = 'ambient-scene';
        scene.innerHTML = `
            <div class="ambient-orb ambient-orb-1"></div>
            <div class="ambient-orb ambient-orb-2"></div>
            <div class="ambient-orb ambient-orb-3"></div>
        `;
        document.body.prepend(scene);
    }
}

/**
 * Instant Real-Time Search as user types
 */
function initLiveSearch() {
    const searchInput = document.getElementById('liveSearchInput');
    if (!searchInput) return;

    searchInput.addEventListener('input', () => {
        applyFilters();
    });
}

/**
 * Filter books by category tab pill
 */
let activeCategory = 'all';

function filterByCategory(catId) {
    activeCategory = String(catId);

    // Update active pill button
    const pillButtons = document.querySelectorAll('.cat-filter-pill');
    pillButtons.forEach(btn => {
        if (btn.getAttribute('data-category-id') === activeCategory) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    // Sync header dropdown if present
    const headerSelect = document.getElementById('headerCategorySelect');
    if (headerSelect) {
        headerSelect.value = activeCategory;
    }

    applyFilters();
}
window.filterByCategory = filterByCategory;

/**
 * Core Filter Logic combining Search Query + Category Selection
 */
function applyFilters() {
    const searchInput = document.getElementById('liveSearchInput');
    const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
    const cards = document.querySelectorAll('#booksGrid .card-3d-wrap');
    const noResults = document.getElementById('noResultsMsg');
    const counter = document.getElementById('liveBookCounter');

    if (!cards.length) return;

    let visibleCount = 0;

    cards.forEach(card => {
        const catId = String(card.getAttribute('data-category-id') || '');
        const title = (card.getAttribute('data-title') || '').toLowerCase();
        const author = (card.getAttribute('data-author') || '').toLowerCase();
        const desc = (card.getAttribute('data-desc') || '').toLowerCase();

        const matchesCategory = (activeCategory === 'all' || catId === activeCategory);
        const matchesQuery = (!query || title.includes(query) || author.includes(query) || desc.includes(query));

        if (matchesCategory && matchesQuery) {
            card.classList.remove('hidden');
            card.style.display = '';
            visibleCount++;
        } else {
            card.classList.add('hidden');
            card.style.display = 'none';
        }
    });

    // Update Counter
    if (counter) {
        counter.innerHTML = `Displaying <strong>${visibleCount}</strong> of ${cards.length} books`;
    }

    // Toggle No Results Message
    if (noResults) {
        noResults.style.display = (visibleCount === 0) ? 'block' : 'none';
    }
}
window.applyFilters = applyFilters;

/**
 * Reset all active filters
 */
function resetFilters() {
    const searchInput = document.getElementById('liveSearchInput');
    if (searchInput) searchInput.value = '';
    filterByCategory('all');
}
window.resetFilters = resetFilters;

/**
 * Interactive 5-Star Rating Widget on Feedback Page
 */
const ratingDescriptions = {
    1: '1 Star — Poor Experience',
    2: '2 Stars — Fair / Needs Improvement',
    3: '3 Stars — Good / Average',
    4: '4 Stars — Very Good & Useful',
    5: '5 Stars — Outstanding Experience!'
};

function initStarRating() {
    const starBox = document.getElementById('starRatingBox') || document.querySelector('.star-rating-box-interactive') || document.querySelector('.star-rating-box');
    if (!starBox) return;

    const stars = starBox.querySelectorAll('i');
    const ratingInput = document.getElementById('ratingInput') || document.getElementById('rating-value');
    const ratingLabel = document.getElementById('ratingLabel');

    let currentSelected = ratingInput ? parseInt(ratingInput.value) || 5 : 5;

    function renderStars(val, isHover = false) {
        stars.forEach(s => {
            const starVal = parseInt(s.getAttribute('data-value') || s.getAttribute('data-rate') || 0);
            if (isHover) {
                s.classList.toggle('hovered', starVal <= val);
            } else {
                s.classList.toggle('selected', starVal <= val);
            }
        });

        if (ratingLabel && ratingDescriptions[val]) {
            ratingLabel.textContent = ratingDescriptions[val];
        }
    }

    renderStars(currentSelected, false);

    stars.forEach(star => {
        const starVal = parseInt(star.getAttribute('data-value') || star.getAttribute('data-rate') || 0);

        star.addEventListener('mouseenter', () => {
            renderStars(starVal, true);
        });

        star.addEventListener('mouseleave', () => {
            stars.forEach(s => s.classList.remove('hovered'));
            renderStars(currentSelected, false);
        });

        star.addEventListener('click', () => {
            currentSelected = starVal;
            if (ratingInput) ratingInput.value = starVal;
            renderStars(currentSelected, false);
        });
    });
}

/**
 * Auto dismiss flash alert notifications
 */
function initFlashAlerts() {
    const toasts = document.querySelectorAll('.flash-toast-wrap, .flash-glass');
    toasts.forEach(toast => {
        setTimeout(() => {
            toast.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    });
}

/**
 * Expandable Show More/Show Less for book descriptions
 */
function initShowMore() {
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.show-more-btn, .admin-desc-toggle-btn');
        if (!btn) return;

        e.preventDefault();

        // 1. Homepage / Category Book Cards
        if (btn.classList.contains('show-more-btn')) {
            const bookId = btn.getAttribute('data-book-id');
            const descWrap = document.getElementById(`desc-${bookId}`);
            if (!descWrap) return;

            const truncated = descWrap.querySelector('.truncated-content');
            const full = descWrap.querySelector('.full-content');

            if (full && full.style.display === 'none') {
                full.style.display = 'inline';
                if (truncated) truncated.style.display = 'none';
                btn.innerHTML = '<i class="fas fa-chevron-up"></i> Show Less';
            } else if (full) {
                full.style.display = 'none';
                if (truncated) truncated.style.display = 'inline';
                btn.innerHTML = '<i class="fas fa-chevron-down"></i> Show More';
            }
        }

        // 2. Admin Table Description Toggle
        if (btn.classList.contains('admin-desc-toggle-btn')) {
            const bookId = btn.getAttribute('data-book-id');
            const fullDesc = document.getElementById(`admin-desc-full-${bookId}`);
            const isShowing = fullDesc && fullDesc.style.display !== 'none';

            if (fullDesc) {
                fullDesc.style.display = isShowing ? 'none' : 'block';
                btn.innerHTML = isShowing 
                    ? '<i class="fas fa-chevron-down"></i> Expand' 
                    : '<i class="fas fa-chevron-up"></i> Collapse';
            }
        }
    });
}

/**
 * Smooth Back to Top button
 */
function initBackToTop() {
    const btn = document.getElementById('backToTop');
    if (!btn) return;

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            btn.classList.add('show');
        } else {
            btn.classList.remove('show');
        }
    });

    btn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

/**
 * AJAX Live Voting (Likes & Dislikes)
 */
function vote(bookId, type) {
    const likeBtn = document.getElementById(`btn-like-${bookId}`);
    const dislikeBtn = document.getElementById(`btn-dislike-${bookId}`);
    const likesSpan = document.getElementById(`likes-${bookId}`);
    const dislikesSpan = document.getElementById(`dislikes-${bookId}`);

    fetch('vote.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `book_id=${encodeURIComponent(bookId)}&type=${encodeURIComponent(type)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            if (likesSpan) likesSpan.innerText = data.likes;
            if (dislikesSpan) dislikesSpan.innerText = data.dislikes;

            if (likeBtn) likeBtn.classList.toggle('voted', data.user_vote === 'like');
            if (dislikeBtn) dislikeBtn.classList.toggle('voted', data.user_vote === 'dislike');
        }
    })
    .catch(err => {
        console.error('Vote failed:', err);
    });
}
window.vote = vote;


/**
 * 7. Dynamic Live Theme Switcher Engine
 */
function initTheme() {
    const savedTheme = localStorage.getItem('bookverse_theme') || 'amethyst';
    setTheme(savedTheme, false);
}

function setTheme(themeName, save) {
    if (save === undefined) save = true;
    document.documentElement.setAttribute('data-theme', themeName);
    if (save) {
        localStorage.setItem('bookverse_theme', themeName);
    }

    const options = document.querySelectorAll('.theme-option');
    options.forEach(opt => {
        const optTheme = opt.getAttribute('data-theme-name') || '';
        opt.classList.toggle('active', optTheme === themeName);
    });
}
window.setTheme = setTheme;
