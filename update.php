<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = (int)$_POST["id"];

    // Inverser le statut
    $stmt = $pdo->prepare("UPDATE tasks SET is_done = NOT is_done WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

header("Location: index.php");
exit;
?>
