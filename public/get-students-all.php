<?php
require_once('config.php');

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT s.name, s.surname, s.id AS student_id,
       COUNT(t.id) AS generatedTasks,
       SUM(t.submitted) AS submittedTasks,
       SUM(t.points) AS earnedPoints
    FROM student s
    LEFT JOIN task t ON s.id = t.student_id
    GROUP BY s.id";

    $stmt = $pdo->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($results);
?>