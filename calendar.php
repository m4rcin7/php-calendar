<?php

include 'connection.php';

$successMsg = '';
$errorMsg = '';
$eventsFromDB = [];

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? '') === 'add') {
    $task = trim($_POST['task_name'] ?? '');
    $task_desc  = trim($_POST['task_description	'] ?? '');
    $start = $_POST['start_date'] ?? '';
    $end = $_POST['end_date'] ?? '';

    if ($task && $task_desc && $start && $end) {
        $stmt = $conn->prepare(
            'INSERT INTO tasks (task_name, task_description, start_date, end_date) 
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('ssss', $task, $task_desc, $start, $end);
        $stmt->execute();
        $stmt->close();

        header("Location: " . $_SERVER["PHP_SELF"] . "?success=1");
        exit;
    } else {
        header("Location: " . $_SERVER["PHP_SELF"] . "?error=1");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? '') === 'edit') {
    $id = $_POST["event_id"] ?? null;
    $task = trim($_POST["task_name"]);
    $task_desc = trim($_POST["task_description"]);
    $start = $_POST["start_date"] ?? '';
    $end = $_POST["end_date"] ?? '';

    if ($id && $task && $task_desc && $start && $end) {
        $stmt = $conn->prepare(
            "UPDATE tasks SET task_name = ?, task_description = ?,  start_date = ?, end_date = ? WHERE id = ?"
        );
        $stmt->bind_param("ssssi", $task, $task_desc, $start, $end, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: " . $_SERVER["PHP_SELF"] . "?success=2");
        exit();
    } else {
        header("Location: " . $_SERVER["PHP_SELF"] . "?error=2");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? '') === 'delete') {
    $id = $_POST['event_id'] ?? null;

    if ($id) {
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id =?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER["PHP_SELF"] . "?sucess=3");
        exit();
    }
}
