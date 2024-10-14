<?php
session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $task = trim($_POST['task']);
    if (!empty($task)) {
        $_SESSION['tasks'][] = $task;
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
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-4">Ma To-Do List</h1>

        <form method="POST" class="flex mb-4">
            <input type="text" name="task" class="flex-1 p-2 border border-gray-300 rounded-l focus:outline-none" placeholder="Ajouter une tâche..." required>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r hover:bg-blue-600 focus:outline-none">Ajouter</button>
        </form>

        <ul class="list-none space-y-2">
            <?php if (!empty($_SESSION['tasks'])): ?>
                <?php foreach ($_SESSION['tasks'] as $index => $task): ?>
                    <li class="bg-gray-200 p-2 rounded flex justify-between items-center">
                        <span><?= htmlspecialchars($task) ?></span>
                        <a href="?delete=<?= $index ?>" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Supprimer</a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="text-gray-500 text-center">Aucune tâche ajoutée pour le moment.</li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
