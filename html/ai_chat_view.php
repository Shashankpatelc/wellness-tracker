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
            <a href="/wellness-tracker/php/dashboard.php" class="button">Dashboard</a>
            
            <div class="profile-dropdown">
                <button class="profile-dropdown-btn" id="profileDropdownBtn">
                    <svg class="svg-icon" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span>Profile</span>
                    <span class="arrow">▼</span>
                </button>
                <div class="profile-dropdown-menu" id="profileDropdownMenu">
                    <a href="/wellness-tracker/php/profile.php">
                        <svg class="svg-icon" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> View Profile
                    </a>
                    <a href="/wellness-tracker/php/profile.php#edit">
                        <svg class="svg-icon" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit Information
                    </a>
                    <a href="/wellness-tracker/php/logout.php" class="danger">
                        <svg class="svg-icon" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> Sign Out
                    </a>
                </div>
            </div>
            <div class="dark-mode-toggle">
                <label for="dark-mode-switch">Dark Mode</label>
                <label class="toggle-switch">
                    <input type="checkbox" id="dark-mode-switch">
                    <span class="slider"></span>
                </label>
            </div>
        </div>
    </div>

    <div class="container scale-in delay-200">
        <section class="chat-interface fade-in-up">
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
                <button id="voice-button" class="button voice-btn" title="Click to start voice input">🎤</button>
                <button id="send-button" class="button primary">Send</button>
            </div>
            <div id="voice-status" class="voice-status"></div>
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
    
    // Profile Dropdown Menu
    const profileDropdownBtn = document.getElementById('profileDropdownBtn');
    const profileDropdown = profileDropdownBtn?.parentElement;
    
    if (profileDropdownBtn && profileDropdown) {
        profileDropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!profileDropdown.contains(e.target)) {
                profileDropdown.classList.remove('active');
            }
        });
    }
</script>
<script>
    // Initialize Speech Recognition API with better browser detection
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    let recognition = null;
    let isListening = false;
    let isSupported = false;

    // Initialize recognition object if API is available
    try {
        if (SpeechRecognition) {
            recognition = new SpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = true;
            recognition.lang = 'en-US';
            isSupported = true;
            console.log('✅ Speech Recognition API is supported');
        }
    } catch (error) {
        console.warn('⚠️ Speech Recognition API error:', error);
        isSupported = false;
    }

    const chatDisplay = document.getElementById('chat-display');
    const userMessageInput = document.getElementById('user-message');
    const sendButton = document.getElementById('send-button');
    const voiceButton = document.getElementById('voice-button');
    const voiceStatus = document.getElementById('voice-status');

    sendButton.addEventListener('click', sendMessage);
    userMessageInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Voice Input Handler - Enable if supported
    if (voiceButton) {
        if (isSupported && recognition) {
            // Full voice feature enabled
            voiceButton.disabled = false;
            voiceButton.style.opacity = '1';
            voiceButton.style.cursor = 'pointer';
            
            voiceButton.addEventListener('click', function () {
                if (isListening) {
                    recognition.stop();
                    isListening = false;
                    voiceButton.classList.remove('listening');
                    voiceStatus.textContent = '';
                } else {
                    // Clear any previous input for new recording
                    userMessageInput.value = '';
                    try {
                        recognition.start();
                        isListening = true;
                        voiceButton.classList.add('listening');
                        voiceStatus.textContent = '🎤 Listening...';
                    } catch (error) {
                        console.warn('⚠️ Could not start recognition:', error);
                        voiceStatus.textContent = '❌ Could not start recording. Already recording?';
                        setTimeout(() => {
                            voiceStatus.textContent = '';
                        }, 5000);
                    }
                }
            });

            recognition.onstart = function () {
                isListening = true;
                voiceButton.classList.add('listening');
                voiceStatus.textContent = '🎤 Listening...';
            };

            recognition.onresult = function (event) {
                let interimTranscript = '';
                let finalTranscript = '';

                for (let i = event.resultIndex; i < event.results.length; i++) {
                    const transcript = event.results[i][0].transcript;

                    if (event.results[i].isFinal) {
                        finalTranscript += transcript + ' ';
                    } else {
                        interimTranscript += transcript;
                    }
                }

                // Display interim results (what user is saying)
                if (interimTranscript) {
                    userMessageInput.value = finalTranscript + interimTranscript;
                    userMessageInput.style.fontStyle = 'italic';
                }

                // When speech is recognized, update the input field
                if (finalTranscript) {
                    userMessageInput.value = finalTranscript;
                    userMessageInput.style.fontStyle = 'normal';
                }
            };

            recognition.onerror = function (event) {
                voiceStatus.textContent = '❌ Error: ' + event.error;
                voiceButton.classList.remove('listening');
                isListening = false;

                // Auto-clear error message after 3 seconds
                setTimeout(() => {
                    voiceStatus.textContent = '';
                }, 3000);
            };

            recognition.onend = function () {
                isListening = false;
                voiceButton.classList.remove('listening');
                voiceStatus.textContent = '';
                userMessageInput.style.fontStyle = 'normal';
            };
        } else {
            // Speech Recognition not supported - disable gracefully
            console.warn('⚠️ Speech Recognition API not supported in this browser');
            voiceButton.disabled = true;
            voiceButton.title = 'Speech Recognition not supported in your browser. Please use typing instead.';
            voiceButton.style.opacity = '0.5';
            voiceButton.style.cursor = 'not-allowed';
        }
    }

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
        fetch('/wellness-tracker/php/ai_chat.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `message=${encodeURIComponent(message)}`
        })
        .then(response => response.json())
        .then(data => {
            
            if (!data || !data.response) {
                console.error('Invalid response structure:', data);
                
                // Show error message to user
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('message', 'ai-message');
                const errorP1 = document.createElement('p');
                errorP1.className = 'message-sender';
                errorP1.textContent = '🤖 Guide';
                const errorP2 = document.createElement('p');
                errorP2.textContent = 'Oops! Something went wrong. Please try again later.';
                errorDiv.appendChild(errorP1);
                errorDiv.appendChild(errorP2);
                chatDisplay.appendChild(errorDiv);
                chatDisplay.scrollTop = chatDisplay.scrollHeight;
                return;
            }
            
            const responseText = data.response;
            
            // Create AI message container
            const aiMessageDiv = document.createElement('div');
            aiMessageDiv.classList.add('message', 'ai-message');
            
            // Create sender paragraph
            const senderP = document.createElement('p');
            senderP.className = 'message-sender';
            senderP.textContent = '🤖 Guide';
            
            // Create typing text paragraph
            const typingP = document.createElement('p');
            typingP.className = 'typing-text';
            
            // Append both paragraphs
            aiMessageDiv.appendChild(senderP);
            aiMessageDiv.appendChild(typingP);
            chatDisplay.appendChild(aiMessageDiv);
            
            // Display text immediately
            typingP.textContent = responseText;
            
            chatDisplay.scrollTop = chatDisplay.scrollHeight;
        })
        .catch(error => {
            console.error('Error:', error);
            const errorMessageDiv = document.createElement('div');
            errorMessageDiv.classList.add('message', 'ai-message');
            const errorP1 = document.createElement('p');
            errorP1.className = 'message-sender';
            errorP1.textContent = '🤖 Guide';
            const errorP2 = document.createElement('p');
            errorP2.textContent = 'Oops! Something went wrong. Please try again later.';
            errorMessageDiv.appendChild(errorP1);
            errorMessageDiv.appendChild(errorP2);
            chatDisplay.appendChild(errorMessageDiv);
            chatDisplay.scrollTop = chatDisplay.scrollHeight;
        });
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
<script src="../js/animations.js"></script>
<script src="../js/visual-utils.js"></script>
</body>
</html>
