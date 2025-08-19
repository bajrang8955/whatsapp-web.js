<?php
session_start();
$qr = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    $_SESSION['userId'] = $userId;
    // Check status first
    $status = @file_get_contents('http://localhost:3000/status/' . urlencode($userId));
    $statusData = json_decode($status, true);
    if ($statusData && !empty($statusData['loggedIn'])) {
        header('Location: panel.php');
        exit;
    }
    $data = json_encode(['userId' => $userId]);
    $opts = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => $data
        ]
    ];
    $context = stream_context_create($opts);
    $result = file_get_contents('http://localhost:3000/login', false, $context);
    $resp = json_decode($result, true);
    if (isset($resp['qr'])) {
        $qr = $resp['qr'];
    } elseif (isset($resp['status']) && $resp['status'] === 'authenticated') {
        header('Location: panel.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h1>WhatsApp Login</h1>
<?php if ($qr): ?>
    <p>Scan the QR code with WhatsApp:</p>
    <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?php echo urlencode($qr); ?>&size=200x200" />
<?php else: ?>
    <form method="post">
        <label>User ID <input type="text" name="userId" required></label>
        <button type="submit">Login</button>
    </form>
<?php endif; ?>
</body>
</html>
