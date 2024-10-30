<?php
include 'db_connection.php';

$poll_id = $_GET['poll_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $option_id = $_POST['option_id'];

    try {
        $stmt = $conn->prepare("INSERT INTO poll_votes (poll_id, option_id) VALUES (:poll_id, :option_id)");
        $stmt->bindParam(':poll_id', $poll_id);
        $stmt->bindParam(':option_id', $option_id);
        $stmt->execute();

        echo "Thank you for voting!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    exit;
}

$poll = $conn->query("SELECT * FROM polls WHERE poll_id = $poll_id")->fetch(PDO::FETCH_ASSOC);
$options = $conn->query("SELECT * FROM poll_options WHERE poll_id = $poll_id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vote in Poll</title>
    <style>
       
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h2 {
            color: #333;
            font-size: 22px;
            margin-bottom: 20px;
        }

        .option {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }

        .option input[type="radio"] {
            margin-right: 10px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo $poll['question']; ?></h2>
        <form action="vote.php?poll_id=<?php echo $poll_id; ?>" method="POST">
            <?php foreach ($options as $option) { ?>
                <div class="option">
                    <input type="radio" name="option_id" value="<?php echo $option['option_id']; ?>" required>
                    <label><?php echo htmlspecialchars($option['option_text']); ?></label>
                </div>
            <?php } ?>
            <button type="submit">Vote</button>
        </form>
    </div>
</body>
</html>