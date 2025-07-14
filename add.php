<?php
require_once 'session.php';
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["title"])) {
    $title = trim($_POST["title"]);
    $priority = $_POST["priority"] ?? "Moyenne";

    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, priority) VALUES (:user_id, :title, :priority)");
    $stmt->execute([
        'user_id' => $_SESSION["user_id"],
        'title' => htmlspecialchars($title),
        'priority' => $priority
    ]);
}

header("Location: index.php");
exit;
