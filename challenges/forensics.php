<?php
session_start();

$challenges = [
    ['id' => 1, 'title' => 'File Recovery', 'description' => 'What command recovers deleted files?', 'encrypted' => 'A user deleted important.txt', 'answer' => 'extundelete', 'hint' => 'Use ext filesystem recovery tools', 'difficulty' => 'Medium', 'points' => 40],
    ['id' => 2, 'title' => 'Memory Dump Analysis', 'description' => 'What tool analyzes memory dumps?', 'encrypted' => 'Memory acquired from running system', 'answer' => 'volatility', 'hint' => 'Popular memory forensics framework', 'difficulty' => 'Medium', 'points' => 40],
    ['id' => 3, 'title' => 'Log Analysis', 'description' => 'Find the suspicious IP in logs', 'encrypted' => '192.168.1.100 GET /admin 403 | 10.0.0.50 GET /shell.php 200 | 192.168.1.1 GET / 200', 'answer' => '10.0.0.50', 'hint' => 'Look for successful suspicious requests', 'difficulty' => 'Easy', 'points' => 25]
];

$submitted = false;
$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = true;
    $challenge_id = (int)$_POST['challenge_id'];
    $user_answer = strtolower(trim($_POST['answer']));
    
    foreach ($challenges as $c) {
        if ($c['id'] === $challenge_id) {
            $correct = strpos($user_answer, strtolower($c['answer'])) !== false;
            $result = ['correct' => $correct, 'challenge' => $c, 'answer' => $_POST['answer']];
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forensics - HackToLive CTF</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: monospace; background: linear-gradient(135deg, #0d1f3c 0%, #1a2e5c 50%, #2a5298 100%); min-height: 100vh; }
        header { background: rgba(0, 0, 0, 0.95); color: white; padding: 30px; text-align: center; border-bottom: 3px solid #00ff00; }
        header h1 { font-size: 2.5em; text-shadow: 0 0 20px #00ff00; }
        header a { color: #00ff00; text-decoration: none; }
        .container { max-width: 1000px; margin: 40px auto; padding: 20px; }
        .back-btn { display: inline-block; margin-bottom: 20px; padding: 10px 20px; background: #00ff00; color: #000; border-radius: 4px; font-weight: bold; text-decoration: none; }
        .challenge-box { background: #fff; border-radius: 8px; padding: 25px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); border: 1px solid rgba(0, 255, 0, 0.2); }
        .challenge-box h3 { color: #1e3c72; margin-bottom: 10px; }
        .badge { display: inline-block; padding: 5px 12px; border-radius: 4px; font-weight: bold; font-size: 0.85em; }
        .points { background: #6c5ce7; color: white; margin-left: 10px; }
        .difficulty { background: #FFD700; }
        .encrypted-text { background: #1a1a1a; color: #00ff00; padding: 15px; margin: 15px 0; border-left: 3px solid #00ff00; border-radius: 4px; word-break: break-all; }
        input { width: 100%; padding: 12px; border: 2px solid #00ff00; border-radius: 4px; margin: 10px 0; }
        .btn { background: #00ff00; color: #000; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-top: 15px; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-top: 15px; }
        .hint { background: #fff3cd; color: #856404; padding: 12px; border-left: 4px solid #ffc107; margin-top: 12px; border-radius: 4px; }
    </style>
</head>
<body>
    <header><h1>🔍 Forensics</h1><p>Investigate digital evidence</p><a href="../index.html">&larr; Back</a></header>
    <div class="container">
        <a href="../index.html" class="back-btn">&larr; Back to Hub</a>
        <?php foreach ($challenges as $c): ?>
            <div class="challenge-box">
                <h3><?php echo $c['title']; ?></h3>
                <span class="badge difficulty"><?php echo $c['difficulty']; ?></span>
                <span class="badge points">+<?php echo $c['points']; ?> pts</span>
                <p style="margin-top: 10px;"><?php echo $c['description']; ?></p>
                <div class="encrypted-text"><?php echo $c['encrypted']; ?></div>
                <?php if ($submitted && isset($result) && $result['challenge']['id'] === $c['id']): ?>
                    <div class="<?php echo $result['correct'] ? 'success' : 'error'; ?>">
                        <?php echo $result['correct'] ? '✅ CORRECT! +' . $result['challenge']['points'] . ' pts' : '❌ Incorrect'; ?>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <input type="text" name="answer" placeholder="Your answer..." required>
                    <input type="hidden" name="challenge_id" value="<?php echo $c['id']; ?>">
                    <button type="submit" class="btn">Submit</button>
                </form>
                <div class="hint">💡 <?php echo $c['hint']; ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>