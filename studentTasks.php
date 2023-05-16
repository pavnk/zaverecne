<?php
session_start();

$studentId = $_GET['id'];

require_once 'config.php';

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("SELECT * FROM task WHERE student_id = :studentId");
    $stmt->bindValue(':studentId', $studentId, PDO::PARAM_INT);
    $stmt->execute();

    $studentTasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo $e->getMessage();
}

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

    <title>Student Tasks</title>
</head>
<body>
    <h1>Student Tasks</h1>
    <button style="background-color:#c4c2bb" class="btn" onclick="location.href='teacher.php'">Back</button>
    <?php foreach ($studentTasks as $task): ?>
        <p>
            Task ID: <?php echo $task['exercise_id']; ?><br>
            Task text: 
            <div class="math">
            <?php echo ($task['text']); ?>
            </div>
            Task solution: 
            <div class="math">
            <?php  echo ($task['solution']);  ?>
            </div>
            Earned points: <?php echo $task['points']; ?><br>
            Submitted: <?php echo $task['submitted']; ?><br>
            
        </p>
    <?php endforeach; ?>
</body>
</html>



