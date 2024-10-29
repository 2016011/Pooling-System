<?php

$host = 'database-test-3.c5y4k2g64dsp.us-east-2.rds.amazonaws.com'; 
$dbname = 'poll_db'; 
$username = 'admin2'; 
$password = 'naveenT2024'; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully"; 
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>