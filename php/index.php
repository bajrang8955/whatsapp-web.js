<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    $to = $_POST['to'];
    $message = $_POST['message'];
    $data = json_encode(['userId' => $userId, 'to' => $to, 'message' => $message]);
    $ch = curl_init('http://localhost:3000/send');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $result = curl_exec($ch);
    curl_close($ch);
    echo "<pre>$result</pre>";
}
?>
<form method="post">
    <label>User ID <input type="text" name="userId" required></label><br>
    <label>To <input type="text" name="to" required></label><br>
    <label>Message <input type="text" name="message" required></label><br>
    <button type="submit">Send</button>
</form>
