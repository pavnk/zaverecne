<?php
require_once('config.php');

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT se.id, s.name, s.surname, s.id AS student_id, COUNT(se.exercise_id) AS generatedExercises, SUM(se.submited) AS submittedExercises, SUM(se.gotten_points) AS earnedPoints
         FROM student_exercise se
         RIGHT JOIN student s ON se.student_id = s.id
         GROUP BY se.id, s.name, s.surname, s.id";

    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($results);
?>