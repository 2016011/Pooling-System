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
</head>
<body>
    <h2><?php echo $poll['question']; ?></h2>
    <form action="vote.php?poll_id=<?php echo $poll_id; ?>" method="POST">
        <?php foreach ($options as $option) { ?>
            <input type="radio" name="option_id" value="<?php echo $option['option_id']; ?>" required>
            <?php echo $option['option_text']; ?><br>
        <?php } ?>
        <button type="submit">Vote</button>
    </form>
</body>
</html>