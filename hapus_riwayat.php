<?php
include 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM todos WHERE id = ? AND status = 'done'");
    $stmt->execute([$_GET['id']]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_semua'])) {
    $pdo->query("DELETE FROM todos WHERE status = 'done'");
}

header("Location: riwayat.php");
exit;
