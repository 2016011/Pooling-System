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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background-color: #fff;
            padding: 20px 30px;
            width: 100%;
            max-width: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Create a New Poll</h1>
    <form action="create_pool.php" method="POST">
        <label for="question">Poll Question:</label>
        <input type="text" name="question" required>
        
        <label>Option 1:</label>
        <input type="text" name="option1" required>
        
        <label>Option 2:</label>
        <input type="text" name="option2" required>
        
        <label>Option 3:</label>
        <input type="text" name="option3" required>
        
        <button type="submit">Create Poll</button>
    </form>
</body>
</html>