# 🚀 Groq AI Setup (FREE!)

## ✅ What is Groq?
- **100% FREE** API with generous limits
- **Super FAST** responses (faster than ChatGPT!)
- Uses **Llama 3.1** - high quality AI model
- Perfect for wellness chatbots

---

## 📝 How to Get Your FREE API Key

### Step 1: Create Account
1. Go to: **https://console.groq.com**
2. Click "Sign Up" (use Google/GitHub or email)
3. Verify your email

### Step 2: Get API Key
1. After login, go to: **https://console.groq.com/keys**
2. Click "Create API Key"
3. Give it a name (e.g., "Wellness Tracker")
4. Click "Submit"
5. **COPY the key** (starts with `gsk_...`)

### Step 3: Add to Your App
1. Open: `/var/www/html/wellness-tracker/config/ai_config.php`
2. Replace `YOUR_GROQ_API_KEY_HERE` with your actual key
3. Save the file

**Example:**
```php
define('GROQ_API_KEY', 'gsk_abc123xyz...');
```

---

## 🧪 Test It!

After adding your API key:
1. Go to AI Chat page
2. Send a message: "I'm feeling stressed"
3. Groq will respond instantly!

---

## 💡 Why Groq is Better Than Gemini for You

| Feature | Groq | Gemini Pro |
|---------|------|------------|
| **Cost** | FREE ✅ | Paid 💰 |
| **Speed** | Ultra-fast ⚡ | Fast |
| **Setup** | Easy | Complex |
| **Limits** | 30 req/min | Depends on plan |
| **Quality** | Excellent | Excellent |

---

## 🔧 What I Changed

### 1. Updated Config (`config/ai_config.php`)
- Changed from Gemini to Groq
- Using `llama-3.1-8b-instant` model
- OpenAI-compatible API format

### 2. Updated AI Chat (`php/ai_chat.php`)
- Changed API endpoint
- Updated request format (OpenAI style)
- Better error handling
- Same empathetic personality

---

## ⚙️ Configuration

You can adjust these in `config/ai_config.php`:

```php
GROQ_MODEL          // 'llama-3.1-8b-instant' (recommended)
                    // or 'llama-3.1-70b-versatile' (smarter but slower)
AI_MAX_TOKENS       // 150 (response length)
AI_TEMPERATURE      // 0.7 (creativity: 0.0-1.0)
```

---

## 🎯 Free Tier Limits

- **30 requests per minute**
- **6,000 requests per day**
- **14,400 tokens per minute**

More than enough for a wellness tracker! 🎉

---

## 🆘 Troubleshooting

**"API Error: Invalid API Key"**
- Double-check your API key in config file
- Make sure it starts with `gsk_`

**"Rate limit exceeded"**
- Wait 1 minute and try again
- You hit the 30 req/min limit

**"Connection failed"**
- Check internet connection
- Verify Groq API is accessible

---

## 🚀 Ready to Go!

Once you add your API key, your wellness chatbot will be powered by **Groq's lightning-fast AI** - completely FREE! ⚡

Get your key now: **https://console.groq.com/keys**
