<?php
$pdo = new PDO("mysql:host=localhost;dbname=todo_app", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start();
?>
