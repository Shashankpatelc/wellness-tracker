<?php
/**
 * Sentiment Analysis Helper
 * Uses Groq AI to analyze journal entry text and determine emotional sentiment
 */

require_once __DIR__ . '/../config/ai_config.php';

/**
 * Analyze the sentiment of a journal entry
 * 
 * @param string $text The journal entry text to analyze
 * @return array Contains 'sentiment' (positive/negative/neutral), 'confidence' (0-1), and 'category'
 */
function analyzeSentiment($text) {
    // Return neutral if text is empty or too short
    if (empty(trim($text)) || strlen(trim($text)) < 5) {
        return [
            'sentiment' => 'neutral',
            'confidence' => 0.9,
            'category' => 'insufficient_text',
            'requires_support' => false
        ];
    }
    
    // Specialized sentiment analysis prompt
    $system_instruction = "You are a sentiment analysis expert for mental wellness applications. 
Your task is to analyze journal entries and determine the emotional state of the writer.

Classify the sentiment into ONE of these categories:
- NEGATIVE: The person is struggling, sad, depressed, anxious, overwhelmed, stressed, or in distress
- POSITIVE: The person is happy, joyful, grateful, energized, accomplished, or in a good mood
- NEUTRAL: The person is simply reporting facts without strong emotion

Additionally, identify if the person requires mental health support:
- REQUIRES_SUPPORT: true if they express sadness, hopelessness, anxiety, stress, or struggle
- REQUIRES_SUPPORT: false otherwise

Respond in this EXACT JSON format (no other text):
{
  \"sentiment\": \"negative|positive|neutral\",
  \"confidence\": 0.85,
  \"category\": \"brief description\",
  \"requires_support\": true|false
}

Be sensitive and err on the side of caution - if someone might be struggling, mark requires_support as true.";

    $data = [
        'model' => GROQ_MODEL,
        'messages' => [
            [
                'role' => 'system',
                'content' => $system_instruction
            ],
            [
                'role' => 'user',
                'content' => "Analyze this journal entry:\n\n" . $text
            ]
        ],
        'max_tokens' => 150,
        'temperature' => 0.3 // Lower temperature for more consistent analysis
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\nAuthorization: Bearer " . GROQ_API_KEY . "\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
            'timeout' => 15,
        ],
    ];

    $context = stream_context_create($options);
    $result = @file_get_contents(GROQ_API_URL, false, $context);

    if ($result === FALSE) {
        // Fallback to neutral on API error
        error_log("Sentiment Analysis API Error: Connection failed");
        return [
            'sentiment' => 'neutral',
            'confidence' => 0.5,
            'category' => 'api_error',
            'requires_support' => false
        ];
    }

    $response_data = json_decode($result, true);
    
    if (isset($response_data['error'])) {
        error_log("Sentiment Analysis API Error: " . json_encode($response_data['error']));
        return [
            'sentiment' => 'neutral',
            'confidence' => 0.5,
            'category' => 'api_error',
            'requires_support' => false
        ];
    }

    if (isset($response_data['choices'][0]['message']['content'])) {
        $ai_response = $response_data['choices'][0]['message']['content'];
        
        // Extract JSON from response (in case AI adds extra text)
        $json_match = preg_match('/\{[^}]+\}/', $ai_response, $matches);
        if ($json_match) {
            $sentiment_data = json_decode($matches[0], true);
            
            if ($sentiment_data && isset($sentiment_data['sentiment'])) {
                // Ensure all required fields exist
                return [
                    'sentiment' => strtolower($sentiment_data['sentiment']),
                    'confidence' => $sentiment_data['confidence'] ?? 0.7,
                    'category' => $sentiment_data['category'] ?? 'general',
                    'requires_support' => $sentiment_data['requires_support'] ?? false
                ];
            }
        }
    }

    // Fallback if parsing fails
    return [
        'sentiment' => 'neutral',
        'confidence' => 0.5,
        'category' => 'parsing_error',
        'requires_support' => false
    ];
}

/**
 * Get an encouraging prompt based on mood and stress scores
 * 
 * @param int $mood_score Mood score (0-10)
 * @param int $stress_score Stress score (0-10)
 * @param array $sentiment_data Result from analyzeSentiment()
 * @return string Encouraging prompt for AI chat
 */
function getEncouragingPrompt($mood_score, $stress_score, $sentiment_data) {
    $prompts = [];
    
    // Very low mood + negative sentiment
    if ($mood_score <= 3 && $sentiment_data['sentiment'] === 'negative') {
        $prompts = [
            "I can sense from your journal that things feel really tough right now. You're not alone in this - I'm here to listen and support you. Would you like to talk about what's weighing on you? Sometimes just sharing helps. 💙",
            "I noticed you're going through a difficult time. It takes courage to acknowledge how you're feeling. I'm here to listen without judgment. What's been the hardest part of your day? 🌟",
            "It sounds like today has been challenging for you. Remember, it's okay not to be okay. I'm here to help you process these feelings and find some relief. Want to tell me more? 💚"
        ];
    }
    // Medium mood but high stress + negative sentiment
    elseif ($mood_score <= 6 && $stress_score >= 7 && $sentiment_data['sentiment'] === 'negative') {
        $prompts = [
            "I can tell you're dealing with a lot of stress right now. Let's take a moment together - I'm here to help you find some calm and perspective. What's causing the most pressure? 🌸",
            "Stress can feel overwhelming, but you've handled tough situations before. I'm here to support you through this. Would you like to explore some ways to ease the tension? 💫",
            "You're carrying a heavy load right now. Let's work together to lighten it a bit. I'm here to listen and offer practical ways to manage this stress. Ready to talk? 🌈"
        ];
    }
    // General negative sentiment
    elseif ($sentiment_data['sentiment'] === 'negative') {
        $prompts = [
            "I noticed you might be struggling a bit. I'm here as a supportive friend - no pressure, just someone to listen. How are you really feeling right now? 💙",
            "It seems like you could use some extra support today. I'm here to help you process what you're going through. Want to share what's on your mind? 🌟",
            "Sometimes we all need someone to talk to. I'm here to listen, understand, and help you find your way through this. Ready to chat? 💚"
        ];
    }
    // Fallback
    else {
        $prompts = [
            "I'm here to support you on your wellness journey. How can I help you today? 🌸"
        ];
    }
    
    // Return a random prompt from the appropriate category
    return $prompts[array_rand($prompts)];
}

/**
 * Get a positive affirmation/celebration message for happy users
 * 
 * @param int $mood_score Mood score (0-10)
 * @param int $stress_score Stress score (0-10)
 * @return string Celebratory affirmation message
 */
function getPositiveAffirmation($mood_score, $stress_score) {
    $affirmations = [];
    
    // Very high mood + low stress (optimal state)
    if ($mood_score >= 8 && $stress_score <= 3) {
        $affirmations = [
            "You're absolutely thriving! Keep riding this positive wave - you deserve this happiness! 🌟✨",
            "What an incredible day you're having! Your positive energy is contagious - keep shining! 🌈💫",
            "You're on fire today! This is the energy that creates amazing moments. Celebrate yourself! 🎉🌟"
        ];
    }
    // Good mood with some stress (accomplishment)
    elseif ($mood_score >= 7 && $stress_score >= 5) {
        $affirmations = [
            "Look at you - handling challenges AND staying positive! That's real strength! 💪✨",
            "You're managing stress like a champion! Your resilience is inspiring! 🌟🎯",
            "Even with pressure, you're keeping your spirits up. That's the attitude of a winner! 🏆💫"
        ];
    }
    // Good mood overall
    elseif ($mood_score >= 7) {
        $affirmations = [
            "Wonderful to see you feeling so good! Keep up this amazing momentum! 🌟💚",
            "Your positive vibes are shining bright today! Let this feeling fuel your next steps! ✨🌈",
            "You're in a great place right now - savor this moment and spread that positivity! 🎉💫"
        ];
    }
    // Moderate positive mood
    elseif ($mood_score >= 5 && $stress_score <= 5) {
        $affirmations = [
            "Things are looking up! Keep nurturing this positive energy! 🌱💚",
            "You're finding your balance - that's something to celebrate! 🌟✨",
            "Nice to see you in a good space! Small wins add up to big victories! 🎯💫"
        ];
    }
    // Fallback positive
    else {
        $affirmations = [
            "Great job taking care of your wellness! Keep tracking your progress! 🌟",
            "Every positive moment counts - you're on the right path! 💚✨"
        ];
    }
    
    return $affirmations[array_rand($affirmations)];
}
?>
