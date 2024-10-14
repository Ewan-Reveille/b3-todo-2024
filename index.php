<?php

const STATUSES = [
    'todo' => "Pas encore démarré",
    'doing' => "En cours",
    'done' => 'Terminé',
];

session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

$editIndex = -1;
$errorMessage = '';
$filteredTasks = $_SESSION['tasks'];
$startDateFilter = '';

if (isset($_GET['edit'])) {
    $editIndex = (int)$_GET['edit'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter'])) {
    $startDateFilter = trim($_POST['start_date_filter']);
    if (!empty($startDateFilter)) {
        $filteredTasks = array_filter($_SESSION['tasks'], function ($task) use ($startDateFilter) {
            return $task['start_date'] === $startDateFilter;
        });
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $personne = trim($_POST['personne']);
    $startDate = trim($_POST['start_date']);
    $endDate = trim($_POST['end_date']);
    
    if (strtotime($endDate) < strtotime($startDate)) {
        $errorMessage = 'La date de fin ne peut pas être inférieure à la date de début.';
    } else {
        if (!empty($title) && !empty($description) && !empty($personne) && !empty($startDate) && !empty($endDate)) {
            $taskData = [
                'title' => htmlspecialchars($title),
                'description' => htmlspecialchars($description),
                'personne' => htmlspecialchars($personne),
                'start_date' => htmlspecialchars($startDate),
                'end_date' => htmlspecialchars($endDate),
                'status' => STATUSES['todo']
            ];
            
            if ($editIndex === -1) {
                $_SESSION['tasks'][] = $taskData;
            } else {
                $_SESSION['tasks'][$editIndex] = $taskData;
            }
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

if (isset($_GET['delete'])) {
    $index = (int)$_GET['delete'];
    if (isset($_SESSION['tasks'][$index])) {
        array_splice($_SESSION['tasks'], $index, 1);
    }
}

if (isset($_GET['toggle_status'])) {
    $index = (int)$_GET['toggle_status'];
    if (isset($_SESSION['tasks'][$index])) {
        $currentStatus = $_SESSION['tasks'][$index]['status'];
        switch ($currentStatus) {
            case STATUSES['todo']:
                $_SESSION['tasks'][$index]['status'] = STATUSES['doing'];
                break;
            case STATUSES['doing']:
                $_SESSION['tasks'][$index]['status'] = STATUSES['done'];
                break;
            case STATUSES['done']:
                $_SESSION['tasks'][$index]['status'] = STATUSES['todo'];
                break;
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
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
            <?php if ($errorMessage): ?>
                <p style="color: red;"><?= $errorMessage ?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="title" placeholder="Titre" required value="<?= ($editIndex !== -1) ? htmlspecialchars($_SESSION['tasks'][$editIndex]['title']) : '' ?>">
                <input type="text" name="description" placeholder="Description" required value="<?= ($editIndex !== -1) ? htmlspecialchars($_SESSION['tasks'][$editIndex]['description']) : '' ?>">
                <input type="text" name="personne" placeholder="Personne attribuée" required value="<?= ($editIndex !== -1) ? htmlspecialchars($_SESSION['tasks'][$editIndex]['personne']) : '' ?>">
                
                <label for="start_date">Date de début :</label>
                <input type="date" name="start_date" required value="<?= ($editIndex !== -1) ? htmlspecialchars($_SESSION['tasks'][$editIndex]['start_date']) : '' ?>">
                
                <label for="end_date">Date de fin :</label>
                <input type="date" name="end_date" required value="<?= ($editIndex !== -1) ? htmlspecialchars($_SESSION['tasks'][$editIndex]['end_date']) : '' ?>">
                
                <button type="submit" name="submit"><?= ($editIndex !== -1) ? 'Modifier' : 'Ajouter' ?></button>
            </form>

            <form method="POST" style="margin-top: 20px;">
                <h2>Filtrer par date de début</h2>
                <label for="start_date_filter">Date de début :</label>
                <input type="date" name="start_date_filter" value="<?= htmlspecialchars($startDateFilter) ?>">
                
                <button type="submit" name="filter">Filtrer</button>
            </form>

            <div class="task-columns">
                <div class="column-todo">
                    <h2><?= STATUSES['todo'] ?></h2>
                    <?php foreach ($filteredTasks as $index => $task): ?>
                        <?php if ($task['status'] === STATUSES['todo']): ?>
                            <div class="task-item">
                                <strong><?= htmlspecialchars($task['title']) ?></strong><br>
                                Description: <?= htmlspecialchars($task['description']) ?><br>
                                Personne: <?= htmlspecialchars($task['personne']) ?><br>
                                Début: <?= htmlspecialchars($task['start_date']) ?><br>
                                Fin: <?= htmlspecialchars($task['end_date']) ?><br>
                                <a href="?toggle_status=<?= $index ?>">Changer le statut</a>
                                <a href="?edit=<?= $index ?>">Modifier</a>
                                <a href="?delete=<?= $index ?>">Supprimer</a>
                                <a href="?debug=<?= $index ?>">Débugger</a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="column-doing">
                    <h2><?= STATUSES['doing'] ?></h2>
                    <?php foreach ($filteredTasks as $index => $task): ?>
                        <?php if ($task['status'] === STATUSES['doing']): ?>
                            <div class="task-item">
                                <strong><?= htmlspecialchars($task['title']) ?></strong><br>
                                Description: <?= htmlspecialchars($task['description']) ?><br>
                                Personne: <?= htmlspecialchars($task['personne']) ?><br>
                                Début: <?= htmlspecialchars($task['start_date']) ?><br>
                                Fin: <?= htmlspecialchars($task['end_date']) ?><br>
                                <a href="?toggle_status=<?= $index ?>">Changer le statut</a>
                                <a href="?edit=<?= $index ?>">Modifier</a>
                                <a href="?delete=<?= $index ?>">Supprimer</a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="column-done">
                    <h2><?= STATUSES['done'] ?></h2>
                    <?php foreach ($filteredTasks as $index => $task): ?>
                        <?php if ($task['status'] === STATUSES['done']): ?>
                            <div class="task-item">
                                <strong><?= htmlspecialchars($task['title']) ?></strong><br>
                                Description: <?= htmlspecialchars($task['description']) ?><br>
                                Personne: <?= htmlspecialchars($task['personne']) ?><br>
                                Début: <?= htmlspecialchars($task['start_date']) ?><br>
                                Fin: <?= htmlspecialchars($task['end_date']) ?><br>
                                <a href="?toggle_status=<?= $index ?>">Changer le statut</a>
                                <a href="?edit=<?= $index ?>">Modifier</a>
                                <a href="?delete=<?= $index ?>">Supprimer</a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="footer">
            <h2>MyDigitalSchool 2024</h2>
        </div>
    </footer>
</body>
</html>
