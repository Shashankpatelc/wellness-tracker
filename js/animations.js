/**
 * Wellness Tracker - Animation Library
 * Handles scroll-based animations, counters, and interactive effects
 */

// INTERSECTION OBSERVER FOR SCROLL ANIMATIONS 
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const animateOnScroll = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animated');

            // If element has stagger children, animate them sequentially
            if (entry.target.hasAttribute('data-stagger')) {
                staggerChildren(entry.target);
            }
        }
    });
}, observerOptions);

// Observe all elements with animation classes
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll(
        '.fade-in-up, .fade-in-left, .fade-in-right, .fade-in-down, .scale-in, .slide-in-left, .slide-in-right'
    );

    animatedElements.forEach(el => {
        animateOnScroll.observe(el);
    });
}

//  STAGGER ANIMATIONS 
function staggerChildren(parent) {
    const children = parent.children;
    Array.from(children).forEach((child, index) => {
        setTimeout(() => {
            child.classList.add('stagger-animate');
        }, index * 100); // 100ms delay between each child
    });
}

//  COUNTER ANIMATIONS 
function animateCounter(element, start, end, duration) {
    let startTime = null;
    const step = (timestamp) => {
        if (!startTime) startTime = timestamp;
        const progress = Math.min((timestamp - startTime) / duration, 1);
        const value = Math.floor(progress * (end - start) + start);
        element.textContent = value;
        if (progress < 1) {
            window.requestAnimationFrame(step);
        } else {
            element.textContent = end; // Ensure we end at exact value
        }
    };
    window.requestAnimationFrame(step);
}

// Initialize counters when they come into view
function initCounters() {
    const counters = document.querySelectorAll('[data-counter]');

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                const target = entry.target;
                const endValue = parseInt(target.getAttribute('data-counter'));
                const duration = parseInt(target.getAttribute('data-duration')) || 2000;

                target.classList.add('counted');
                animateCounter(target, 0, endValue, duration);
            }
        });
    }, observerOptions);

    counters.forEach(counter => counterObserver.observe(counter));
}

//  TABLE ROW ANIMATIONS 
function animateTableRows() {
    const tables = document.querySelectorAll('.entries-table tbody');

    tables.forEach(tbody => {
        const rows = tbody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';

            setTimeout(() => {
                row.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50); // Stagger by 50ms
        });
    });
}

//  RIPPLE EFFECT FOR BUTTONS 
function createRipple(event) {
    const button = event.currentTarget;

    // Don't add ripple if button already has the ::before pseudo-element ripple
    if (button.classList.contains('button')) return;

    const circle = document.createElement('span');
    const diameter = Math.max(button.clientWidth, button.clientHeight);
    const radius = diameter / 2;

    circle.style.width = circle.style.height = `${diameter}px`;
    circle.style.left = `${event.clientX - button.offsetLeft - radius}px`;
    circle.style.top = `${event.clientY - button.offsetTop - radius}px`;
    circle.classList.add('ripple');

    const ripple = button.getElementsByClassName('ripple')[0];
    if (ripple) {
        ripple.remove();
    }

    button.appendChild(circle);
}

//  SMOOTH SCROLL 
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#') return;

            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

//  LOADING SPINNER 
function showLoadingSpinner(container) {
    const spinner = document.createElement('div');
    spinner.className = 'loading-spinner';
    spinner.innerHTML = '<div class="spinner"></div>';
    container.appendChild(spinner);
    return spinner;
}

function hideLoadingSpinner(spinner) {
    if (spinner && spinner.parentNode) {
        spinner.classList.add('fade-out');
        setTimeout(() => spinner.remove(), 300);
    }
}

//  CARD FLIP ANIMATION 
function initCardFlips() {
    const flipCards = document.querySelectorAll('.flip-card');

    flipCards.forEach(card => {
        card.addEventListener('click', () => {
            card.classList.toggle('flipped');
        });
    });
}

//  PARALLAX EFFECT 
function initParallax() {
    const parallaxElements = document.querySelectorAll('[data-parallax]');

    window.addEventListener('scroll', () => {
        parallaxElements.forEach(el => {
            const speed = el.getAttribute('data-parallax') || 0.5;
            const yPos = -(window.pageYOffset * speed);
            el.style.transform = `translateY(${yPos}px)`;
        });
    });
}

//  TYPING ANIMATION 
function typeWriter(element, text, speed = 50) {
    let i = 0;
    element.textContent = '';

    function type() {
        if (i < text.length) {
            element.textContent += text.charAt(i);
            i++;
            setTimeout(type, speed);
        }
    }

    type();
}

//  PULSE ANIMATION ON HOVER 
function initPulseOnHover() {
    const pulseElements = document.querySelectorAll('.pulse-on-hover');

    pulseElements.forEach(el => {
        el.addEventListener('mouseenter', () => {
            el.style.animation = 'pulse 0.5s ease';
        });

        el.addEventListener('animationend', () => {
            el.style.animation = '';
        });
    });
}

//  SHAKE ANIMATION FOR ERRORS 
function shakeElement(element) {
    element.classList.add('shake-animation');
    setTimeout(() => {
        element.classList.remove('shake-animation');
    }, 500);
}

//  INITIALIZE ALL ANIMATIONS 
function initAnimations() {
    // Wait for DOM to be fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        initScrollAnimations();
        initCounters();
        initSmoothScroll();
        initCardFlips();
        initParallax();
        initPulseOnHover();

        // Animate table rows if they exist
        if (document.querySelector('.entries-table')) {
            setTimeout(animateTableRows, 300);
        }

        // Add ripple effect to all buttons
        const buttons = document.querySelectorAll('button:not(.button):not(.profile-dropdown-btn), input[type="submit"]:not(.button)');
        buttons.forEach(button => {
            button.addEventListener('click', createRipple);
        });
    }
}

// Auto-initialize
initAnimations();

// Export functions for external use
window.WellnessAnimations = {
    animateCounter,
    showLoadingSpinner,
    hideLoadingSpinner,
    shakeElement,
    typeWriter,
    staggerChildren
};
