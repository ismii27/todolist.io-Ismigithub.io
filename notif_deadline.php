<?php

date_default_timezone_set("Asia/Jakarta");
include 'config.php';

$token = "mFfCCTuhE7ZqLFGbtohU"; 
$nomor_tujuan = "6285695313720"; 

$stmt = $pdo->prepare("SELECT * FROM todos WHERE status = 'pending' AND TIMESTAMPDIFF(MINUTE, NOW(), deadline) <= 10 AND TIMESTAMPDIFF(MINUTE, NOW(), deadline) > 0");
$stmt->execute();
$tugas = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($tugas as $t) {
    $pesan = "⏳ *Pengingat Tugas* ⏳\n\n"
           . "📌 Judul: {$t['judul']}\n"
           . "🕒 Deadline: {$t['deadline']}\n"
           . "⚠️ Segera selesaikan sebelum waktu habis!";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.fonnte.com/send");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        "target" => $nomor_tujuan,
        "message" => $pesan
    ]);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: $token"
    ]);
    $result = curl_exec($ch);
    curl_close($ch);

    echo "Pesan WA terkirim untuk tugas: {$t['judul']}\n";
}
?>
