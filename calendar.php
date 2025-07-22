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
    $startTime = $_POST['start_time'] ?? '';
    $endTime = $_POST['end_time'] ?? '';

    if ($task && $task_desc && $start && $end && $startTime && $endTime) {
        $stmt = $conn->prepare(
            'INSERT INTO tasks (task_name, task_description, start_date, end_date, start_time, end_time) 
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('ssssss', $task, $task_desc, $start, $end, $startTime, $endTime);
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
    $startTime = $_POST["start_time"] ?? '';
    $endTime = $_POST["end_time"] ?? '';

    if ($id && $task && $task_desc && $start && $end && $startTime && $endTime) {
        $stmt = $conn->prepare(
            "UPDATE tasks SET task_name = ?, task_description = ?,  start_date = ?, end_date = ?, start_time = ?, end_time = ? WHERE id = ?"
        );
        $stmt->bind_param("ssssssi", $task, $task_desc, $start, $end, $startTime, $endTime, $id);
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
        header("Location: " . $_SERVER["PHP_SELF"] . "?success=3");
        exit();
    }
}

if (isset($_GET["success"])) {
    $successMsg = match ($_GET["success"]) {
        '1' => "✅ Task added successfully!",
        '2' => "🔄 Task updated successfully!",
        '3' => "❌ Task deleted successfully!",
        default => ''
    };
}

if (isset($_GET["error"])) {
    $errorMsg = '❗ Error occured. Check your input!';
}

$result = $conn->query("SELECT *FROM tasks");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $start = new DateTime($row["start_date"]);
        $end = new DateTime($row["end_date"]);

        while ($start <= $end) {
            $eventFromDB[] = [
                'id' => $row["id"],
                "title" => "{$row['task_name']} - {$row['task_description']}",
                "date" => $start->format('Y-m-d'),
                "start" => $row["start_date"],
                "end" => $row['end_date'],
                "start_time" => $row["start_time"],
                "end_time" => $row["end_time"],
            ];

            $start->modify("+1 day");
        }
    }
}


$conn->close();
