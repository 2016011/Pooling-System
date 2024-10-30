<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

include 'db_connection.php';

$polls = $conn->query("SELECT poll_id, question FROM polls")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<style>
        
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        
        .logout-button {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .logout-button:hover {
            background-color: #c0392b;
        }

        .logout-icon {
            margin-right: 5px;
        }
        
        .tabs { display: flex; margin-top: 20px; }
        .tab { padding: 10px 20px; cursor: pointer; background-color: #f1f1f1; border: 1px solid #ccc; margin-right: 5px; font-weight: bold; }
        .tab:hover { background-color: #ddd; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .check-result { cursor: pointer; color: blue; text-decoration: underline; }
    </style>

    <title>Admin Dashboard</title>
    <style>
        .tabs { display: flex; margin-top: 20px; }
        .tab { padding: 10px 20px; cursor: pointer; background-color: #f1f1f1; border: 1px solid #ccc; margin-right: 5px; font-weight: bold; }
        .tab:hover { background-color: #ddd; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .check-result { cursor: pointer; color: blue; text-decoration: underline; }
    </style>
</head>
<body>

<a href="?logout=true" class="logout-button">
        <span class="logout-icon">🔒</span> Logout
    </a>
    
    <h2>Admin Dashboard</h2>

    <div class="tabs">
        <div class="tab" onclick="location.href='create_pool.php'">Create Poll</div>
    </div>

    <h3>All Polls</h3>
    <table>
        <tr>
            <th>Poll ID</th>
            <th>Question</th>
            <th>Actions</th>
        </tr>
        <?php if (empty($polls)): ?>
            <tr>
                <td colspan="3" style="text-align: center;">Nothing else</td>
            </tr>
        <?php else: ?>
            <?php foreach ($polls as $poll): ?>
                <tr>
                    <td><?php echo htmlspecialchars($poll['poll_id']); ?></td>
                    <td><?php echo htmlspecialchars($poll['question']); ?></td>
                    <td>
                        <a href="results.php?poll_id=<?php echo $poll['poll_id']; ?>" class="check-result">Check Result</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>
</html>