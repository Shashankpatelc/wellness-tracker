<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AI Chat - Wellness Tracker</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/dark-mode.css">
</head>
<body>
    <div class="header">
        <h1>AI Chat</h1>
        <p>Talk to our AI companion to relax and reflect.</p>
        <div class="auth-buttons">
            <a href="dashboard.php" class="button">Dashboard</a>
            <a href="logout.php" class="button">Sign Out</a>
            <div class="dark-mode-toggle">
                <label for="dark-mode-switch">Dark Mode</label>
                <label class="toggle-switch">
                    <input type="checkbox" id="dark-mode-switch">
                    <span class="slider"></span>
                </label>
            </div>
        </div>
    </div>

    <div class="container">
        <section class="chat-interface">
            <div class="wellness-info">
                <h3>📊 Your Wellness Today</h3>
                <div class="stats-grid">
                    <div class="stat-box">
                        <span class="stat-label">Current Mood</span>
                        <span class="stat-value"><?php echo htmlspecialchars($user_stats['latest_mood']); ?>/10</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-label">Current Stress</span>
                        <span class="stat-value"><?php echo htmlspecialchars($user_stats['latest_stress']); ?>/10</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-label">Month Avg Mood</span>
                        <span class="stat-value"><?php echo htmlspecialchars($user_stats['avg_mood']); ?>/10</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-label">Month Avg Stress</span>
                        <span class="stat-value"><?php echo htmlspecialchars($user_stats['avg_stress']); ?>/10</span>
                    </div>
                </div>
            </div>
            <div class="chat-display" id="chat-display">
                <div class="message ai-message">
                    <p class="message-sender">🤖 Guide</p>
                    <p>Hello, <?php echo htmlspecialchars($username); ?>! I'm here to listen and help you relax. How are you feeling today?</p>
                </div>
            </div>
            <div class="chat-input">
                <input type="text" id="user-message" placeholder="Type your message here...">
                <button id="send-button" class="button primary">Send</button>
            </div>
        </section>
    </div>
<script>
    // Dark Mode Toggle
    const htmlElement = document.documentElement;
    const darkModeSwitch = document.getElementById('dark-mode-switch');
    
    // Set initial theme from localStorage
    const currentTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-theme', currentTheme);
    if (currentTheme === 'dark') {
        darkModeSwitch.checked = true;
    }
    
    // Listen for dark mode toggle changes
    darkModeSwitch.addEventListener('change', () => {
        const theme = darkModeSwitch.checked ? 'dark' : 'light';
        htmlElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
    });
</script>
<script>
    const chatDisplay = document.getElementById('chat-display');
    const userMessageInput = document.getElementById('user-message');
    const sendButton = document.getElementById('send-button');

    sendButton.addEventListener('click', sendMessage);
    userMessageInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    function sendMessage() {
        const message = userMessageInput.value.trim();
        if (message === '') return;

        // Display user message
        const userMessageDiv = document.createElement('div');
        userMessageDiv.classList.add('message', 'user-message');
        userMessageDiv.innerHTML = `<p class="message-sender">👤 <?php echo htmlspecialchars($username);?></p><p>${htmlspecialchars(message)}</p>`;
        chatDisplay.appendChild(userMessageDiv);

        userMessageInput.value = '';
        chatDisplay.scrollTop = chatDisplay.scrollHeight; // Scroll to bottom

        // Send message to AI backend
        fetch('../php/ai_chat.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `message=${encodeURIComponent(message)}`
        })
        .then(response => response.json())
        .then(data => {
            const aiMessageDiv = document.createElement('div');
            aiMessageDiv.classList.add('message', 'ai-message');
            const responseText = data.response; // Don't escape - keep special chars
            aiMessageDiv.innerHTML = `<p class="message-sender">🤖 Guide</p><p class="typing-text"></p>`;
            chatDisplay.appendChild(aiMessageDiv);
            
            // Get the paragraph element where text will be typed
            const typingElement = aiMessageDiv.querySelector('.typing-text');
            
            // Add typing effect
            typeWriter(responseText, typingElement);
            chatDisplay.scrollTop = chatDisplay.scrollHeight;
        })
        .catch(error => {
            console.error('Error:', error);
            const errorMessageDiv = document.createElement('div');
            errorMessageDiv.classList.add('message', 'ai-message');
            errorMessageDiv.innerHTML = `<p class="message-sender">🤖 Guide</p><p>Oops! Something went wrong. Please try again later.</p>`;
            chatDisplay.appendChild(errorMessageDiv);
            chatDisplay.scrollTop = chatDisplay.scrollHeight;
        });
    }

    function typeWriter(text, element) {
        let index = 0;
        const typingSpeed = 30; // milliseconds per character
        
        function type() {
            if (index < text.length) {
                // Add one character at a time
                element.textContent += text.charAt(index);
                index++;
                
                // Scroll chat to bottom as text is being typed
                chatDisplay.scrollTop = chatDisplay.scrollHeight;
                
                // Continue typing
                setTimeout(type, typingSpeed);
            }
        }
        
        // Start the typing animation
        type();
    }

    function htmlspecialchars(str) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return str.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
</script>
</body>
</html>
