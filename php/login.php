<?php
$userId = $_GET['userId'] ?? '';
if ($userId) {
    $data = json_encode(['userId' => $userId]);
    $ch = curl_init('http://localhost:3000/login');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $result = curl_exec($ch);
    curl_close($ch);
    $resp = json_decode($result, true);
    if (isset($resp['qr'])) {
        echo "<img src='https://api.qrserver.com/v1/create-qr-code/?data=".urlencode($resp['qr'])."&size=200x200' />";
    } else {
        echo "<pre>$result</pre>";
    }
}
?>
<form method="get">
    <label>User ID <input type="text" name="userId" required></label>
    <button type="submit">Login</button>
</form>
