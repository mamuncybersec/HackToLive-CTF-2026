<?php
session_start();

// Initialize challenge data
$challenges = [
    [
        'id' => 1,
        'title' => 'Caesar Cipher',
        'description' => 'Decode this Caesar cipher (shift by 3):',
        'encrypted' => 'WKH TXLFN EURZQ IRA MXPSV RYHU WKH ODCB GRJ',
        'answer' => 'the quick brown fox jumps over the lazy dog',
        'hint' => 'Try rotating each letter by different amounts',
        'difficulty' => 'Easy',
        'points' => 20
    ],
    [
        'id' => 2,
        'title' => 'Base64 Encoding',
        'description' => 'Decode this Base64 encoded string:',
        'encrypted' => 'RmxhZ3s2MTNkYjBjYjk5NWMwN2E0ZTVlODczOTRlYzdiMDc5Nn0=',
        'answer' => 'flag{613db0cb995c07a4e5e87394ec7b0796}',
        'hint' => 'Use an online Base64 decoder or PHP base64_decode()',
        'difficulty' => 'Easy',
        'points' => 20
    ],
    [
        'id' => 3,
        'title' => 'Simple XOR Cipher',
        'description' => 'This string was XORed with key 42:',
        'encrypted' => '57 15 10 13 43 25 27 16 16 27 16 51 05 13 04',
        'answer' => 'crypto is awesome',
        'hint' => 'XOR each byte with 42 to decode',
        'difficulty' => 'Medium',
        'points' => 30
    ],
    [
        'id' => 4,
        'title' => 'Morse Code',
        'description' => 'Decode this Morse code:',
        'encrypted' => '.... . .-.. .-.. --- .-- --- .-. .-.. -..',
        'answer' => 'helloworld',
        'hint' => 'Dash = -, Dot = . Space = letter separator',
        'difficulty' => 'Medium',
        'points' => 30
    ],
    [
        'id' => 5,
        'title' => 'ROT13 Cipher',
        'description' => 'Decode this ROT13 encoded message:',
        'encrypted' => 'Guvf vf n urn13 rapbqrq frethrg, gung\'f ubj lbh pnccgher gur synt!',
        'answer' => 'this is a rot13 encoded request, that\'s how you capture the flag!',
        'hint' => 'ROT13 is a Caesar cipher with rotation of 13',
        'difficulty' => 'Easy',
        'points' => 20
    ],
    [
        'id' => 6,
        'title' => 'Substitution Cipher',
        'description' => 'Crack this substitution cipher:',
        'encrypted' => 'VHFXULWB LH WRB KHVWLRQ HQFBSWLRQ',
        'answer' => 'security key test question encryption',
        'hint' => 'Frequency analysis: E, T, A are most common',
        'difficulty' => 'Hard',
        'points' => 50
    ]
];

// Handle form submission
$submitted = false;
$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = true;
    $challenge_id = (int)$_POST['challenge_id'];
    $user_answer = strtolower(trim($_POST['answer']));
    
    // Find the challenge
    $challenge = null;
    foreach ($challenges as $c) {
        if ($c['id'] === $challenge_id) {
            $challenge = $c;
            break;
        }
    }
    
    if ($challenge) {
        $correct_answer = strtolower($challenge['answer']);
        $result = [
            'correct' => ($user_answer === $correct_answer),
            'challenge' => $challenge,
            'user_answer' => $_POST['answer']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cryptography Challenges - HackToLive CTF</title>
    <link rel="icon" href="../logos/logo.png" type="image/png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: linear-gradient(135deg, #0d1f3c 0%, #1a2e5c 50%, #2a5298 100%);
            min-height: 100vh;
            color: #333;
            perspective: 1000px;
        }
        
        header {
            background: rgba(0, 0, 0, 0.95);
            color: white;
            padding: 30px;
            text-align: center;
            border-bottom: 3px solid #00ff00;
            box-shadow: 0 10px 40px rgba(0, 255, 0, 0.3), inset 0 1px 0 rgba(0, 255, 0, 0.1);
        }
        
        header h1 {
            font-size: 2.5em;
            text-shadow: 0 0 10px #00ff00, 0 0 20px #00ff00, 0 0 30px #00ff00;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        
        header a {
            color: #00ff00;
            text-decoration: none;
            font-size: 0.9em;
            transition: all 0.3s;
        }
        
        header a:hover {
            text-shadow: 0 0 10px #00ff00;
        }
        
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .challenges-list {
            display: grid;
            gap: 20px;
            margin-top: 30px;
        }
        
        .challenge-box {
            background: linear-gradient(135deg, #ffffff 0%, #f5f5f5 100%);
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2), 0 0 20px rgba(0, 255, 0, 0.1);
            border: 1px solid rgba(0, 255, 0, 0.2);
            transition: all 0.3s;
        }
        
        .challenge-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, #00ff00, transparent);
        }
        
        .challenge-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 255, 0, 0.2), 0 0 30px rgba(0, 255, 0, 0.15);
        }
        
        .challenge-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .challenge-box h3 {
            color: #1e3c72;
            font-size: 1.5em;
        }
        
        .difficulty-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 0.85em;
            font-weight: bold;
        }
        
        .difficulty-easy { background: #90EE90; color: #333; }
        .difficulty-medium { background: #FFD700; color: #333; }
        .difficulty-hard { background: #FF6B6B; color: white; }
        
        .points-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 0.85em;
            font-weight: bold;
            background: #6c5ce7;
            color: white;
            margin-left: 10px;
        }
        
        .encrypted-text {
            background: #1a1a1a;
            color: #00ff00;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            word-break: break-all;
            border-left: 3px solid #00ff00;
            text-shadow: 0 0 5px #00ff00;
        }
        
        .challenge-description {
            color: #666;
            margin-bottom: 10px;
            font-size: 0.95em;
        }
        
        .form-group {
            margin: 15px 0;
        }
        
        label {
            display: block;
            color: #333;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        input[type="text"], input[type="hidden"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #00ff00;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            background: #f0f0f0;
            color: #333;
            transition: all 0.3s;
        }
        
        input[type="text"]:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
            background: #fff;
        }
        
        .btn {
            background: linear-gradient(135deg, #00ff00 0%, #00cc00 100%);
            color: #000;
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 255, 0, 0.3), inset 0 -2px 0 rgba(0, 0, 0, 0.2);
            font-family: 'Courier New', monospace;
            font-size: 0.95em;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 255, 0, 0.5), inset 0 -2px 0 rgba(0, 0, 0, 0.2);
        }
        
        .btn-secondary {
            background: #6c757d;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3), inset 0 -2px 0 rgba(0, 0, 0, 0.2);
            color: white;
            margin-left: 10px;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.5), inset 0 -2px 0 rgba(0, 0, 0, 0.2);
        }
        
        .hint {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            border-radius: 4px;
            margin-top: 12px;
            color: #856404;
            font-size: 0.9em;
        }
        
        .result-box {
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
            font-weight: bold;
        }
        
        .result-success {
            background: #d4edda;
            border: 2px solid #28a745;
            color: #155724;
        }
        
        .result-error {
            background: #f8d7da;
            border: 2px solid #dc3545;
            color: #721c24;
        }
        
        .flag-display {
            background: #1a1a1a;
            color: #00ff00;
            padding: 12px;
            border-radius: 4px;
            margin-top: 10px;
            text-shadow: 0 0 5px #00ff00;
            word-break: break-all;
            font-family: 'Courier New', monospace;
        }
        
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #00ff00;
            color: #000;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 255, 0, 0.3);
        }
        
        .back-btn:hover {
            background: #00cc00;
            box-shadow: 0 8px 25px rgba(0, 255, 0, 0.5);
        }
    </style>
</head>
<body>
    <header>
        <h1>🔐 Cryptography Challenges</h1>
        <p>Decode, decrypt, and break codes to capture the flag!</p>
        <a href="../index.html">&larr; Back to Hub</a>
    </header>
    
    <div class="container">
        <a href="../index.html" class="back-btn">&larr; Back to Challenge Hub</a>
        
        <div class="challenges-list">
            <?php foreach ($challenges as $challenge): ?>
                <div class="challenge-box">
                    <div class="challenge-header">
                        <h3><?php echo htmlspecialchars($challenge['title']); ?></h3>
                        <span class="difficulty-badge difficulty-<?php echo strtolower($challenge['difficulty']); ?>">
                            <?php echo htmlspecialchars($challenge['difficulty']); ?>
                        </span>
                        <span class="points-badge">+<?php echo $challenge['points']; ?> pts</span>
                    </div>
                    
                    <p class="challenge-description"><?php echo htmlspecialchars($challenge['description']); ?></p>
                    
                    <div class="encrypted-text">
                        <?php echo htmlspecialchars($challenge['encrypted']); ?>
                    </div>
                    
                    <?php if ($submitted && isset($result) && $result['challenge']['id'] === $challenge['id']): ?>
                        <?php if ($result['correct']): ?>
                            <div class="result-box result-success">
                                ✅ CORRECT! You captured the flag and earned +<?php echo $result['challenge']['points']; ?> points!
                                <div class="flag-display">
                                    FLAG{<?php echo strtoupper(md5($challenge['answer'])); ?>}
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="result-box result-error">
                                ❌ Incorrect! Try again.
                                <div style="margin-top: 8px; font-size: 0.9em;">
                                    Your answer: <?php echo htmlspecialchars($result['user_answer']); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <form method="POST" style="margin-top: 15px;">
                        <div class="form-group">
                            <label>Your Answer:</label>
                            <input type="text" name="answer" placeholder="Enter your decoded answer..." required>
                            <input type="hidden" name="challenge_id" value="<?php echo $challenge['id']; ?>">
                        </div>
                        <button type="submit" class="btn">Submit Answer</button>
                    </form>
                    
                    <div class="hint">
                        💡 Hint: <?php echo htmlspecialchars($challenge['hint']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
