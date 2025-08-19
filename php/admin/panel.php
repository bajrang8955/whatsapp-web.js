<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit;
}
$userId = $_SESSION['userId'];
$loggedIn = false;
$status = @file_get_contents('http://localhost:3000/status/' . urlencode($userId));
$statusData = json_decode($status, true);
if ($statusData && !empty($statusData['loggedIn'])) {
    $loggedIn = true;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to = $_POST['to'];
    $message = $_POST['message'];
    $data = json_encode(['userId' => $userId, 'to' => $to, 'message' => $message]);
    $opts = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => $data
        ]
    ];
    $context = stream_context_create($opts);
    $result = file_get_contents('http://localhost:3000/send', false, $context);
    $sendResult = $result;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>
<h1>Send WhatsApp Message</h1>
<?php if (!$loggedIn): ?>
    <p>Session not authenticated. <a href="login.php">Scan QR code</a>.</p>
<?php else: ?>
    <form method="post">
        <label>To <input type="text" name="to" required></label><br>
        <label>Message <input type="text" name="message" required></label><br>
        <button type="submit">Send</button>
    </form>
    <?php if (isset($sendResult)): ?>
        <pre><?php echo htmlspecialchars($sendResult); ?></pre>
    <?php endif; ?>
<?php endif; ?>
</body>
</html>
