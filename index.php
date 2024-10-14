<?php
session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

$editIndex = -1;
$errorMessage = '';

if (isset($_GET['edit'])) {
    $editIndex = (int)$_GET['edit'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $personne = trim($_POST['personne']);
    $startDate = trim($_POST['start_date']);
    $endDate = trim($_POST['end_date']);
    
    // Validation des dates côté serveur
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
                'status' => 'Pas encore démarré' // Par défaut
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
            case 'Pas encore démarré':
                $_SESSION['tasks'][$index]['status'] = 'En cours';
                break;
            case 'En cours':
                $_SESSION['tasks'][$index]['status'] = 'Terminé';
                break;
            case 'Terminé':
                $_SESSION['tasks'][$index]['status'] = 'Pas encore démarré';
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
    <script>
        function validateForm() {
            var startDate = document.querySelector('input[name="start_date"]').value;
            var endDate = document.querySelector('input[name="end_date"]').value;
            
            if (new Date(endDate) < new Date(startDate)) {
                alert("La date de fin ne peut pas être inférieure à la date de début.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="todo-box">
            <h1>Ma To-Do List</h1>
            <?php if ($errorMessage): ?>
                <p style="color: red;"><?= $errorMessage ?></p>
            <?php endif; ?>
            <form method="POST" onsubmit="return validateForm()">
                <input type="text" name="title" placeholder="Titre" required value="<?= ($editIndex !== -1) ? htmlspecialchars($_SESSION['tasks'][$editIndex]['title']) : '' ?>">
                <input type="text" name="description" placeholder="Description" required value="<?= ($editIndex !== -1) ? htmlspecialchars($_SESSION['tasks'][$editIndex]['description']) : '' ?>">
                <input type="text" name="personne" placeholder="Personne attribuée" required value="<?= ($editIndex !== -1) ? htmlspecialchars($_SESSION['tasks'][$editIndex]['personne']) : '' ?>">
                
                <!-- Champs pour la date de début et de fin -->
                <label for="start_date">Date de début :</label>
                <input type="date" name="start_date" required value="<?= ($editIndex !== -1) ? htmlspecialchars($_SESSION['tasks'][$editIndex]['start_date']) : '' ?>">
                
                <label for="end_date">Date de fin :</label>
                <input type="date" name="end_date" required value="<?= ($editIndex !== -1) ? htmlspecialchars($_SESSION['tasks'][$editIndex]['end_date']) : '' ?>">
                
                <button type="submit" name="submit"><?= ($editIndex !== -1) ? 'Modifier' : 'Ajouter' ?></button>
            </form>
            <ul>
                <?php if (!empty($_SESSION['tasks'])): ?>
                    <?php foreach ($_SESSION['tasks'] as $index => $task): ?>
                        <li>
                            <span>
                                <strong>Titre : <?= htmlspecialchars($task['title']) ?></strong><br>
                                Description : <?= htmlspecialchars($task['description']) ?><br>
                                <em>Personne : <?= htmlspecialchars($task['personne']) ?></em><br>
                                Date de début : <?= htmlspecialchars($task['start_date']) ?><br>
                                Date de fin : <?= htmlspecialchars($task['end_date']) ?><br>
                                <strong>Statut : <?= htmlspecialchars($task['status']) ?></strong>
                            </span>
                            <a href="?toggle_status=<?= $index ?>">Changer le statut</a>
                            <a href="?edit=<?= $index ?>">Modifier</a>
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
