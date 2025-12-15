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
        </div>
    </div>

    <div class="container">
        <section class="chat-interface">
            <div class="chat-display" id="chat-display">
                <div class="message ai-message">
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
    const body = document.body;
    if (localStorage.getItem('darkMode') === 'enabled') {
        body.classList.add('dark-mode');
    }
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
        userMessageDiv.innerHTML = `<p>${htmlspecialchars(message)}</p>`;
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
            aiMessageDiv.innerHTML = `<p>${htmlspecialchars(data.response)}</p>`;
            chatDisplay.appendChild(aiMessageDiv);
            chatDisplay.scrollTop = chatDisplay.scrollHeight; // Scroll to bottom
        })
        .catch(error => {
            console.error('Error:', error);
            const errorMessageDiv = document.createElement('div');
            errorMessageDiv.classList.add('message', 'ai-message');
            errorMessageDiv.innerHTML = `<p>Oops! Something went wrong. Please try again later.</p>`;
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
</body>
</html>
