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
</head>
<body>
    <h2><?php echo $poll['question']; ?></h2>
    <?php foreach ($options as $option) { 
        $vote_count = $option['vote_count'];
        $percentage = $total_votes ? round(($vote_count / $total_votes) * 100, 2) : 0;
        ?>
        <p><?php echo $option['option_text']; ?>: <?php echo $vote_count; ?> votes (<?php echo $percentage; ?>%)</p>
    <?php } ?>
</body>
</html>