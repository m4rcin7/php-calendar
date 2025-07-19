<?php

include 'connection.php';

$successMsg = '';
$errorMsg = '';
$eventsFromDB = [];

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST['action'] ?? '') === "add") {
    $task = trim($_POST['task_name'] ?? '');
    $task_desc  = trim($_POST['task_description	'] ?? '');
    $start = $_POST['start_date'] ?? '';
    $end = $_POST['end_date'] ?? '';

    if ($course && $instructor && $start && $end && $startTime && $endTime) {
        $stmt = $conn->prepare(
            'INSERT INTO appointments (task_name, task_description, start_date, end_date) 
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('ssssss', $task, $task_desc, $start, $end);
        $stmt->execute();
        $stmt->close();

        header("Location: " . $_SERVER["PHP_SELF"] . "?success=1");
        exit;
    } else {
        header("Location: " . $_SERVER["PHP_SELF"] . "?error=1");
        exit;
    }
}
