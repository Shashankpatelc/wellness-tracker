-- Seed file for mood_entries table
-- Generates 30 days of sample mood data for user_id = (Enter the user Id) (admin)
-- Run this after creating a user account, or update user_id as needed

USE wellness_tracker_db;

-- Delete existing mood entries for user_id 1 (optional - uncomment if needed)
-- DELETE FROM mood_entries WHERE user_id = (Enter the user Id);

-- Insert 30 days of mood data (from 2025-12-14 to 2026-01-12)
INSERT INTO mood_entries (user_id, mood_score, stress_score, notes, entry_date) VALUES
-- Week 1: December 14-20, 2025 (Starting slow)
(1, 5, 6, 'Feeling average today. Work was stressful but manageable.', '2025-12-14'),
(1, 6, 5, 'Had a good morning walk. Helped clear my mind.', '2025-12-15'),
(1, 4, 7, 'Deadline pressure at work. Feeling overwhelmed.', '2025-12-16'),
(1, 5, 6, 'Meditation helped today. Still some anxiety.', '2025-12-17'),
(1, 7, 4, 'Great workout session! Feeling energized.', '2025-12-18'),
(1, 6, 5, 'Productive day. Completed most of my tasks.', '2025-12-19'),
(1, 8, 3, 'Weekend relaxation. Spent time with family.', '2025-12-20'),

-- Week 2: December 21-27, 2025 (Holiday week - mixed feelings)
(1, 7, 4, 'Holiday preparations going well.', '2025-12-21'),
(1, 6, 5, 'Busy day but managed to stay calm.', '2025-12-22'),
(1, 8, 3, 'Feeling grateful for the holiday break.', '2025-12-23'),
(1, 9, 2, 'Christmas Eve! Wonderful time with loved ones.', '2025-12-24'),
(1, 9, 2, 'Merry Christmas! Feeling blessed and happy.', '2025-12-25'),
(1, 7, 4, 'Post-holiday rest day. Watched movies.', '2025-12-26'),
(1, 6, 5, 'Starting to think about New Year resolutions.', '2025-12-27'),

-- Week 3: December 28, 2025 - January 3, 2026 (New Year transition)
(1, 7, 4, 'Reflecting on the past year. Mixed emotions.', '2025-12-28'),
(1, 6, 5, 'Planning goals for the new year.', '2025-12-29'),
(1, 7, 4, 'Excited about new beginnings.', '2025-12-30'),
(1, 8, 3, 'New Years Eve celebrations!', '2025-12-31'),
(1, 8, 3, 'Happy New Year! Fresh start feeling.', '2026-01-01'),
(1, 6, 5, 'Back to reality. Setting up routines.', '2026-01-02'),
(1, 5, 6, 'Adjusting to normal schedule again.', '2026-01-03'),

-- Week 4: January 4-10, 2026 (Getting into routine)
(1, 6, 5, 'Started new exercise routine. Feeling motivated.', '2026-01-04'),
(1, 7, 4, 'Good progress on wellness goals.', '2026-01-05'),
(1, 5, 6, 'Stressful Monday at work.', '2026-01-06'),
(1, 6, 5, 'Better day. Practiced mindfulness.', '2026-01-07'),
(1, 7, 4, 'Healthy eating streak continues!', '2026-01-08'),
(1, 8, 3, 'Accomplished a major work milestone.', '2026-01-09'),
(1, 7, 4, 'Relaxing weekend ahead. Feeling optimistic.', '2026-01-10'),

-- Final days: January 11-12, 2026
(1, 8, 3, 'Great weekend! Outdoor activities with friends.', '2026-01-11'),
(1, 7, 4, 'Productive Sunday. Ready for the week ahead.', '2026-01-12');

-- Verify inserted data
SELECT COUNT(*) as total_entries, 
       AVG(mood_score) as avg_mood, 
       AVG(stress_score) as avg_stress 
FROM mood_entries 
WHERE user_id = 1;
