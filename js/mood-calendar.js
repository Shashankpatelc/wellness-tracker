/**
 * Mood Calendar Heatmap Component
 * Displays a visual calendar showing mood history
 */

// Create mood calendar heatmap
function createMoodCalendar(containerId, moodData) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const today = new Date();
    const daysToShow = 90; // Show last 90 days
    const startDate = new Date(today);
    startDate.setDate(startDate.getDate() - daysToShow);

    // Create calendar grid
    const calendar = document.createElement('div');
    calendar.className = 'mood-calendar';

    // Create month labels
    const monthsRow = document.createElement('div');
    monthsRow.className = 'calendar-months';

    let currentMonth = '';
    for (let i = 0; i < daysToShow; i++) {
        const date = new Date(startDate);
        date.setDate(date.getDate() + i);
        const month = date.toLocaleDateString('en-US', { month: 'short' });

        if (month !== currentMonth && date.getDate() <= 7) {
            const monthLabel = document.createElement('div');
            monthLabel.className = 'month-label';
            monthLabel.textContent = month;
            monthLabel.style.gridColumn = `${i + 1} / span 7`;
            monthsRow.appendChild(monthLabel);
            currentMonth = month;
        }
    }
    calendar.appendChild(monthsRow);

    // Create day cells
    const daysGrid = document.createElement('div');
    daysGrid.className = 'calendar-grid';

    for (let i = 0; i < daysToShow; i++) {
        const date = new Date(startDate);
        date.setDate(date.getDate() + i);
        const dateStr = date.toISOString().split('T')[0];

        const dayCell = document.createElement('div');
        dayCell.className = 'calendar-day';
        dayCell.setAttribute('data-date', dateStr);
        dayCell.setAttribute('title', dateStr);

        // Find mood data for this date
        const moodEntry = moodData.find(entry => entry.date === dateStr);

        if (moodEntry) {
            const moodLevel = parseInt(moodEntry.mood_score);
            dayCell.classList.add(`mood-level-${moodLevel}`);
            dayCell.setAttribute('data-mood', moodLevel);
            dayCell.setAttribute('title', `${dateStr}: Mood ${moodLevel}/10`);
        } else {
            dayCell.classList.add('no-data');
        }

        // Highlight today
        if (dateStr === today.toISOString().split('T')[0]) {
            dayCell.classList.add('today');
        }

        daysGrid.appendChild(dayCell);
    }

    calendar.appendChild(daysGrid);

    // Add legend
    const legend = document.createElement('div');
    legend.className = 'calendar-legend';
    legend.innerHTML = `
        <span class="legend-label">Mood:</span>
        <div class="legend-item mood-level-0" title="Very Low"></div>
        <div class="legend-item mood-level-3" title="Low"></div>
        <div class="legend-item mood-level-5" title="Medium"></div>
        <div class="legend-item mood-level-7" title="Good"></div>
        <div class="legend-item mood-level-10" title="Excellent"></div>
        <span class="legend-label">Less</span>
        <span class="legend-label">More</span>
    `;
    calendar.appendChild(legend);

    container.appendChild(calendar);
}

// Export for use
window.createMoodCalendar = createMoodCalendar;
