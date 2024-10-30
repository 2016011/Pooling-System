<?php

session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include 'db_connection.php';

$poll_id = $_GET['poll_id'];

$poll = $conn->query("SELECT question FROM polls WHERE poll_id = $poll_id")->fetch(PDO::FETCH_ASSOC);

$total_votes = $conn->query("SELECT COUNT(*) as total_votes FROM poll_votes WHERE poll_id = $poll_id")->fetch(PDO::FETCH_ASSOC)['total_votes'];

$options = $conn->query("SELECT poll_options.option_text, poll_options.option_id, COUNT(poll_votes.option_id) as vote_count
                         FROM poll_options 
                         LEFT JOIN poll_votes ON poll_options.option_id = poll_votes.option_id
                         WHERE poll_options.poll_id = $poll_id
                         GROUP BY poll_options.option_id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Poll Results</title>
    <style>
    
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 500px;
            background-color: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            font-size: 22px;
            margin-bottom: 20px;
        }

        .option {
            margin: 10px 0;
        }

        .option-text {
            font-weight: bold;
            color: #555;
        }

        .vote-count {
            font-size: 14px;
            color: #777;
        }

        .bar-container {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 4px;
            margin-top: 5px;
        }

        .bar {
            height: 10px;
            background-color: #4CAF50;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo $poll['question']; ?></h2>
        <?php foreach ($options as $option) { 
            $vote_count = $option['vote_count'];
            $percentage = $total_votes ? round(($vote_count / $total_votes) * 100, 2) : 0;
        ?>
            <div class="option">
                <span class="option-text"><?php echo htmlspecialchars($option['option_text']); ?></span>
                <span class="vote-count"><?php echo $vote_count; ?> votes (<?php echo $percentage; ?>%)</span>
                <div class="bar-container">
                    <div class="bar" style="width: <?php echo $percentage; ?>%;"></div>
                </div>
            </div>
        <?php } ?>
    </div>
</body>
</html>