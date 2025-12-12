document.addEventListener('DOMContentLoaded', () => {

    // --- DARK MODE (Bootstrap 5.3 Native) ---
    const toggleBtn = document.getElementById('dark-mode-toggle');
    const htmlElement = document.documentElement;

    // Check saved theme or system preference
    const savedTheme = localStorage.getItem('theme');
    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    const currentTheme = savedTheme || systemTheme;

    htmlElement.setAttribute('data-bs-theme', currentTheme);
    updateIcon(currentTheme);

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            const current = htmlElement.getAttribute('data-bs-theme');
            const newTheme = current === 'dark' ? 'light' : 'dark';

            htmlElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateIcon(newTheme);
        });
    }

    function updateIcon(theme) {
        if (!toggleBtn) return;
        if (theme === 'dark') {
            toggleBtn.innerHTML = '<i class="ph-fill ph-sun"></i>';
        } else {
            toggleBtn.innerHTML = '<i class="ph-fill ph-moon-stars"></i>';
        }
    }

    // --- LIVE SEARCH ---
    const searchInput = document.getElementById('live-search');
    const searchResults = document.getElementById('search-results');

    if (searchInput) {
        searchInput.addEventListener('input', debounce((e) => {
            const term = e.target.value;
            if (term.length < 2) {
                searchResults.style.display = 'none';
                return;
            }

            fetch(`index.php?controller=search&action=index&q=${encodeURIComponent(term)}`)
                .then(res => res.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    let html = '';

                    data.users.forEach(u => html += `<div class="search-item"><a href="index.php?controller=user&action=profile&id=${u.id}">👤 ${u.nom}</a></div>`);
                    data.posts.forEach(p => html += `<div class="search-item"><a href="index.php?controller=post&action=show&id=${p.id}">📝 ${p.titre}</a></div>`);

                    if (html) {
                        searchResults.innerHTML = html;
                        searchResults.style.display = 'block';
                    } else {
                        searchResults.style.display = 'none';
                    }
                });
        }, 300));

        // Close search on click outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    }

    // --- NOTIFICATIONS POLLING ---
    const notifBadge = document.getElementById('notif-badge');
    if (notifBadge) {
        setInterval(() => {
            fetch('index.php?controller=notification&action=check')
                .then(res => res.json())
                .then(data => {
                    if (data.count > 0) {
                        notifBadge.innerText = data.count;
                        notifBadge.style.display = 'inline-block';
                    } else {
                        notifBadge.style.display = 'none';
                    }
                });
        }, 10000); // 10 seconds
    }
});

// --- HELPER DEBOUNCE ---
function debounce(func, wait) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

// --- LIKE REACTION ---
function toggleLike(postId, btn) {
    fetch('index.php?controller=reaction&action=toggleLike', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ post_id: postId })
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const countSpan = btn.querySelector('.count');
                const icon = btn.querySelector('i');

                countSpan.innerText = data.count;

                if (data.action === 'added') {
                    btn.classList.add('liked');
                    icon.classList.add('ph-fill');
                    icon.classList.add('ph-heart');
                } else {
                    btn.classList.remove('liked');
                    icon.classList.remove('ph-fill');
                    icon.classList.add('ph-heart');
                }
            } else {
                window.location.href = 'index.php?controller=auth&action=login';
            }
        });
}

// --- VOTE ON COMMENT ---
function vote(commentId, type, btn) {
    fetch('index.php?controller=reaction&action=vote', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ comment_id: commentId, type: type })
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // Find score element
                const parent = btn.parentElement;
                const scoreSpan = parent.querySelector('.vote-score');
                scoreSpan.innerText = data.score;
            } else {
                console.error('Vote failed');
            }
        });
}

// --- INLINE EDIT (POSTS & COMMENTS) ---
function updatePost(id, field, value) {
    fetch('index.php?controller=post&action=edit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id, field: field, value: value })
    }).then(res => res.json()).then(console.log);
}

function updateComment(id, value) {
    fetch('index.php?controller=comment&action=edit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id, value: value })
    }).then(res => res.json()).then(console.log);
}

// --- ADD COMMENT ---
function addComment(postId) {
    const input = document.getElementById('new-comment-content');
    const content = input.value;
    if (!content.trim()) return;

    fetch('index.php?controller=comment&action=create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ post_id: postId, contenu: content })
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload(); // Simple reload to show new comment
            }
        });
}

// --- MARK NOTIFICATION READ ---
function markRead(id, btn) {
    fetch('index.php?controller=notification&action=markRead', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                btn.parentElement.classList.remove('unread');
                btn.remove();
            }
        });
}
