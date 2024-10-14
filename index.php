<?php
session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $personne = trim($_POST['personne']);
    
    if (!empty($title) && !empty($description) && !empty($personne)) {
        $_SESSION['tasks'][] = [
            'title' => htmlspecialchars($title),
            'description' => htmlspecialchars($description),
            'personne' => htmlspecialchars($personne)
        ];
    }
}

if (isset($_GET['delete'])) {
    $index = (int)$_GET['delete'];
    if (isset($_SESSION['tasks'][$index])) {
        array_splice($_SESSION['tasks'], $index, 1);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List avec PHP</title>
    <link href="./public/css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="todo-box">
            <h1>Ma To-Do List</h1>
            <form method="POST">
                <input type="text" name="title" placeholder="Titre" required>
                <input type="text" name="description" placeholder="Description" required>
                <input type="text" name="personne" placeholder="Personne attribuée" required>
                <button type="submit" name="submit">Ajouter</button>
            </form>
            <ul>
                <?php if (!empty($_SESSION['tasks'])): ?>
                    <?php foreach ($_SESSION['tasks'] as $index => $task): ?>
                        <li>
                            <span>
                                <strong><?= htmlspecialchars($task['title']) ?></strong><br>
                                <?= htmlspecialchars($task['description']) ?><br>
                                <em><?= htmlspecialchars($task['personne']) ?></em>
                            </span>
                            <a href="?delete=<?= $index ?>">Supprimer</a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="text-gray-500">Aucune tâche ajoutée pour le moment.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</body>
</html>
