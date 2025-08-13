<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $judul = trim($_POST['judul']);
    $deadline = $_POST['deadline'];
    $status = $_POST['status'] ?? 'pending';
    $user_id = $_SESSION['user_id'];

    if (!empty($judul) && !empty($deadline) && in_array($status, ['pending', 'done'])) {
        $stmt = $pdo->prepare("INSERT INTO todos (judul, deadline, status, user_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$judul, $deadline, $status, $user_id]);

        header("Location: index.php?added=1");
        exit;
    } else {
        $error = "Judul, deadline, dan status wajib diisi dengan benar.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>📝 Tambah Tugas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>📝 Tambah Tugas</h2>

    <?php if (!empty($error)): ?>
        <p style="color: red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="judul" placeholder="Judul Tugas" required>
        <input type="datetime-local" name="deadline" required>
        
        <select name="status" required>
            <option value="pending">⏳ Pending</option>
            <option value="done">✅ Selesai</option>
        </select>
        
        <button type="submit" name="simpan" class="btn purple">💾 Simpan</button>
    </form>
</div>
</body>
</html>
