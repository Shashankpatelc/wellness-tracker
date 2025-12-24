-- create wellness_tracker database
CREATE DATABASE IF NOT EXISTS wellness_tracker_db;
USE wellness_tracker_db;

-- create users table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- create mood_entries table
CREATE TABLE mood_entries (
    entry_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    mood_score TINYINT NOT NULL CHECK (mood_score BETWEEN 0 AND 10),
    stress_score TINYINT NOT NULL CHECK (stress_score BETWEEN 0 AND 10),
    notes TEXT,
    entry_date DATE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (user_id, entry_date),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- create coping_resources table
CREATE TABLE coping_resources (
    resource_id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    title VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    sort_order TINYINT DEFAULT 0,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);

INSERT INTO coping_resources (category, title, content, sort_order) VALUES
('Grounding', '5-4-3-2-1 Technique', 'Focus on 5 things you can see, 4 things you can feel, 3 things you can hear, 2 things you can smell, and 1 thing you can taste. This shifts focus away from stress.', 1),
('Grounding', 'Mindful Breathing', 'Breathe in slowly for 4 counts, hold for 4 counts, and breathe out slowly for 6 counts. Repeat 5 times.', 2),
('Crisis Contact', 'Indian Mental Health Support', 'Call 1800-599-0019. Available 24/7.', 3),
('Crisis Contact', 'AASRA', 'Call +91-9820466726. Available 24/7.', 4);

-- create journal_prompts table
CREATE TABLE journal_prompts (
    prompt_id INT AUTO_INCREMENT PRIMARY KEY,
    prompt_text TEXT NOT NULL
);

INSERT INTO journal_prompts (prompt_text) VALUES
('What was the best part of your day?'),
('What is something that made you smile today?'),
('What is one thing you are grateful for today?'),
('What is something you are looking forward to?'),
('What is a challenge you faced today and how did you handle it?');

-- create goals table
CREATE TABLE goals (
    goal_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    goal_text TEXT NOT NULL,
    is_completed BOOLEAN NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- create admin user
-- Username: admin | Password: admin@123 (change this after first login!)
INSERT INTO users (username, email, password_hash, role) VALUES 
('admin', 'admin@wellnesstracker.local', '$2y$10$zAvDiL.l99iVWoLwcKaRc.i3drGxoTjvvrml2b1xw2cdEAx1oJlXC', 'admin');
