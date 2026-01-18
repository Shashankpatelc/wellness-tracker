// Celebration Popup JavaScript
// Handles popup modal, close button, and share experience functionality

document.addEventListener('DOMContentLoaded', function () {
    const celebrationOverlay = document.getElementById('celebrationOverlay');
    const celebrationPopup = document.getElementById('celebrationPopup');
    const closeCelebrationBtn = document.getElementById('closeCelebration');
    const dismissCelebrationBtn = document.getElementById('dismissCelebration');
    const shareExperienceBtn = document.getElementById('shareExperienceBtn');

    // Close celebration popup
    function closeCelebration() {
        if (celebrationOverlay) {
            celebrationOverlay.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => {
                celebrationOverlay.style.display = 'none';
            }, 300);
        }
    }

    // Close button
    if (closeCelebrationBtn) {
        closeCelebrationBtn.addEventListener('click', closeCelebration);
    }

    // Dismiss button (Maybe Later)
    if (dismissCelebrationBtn) {
        dismissCelebrationBtn.addEventListener('click', closeCelebration);
    }

    // Click overlay to close (but not the popup itself)
    if (celebrationOverlay) {
        celebrationOverlay.addEventListener('click', function (e) {
            if (e.target === celebrationOverlay) {
                closeCelebration();
            }
        });
    }

    // Share experience button - redirects to AI chat
    if (shareExperienceBtn) {
        shareExperienceBtn.addEventListener('click', function () {
            // Redirect to AI chat page where user can share their positive experience
            window.location.href = '/wellness-tracker/php/ai_chat.php';
        });
    }

    // Auto-dismiss after 15 seconds if user doesn't interact
    if (celebrationOverlay) {
        setTimeout(() => {
            if (celebrationOverlay.style.display !== 'none') {
                closeCelebration();
            }
        }, 15000);
    }
});

// Fade out animation
const styleSheet = document.styleSheets[0];
try {
    styleSheet.insertRule(`
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
    `, styleSheet.cssRules.length);
} catch (e) {
    // Animation already exists or insertion failed
}
