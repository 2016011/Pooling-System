<?php

session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];

    try {
        $stmt = $conn->prepare("INSERT INTO polls (question) VALUES (:question)");
        $stmt->bindParam(':question', $question);
        $stmt->execute();
        $poll_id = $conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO poll_options (poll_id, option_text) VALUES (:poll_id, :option_text)");
        $stmt->bindParam(':poll_id', $poll_id);
        $stmt->bindParam(':option_text', $option1);
        $stmt->execute();
        $stmt->bindParam(':option_text', $option2);
        $stmt->execute();
        $stmt->bindParam(':option_text', $option3);
        $stmt->execute();

        echo "Poll created successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create a Poll</title>
</head>
<body>
    <h1>Create a New Poll</h1>
    <form action="create_pool.php" method="POST">
        <label for="question">Poll Question:</label>
        <input type="text" name="question" required><br><br>
        
        <label>Option 1:</label>
        <input type="text" name="option1" required><br><br>
        
        <label>Option 2:</label>
        <input type="text" name="option2" required><br><br>
        
        <label>Option 3:</label>
        <input type="text" name="option3" required><br><br>
        
        <button type="submit">Create Poll</button>
    </form>
</body>
</html>