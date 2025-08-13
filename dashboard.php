<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT 
    COUNT(*) as total, 
    SUM(status = 'pending') as pending,
    SUM(status = 'done') as done,
    MIN(deadline) as next_deadline 
    FROM todos WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$data = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>📊 Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>📊 Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?></h2>
    <ul>
        <li>Total Tugas: <?= $data['total'] ?></li>
        <li>🕓 Pending: <?= $data['pending'] ?></li>
        <li>✅ Selesai: <?= $data['done'] ?></li>
        <li>⏰ Deadline Terdekat: <?= $data['next_deadline'] ?? 'Tidak ada' ?></li>
    </ul>
    <a href="index.php" class="btn purple">➡️ Masuk To-Do List</a>
    <form action="logout.php" method="post" style="margin-top: 10px;">
        <button type="submit" class="btn-logout">🚪 Logout</button>
    </form>
</div>
</body>
</html>
