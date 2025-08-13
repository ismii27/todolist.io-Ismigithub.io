<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

date_default_timezone_set("Asia/Jakarta");
setlocale(LC_TIME, 'id_ID.UTF-8');

$pdo->query("UPDATE todos SET status='done' WHERE status='pending' AND deadline < NOW()");

$stmt = $pdo->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY deadline ASC");
$stmt->execute([$_SESSION['user_id']]);
$todos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>📋 To-Do List</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #FFDAB9, #FFC0CB, #FFB6C1);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
            color: #5a3d31;
        }
        .container {
            background: rgba(255, 255, 255, 0.7);
            padding: 20px;
            border-radius: 15px;
            backdrop-filter: blur(8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 800px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            overflow: hidden;
        }
        table th, table td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            color: #5a3d31;
        }
        table th {
            background: linear-gradient(135deg, #FFDAB9, #FFC0CB);
        }
        .btn, .btn-logout {
            background: linear-gradient(135deg, #FFCBA4, #FFB6C1);
            color: #5a3d31;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover, .btn-logout:hover {
            background: linear-gradient(135deg, #FFB6C1, #FFCBA4);
            transform: scale(1.05);
        }
        .toast {
            background: linear-gradient(135deg, #FFDAB9, #FFB6C1);
            color: #5a3d31;
            font-weight: bold;
            position: fixed; 
            top: 20px; right: 20px;
            padding: 10px 20px; 
            border-radius: 10px;
            opacity: 0; 
            transition: opacity 0.5s ease-in-out;
            z-index: 999;
        }
        .toast.show { opacity: 1; }
    </style>
</head>
<body>
<div class="container">
    <h2>📋 Daftar Tugas</h2>
    <a href="tambah.php" class="btn">+ Tambah Tugas</a>
    <p>👋 Hai, <?= htmlspecialchars($_SESSION['username']) ?> | 
        <form action="logout.php" method="post" style="display:inline;">
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </p>

    <table>
        <tr>
            <th>Judul</th>
            <th>Deadline</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($todos as $todo): ?>
        <tr id="todo-<?= $todo['id'] ?>">
            <td><?= htmlspecialchars($todo['judul']) ?></td>
            <td><?= strftime('%A, %d %B %Y %H:%M', strtotime($todo['deadline'])) ?></td>
            <td><?= $todo['status'] == 'done' ? 'Selesai' : 'Pending' ?></td>
            <td>
                <?php if ($todo['status'] == 'pending'): ?>
                    <a href="selesai.php?id=<?= $todo['id'] ?>" class="btn" onclick="return tandaiSelesai(event, <?= $todo['id'] ?>)">Selesai</a>
                <?php endif; ?>
                <a href="edit.php?id=<?= $todo['id'] ?>" class="btn">Edit</a>
                <?php if (strtotime($todo['deadline']) <= time()): ?>
                    <a href="hapus.php?id=<?= $todo['id'] ?>" class="btn" onclick="return hapusTugas(event, <?= $todo['id'] ?>)">Hapus</a>
                <?php else: ?>
                    <span style="color: gray;">Tidak Bisa Dihapus</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<audio id="notifSound" src="notif.mp3" preload="auto"></audio>
<div id="toast" class="toast">Tugas selesai!</div>

<script>
function tandaiSelesai(e, id) {
    e.preventDefault();
    const row = document.getElementById('todo-' + id);
    const statusCell = row.querySelectorAll('td')[2]; 
    statusCell.innerHTML = 'Selesai';
    const aksiCell = row.querySelectorAll('td')[3];
    aksiCell.innerHTML = aksiCell.innerHTML.replace(/Selesai/, '');

    const toast = document.getElementById('toast');
    const sound = document.getElementById('notifSound');
    toast.textContent = "Tugas selesai & WA terkirim!";
    toast.classList.add('show');
    sound.play();

    fetch('selesai.php?id=' + id)
        .then(() => {
            setTimeout(() => toast.classList.remove('show'), 3000);
        });
}

function hapusTugas(e, id) {
    e.preventDefault();
    if (!confirm('Yakin hapus tugas ini?')) return;
    window.location.href = 'hapus.php?id=' + id;
}

window.addEventListener('DOMContentLoaded', () => {
    const toast = document.getElementById('toast');
    const sound = document.getElementById('notifSound');

    <?php if (isset($_GET['added'])): ?>
        toast.textContent = "Tugas berhasil ditambahkan!";
        toast.classList.add('show');
        sound.play();
        setTimeout(() => toast.classList.remove('show'), 3000);
    <?php elseif (isset($_GET['edited'])): ?>
        toast.textContent = "Tugas berhasil diedit!";
        toast.classList.add('show');
        sound.play();
        setTimeout(() => toast.classList.remove('show'), 3000);
    <?php endif; ?>
});
</script>
</body>
</html>
