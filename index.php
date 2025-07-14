<?php
require_once 'session.php';
require_once 'db.php';

$user_id = $_SESSION["user_id"];
$filtre = $_GET['filtre'] ?? 'toutes';

$sql = "SELECT * FROM tasks WHERE user_id = :user_id";
$params = ['user_id' => $user_id];

if ($filtre === "terminees") {
    $sql .= " AND is_done = 1";
} elseif ($filtre === "encours") {
    $sql .= " AND is_done = 0";
}

$sql .= " ORDER BY 
    CASE priority
        WHEN 'Haute' THEN 1
        WHEN 'Moyenne' THEN 2
        WHEN 'Basse' THEN 3
    END,
    created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>📝 Bienvenue, <?= htmlspecialchars($_SESSION["username"]) ?></h1>
        <a href="logout.php" class="logout-btn">Déconnexion</a>

        <form action="add.php" method="POST" class="task-form">
            <input type="text" name="title" placeholder="Nouvelle tâche..." required>
            <select name="priority">
                <option value="Haute">🔴 Haute</option>
                <option value="Moyenne" selected>🟡 Moyenne</option>
                <option value="Basse">🟢 Basse</option>
            </select>
            <button type="submit">Ajouter</button>
        </form>

        <div class="filters">
            <a href="?filtre=toutes" class="<?= $filtre === 'toutes' ? 'active' : '' ?>">Toutes</a>
            <a href="?filtre=encours" class="<?= $filtre === 'encours' ? 'active' : '' ?>">En cours</a>
            <a href="?filtre=terminees" class="<?= $filtre === 'terminees' ? 'active' : '' ?>">Terminées</a>
        </div>

        <ul class="task-list">
            <?php foreach ($tasks as $task): ?>
                <li class="<?= $task['is_done'] ? 'done' : '' ?>">
                    <form action="update.php" method="POST" class="inline-form">
                        <input type="hidden" name="id" value="<?= $task['id'] ?>">
                        <button type="submit" class="check-btn"><?= $task['is_done'] ? '✅' : '🔲' ?></button>
                    </form>
                    <span>
                        <?= htmlspecialchars($task['title']) ?>
                        <small class="priority <?= strtolower($task['priority']) ?>">[<?= $task['priority'] ?>]</small>
                    </span>
                    <form action="delete.php" method="POST" class="inline-form">
                        <input type="hidden" name="id" value="<?= $task['id'] ?>">
                        <button type="submit" class="delete-btn">🗑️</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
