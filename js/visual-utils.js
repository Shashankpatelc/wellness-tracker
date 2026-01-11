/**
 * Wellness Tracker - Visual Enhancements Utilities
 * Toast notifications, progress rings, badges, and visual feedback
 */

// ===== TOAST NOTIFICATIONS =====
const Toast = {
    show: function (message, type = 'info', duration = 3000) {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;

        const icons = {
            success: '✓',
            error: '✕',
            info: 'ℹ',
            warning: '⚠'
        };

        toast.innerHTML = `
            <span class="toast-icon">${icons[type] || icons.info}</span>
            <span class="toast-message">${message}</span>
            <span class="toast-close">×</span>
        `;

        document.body.appendChild(toast);

        // Close button
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => {
            toast.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        });

        // Auto remove
        if (duration > 0) {
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.style.animation = 'slideOutRight 0.3s ease-out';
                    setTimeout(() => toast.remove(), 300);
                }
            }, duration);
        }

        return toast;
    },

    success: function (message, duration) {
        return this.show(message, 'success', duration);
    },

    error: function (message, duration) {
        return this.show(message, 'error', duration);
    },

    info: function (message, duration) {
        return this.show(message, 'info', duration);
    },

    warning: function (message, duration) {
        return this.show(message, 'warning', duration);
    }
};

// Add slideOutRight animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// ===== CIRCULAR PROGRESS RING =====
function createProgressRing(value, max = 10, label = '', color = '#6366f1') {
    const percentage = (value / max) * 100;
    const circumference = 2 * Math.PI * 54; // radius = 54
    const offset = circumference - (percentage / 100) * circumference;

    const container = document.createElement('div');
    container.className = 'progress-ring';

    container.innerHTML = `
        <svg width="120" height="120" class="progress-ring-circle">
            <defs>
                <linearGradient id="progressGradient-${Math.random().toString(36).substr(2, 9)}" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:${color};stop-opacity:1" />
                    <stop offset="100%" style="stop-color:${adjustColor(color, -20)};stop-opacity:1" />
                </linearGradient>
            </defs>
            <circle class="progress-ring-background" cx="60" cy="60" r="54"></circle>
            <circle class="progress-ring-progress" cx="60" cy="60" r="54"
                    style="stroke-dasharray: ${circumference}; stroke-dashoffset: ${offset};"></circle>
        </svg>
        <div class="progress-ring-text">${value}</div>
        ${label ? `<div class="progress-ring-label">${label}</div>` : ''}
    `;

    return container;
}

// Helper to adjust color brightness
function adjustColor(color, amount) {
    return '#' + color.replace(/^#/, '').replace(/../g, color => ('0' + Math.min(255, Math.max(0, parseInt(color, 16) + amount)).toString(16)).substr(-2));
}

// ===== MOOD EMOJI INDICATOR =====
const moodEmojis = {
    0: '😢',
    1: '😞',
    2: '😔',
    3: '😕',
    4: '😐',
    5: '🙂',
    6: '😊',
    7: '😄',
    8: '😁',
    9: '🤗',
    10: '🤩'
};

function updateMoodIndicator(value, targetElement) {
    if (!targetElement) return;

    targetElement.textContent = moodEmojis[Math.round(value)] || '😐';
    targetElement.className = 'mood-indicator';
}

// ===== STREAK BADGE =====
function createStreakBadge(days) {
    const badge = document.createElement('div');
    badge.className = 'streak-badge';
    badge.innerHTML = `
        <span class="badge-icon">🔥</span>
        <span class="streak-number">${days}</span>
        <span>Day Streak!</span>
    `;
    return badge;
}

// ===== ACHIEVEMENT BADGE =====
function createBadge(text, type = 'primary', icon = '') {
    const badge = document.createElement('span');
    badge.className = `badge ${type}`;
    badge.innerHTML = `
        ${icon ? `<span class="badge-icon">${icon}</span>` : ''}
        <span>${text}</span>
    `;
    return badge;
}

// ===== CONFETTI ANIMATION (for goal completion) =====
function celebrateWithConfetti() {
    const colors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#3b82f6'];
    const confettiCount = 50;

    for (let i = 0; i < confettiCount; i++) {
        const confetti = document.createElement('div');
        confetti.style.position = 'fixed';
        confetti.style.width = '10px';
        confetti.style.height = '10px';
        confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        confetti.style.left = Math.random() * window.innerWidth + 'px';
        confetti.style.top = '-10px';
        confetti.style.opacity = '1';
        confetti.style.borderRadius = '50%';
        confetti.style.pointerEvents = 'none';
        confetti.style.zIndex = '10000';

        document.body.appendChild(confetti);

        const duration = 2000 + Math.random() * 1000;
        const xMovement = (Math.random() - 0.5) * 200;

        confetti.animate([
            { transform: 'translateY(0) translateX(0) rotate(0deg)', opacity: 1 },
            { transform: `translateY(${window.innerHeight}px) translateX(${xMovement}px) rotate(${Math.random() * 360}deg)`, opacity: 0 }
        ], {
            duration: duration,
            easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
        }).onfinish = () => confetti.remove();
    }
}

// ===== FLOATING ACTION BUTTON =====
function createFAB(onClick, icon = '+') {
    const fab = document.createElement('button');
    fab.className = 'fab';
    fab.innerHTML = icon;
    fab.addEventListener('click', onClick);
    document.body.appendChild(fab);
    return fab;
}

// ===== AUTO-INITIALIZE MOOD SLIDERS =====
function initMoodSliders() {
    const moodSlider = document.getElementById('mood_score');
    const stressSlider = document.getElementById('stress_score');

    if (moodSlider) {
        const moodIndicator = document.createElement('div');
        moodIndicator.className = 'mood-indicator';
        moodIndicator.textContent = moodEmojis[parseInt(moodSlider.value)] || '😐';
        moodSlider.parentElement.insertBefore(moodIndicator, moodSlider);

        moodSlider.addEventListener('input', (e) => {
            updateMoodIndicator(e.target.value, moodIndicator);
        });
    }

    if (stressSlider) {
        const stressIndicator = document.createElement('div');
        stressIndicator.className = 'mood-indicator';
        stressIndicator.textContent = moodEmojis[10 - parseInt(stressSlider.value)] || '😐';
        stressSlider.parentElement.insertBefore(stressIndicator, stressSlider);

        stressSlider.addEventListener('input', (e) => {
            // Invert for stress (high stress = sad emoji)
            updateMoodIndicator(10 - e.target.value, stressIndicator);
        });
    }
}

// Auto-initialize on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMoodSliders);
} else {
    initMoodSliders();
}

// Export utilities
window.WellnessVisuals = {
    Toast,
    createProgressRing,
    updateMoodIndicator,
    createStreakBadge,
    createBadge,
    celebrateWithConfetti,
    createFAB,
    moodEmojis
};
