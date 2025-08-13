<?php
include 'config.php';
$id = $_GET['id'];
$tugas = $pdo->query("SELECT * FROM todos WHERE id=$id")->fetch();

if (isset($_POST['update'])) {
    $stmt = $pdo->prepare("UPDATE todos SET judul=?, deadline=?, status=? WHERE id=?");
    $stmt->execute([$_POST['judul'], $_POST['deadline'], $_POST['status'], $id]);
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Tugas</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
    <h2>✏️ Edit Tugas</h2>
    <form method="post">
        <input type="text" name="judul" value="<?= $tugas['judul'] ?>" required>
        <input type="datetime-local" name="deadline" value="<?= date('Y-m-d\TH:i', strtotime($tugas['deadline'])) ?>" required>
        <select name="status">
            <option value="pending" <?= $tugas['status']=='pending'?'selected':'' ?>>⏳ Pending</option>
            <option value="done" <?= $tugas['status']=='done'?'selected':'' ?>>✅ Selesai</option>
        </select>
        <button type="submit" name="update" class="btn purple">🔄 Update</button>
    </form>
</div>
</body>
</html>
