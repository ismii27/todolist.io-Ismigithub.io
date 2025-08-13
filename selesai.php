<?php
require 'config.php';
date_default_timezone_set("Asia/Jakarta");
setlocale(LC_TIME, 'id_ID.UTF-8');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    http_response_code(900);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM todos WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$todo = $stmt->fetch();

if (!$todo) {
    http_response_code(404);
    exit;
}

$pdo->prepare("UPDATE todos SET status = 'done' WHERE id = ?")->execute([$id]);

$judul = $todo['judul'];
$deadline = strftime('%A, %d %B %Y %H:%M', strtotime($todo['deadline']));
$pesan = "✅ *Tugas Selesai!*\n\n📝 Judul: $judul\n⏰ Deadline: $deadline\n\nSelamat, tugas sudah selesai 🎉";

$token = "mFfCCTuhE7ZqLFGbtohU";
$target = "6285695313720";
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.fonnte.com/send",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => array(
        'target' => $target,
        'message' => $pesan,
    ),
    CURLOPT_HTTPHEADER => array(
        "Authorization: $token"
    ),
));
$response = curl_exec($curl);
curl_close($curl);

echo json_encode(["status" => "success"]);
