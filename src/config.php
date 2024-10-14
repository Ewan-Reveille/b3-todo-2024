<?php
define('APP_NAME', 'To-Do List App');


function sanitize($data) {
    return htmlspecialchars(trim($data));
}


function addTask($task) {
    if (!isset($_SESSION['tasks'])) {
        $_SESSION['tasks'] = [];
    }
    $_SESSION['tasks'][] = sanitize($task);
}

function removeTask($index) {
    if (isset($_SESSION['tasks'][$index])) {
        array_splice($_SESSION['tasks'], $index, 1);
    }
}
