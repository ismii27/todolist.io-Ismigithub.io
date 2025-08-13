<?php
date_default_timezone_set("Asia/Jakarta");
include 'config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM todos WHERE id = ?");
$stmt->execute([$id]);
$tugas = $stmt->fetch();

if ($tugas) {
    $deadline = strtotime($tugas['deadline']);
    $now = time();

    if ($deadline <= $now) {
        $delete = $pdo->prepare("DELETE FROM todos WHERE id = ?");
        $delete->execute([$id]);
    }
}

header("Location: index.php");
exit;
?>
