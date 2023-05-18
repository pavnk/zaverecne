<?php
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]){
    if($_SESSION["user_type"] != "teacher"){
        header('Location: index.php');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}



$studentId = $_GET['id'];

require_once 'config.php';

try {
    $db = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
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
<h1 class="mb-4">Student Tasks</h1>
<button class="btn btn-secondary mb-4" onclick="location.href='teacher.php'">Back</button>
<div class="container">
    <?php foreach ($studentTasks as $task): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Task ID: <?php echo $task['exercise_id']; ?></h5>
                <p class="card-text">
                    Task text:
                <div class="math mathjax-latex">
                    \( <?php echo ($task['text']); ?> \)
                </div>
                </p>
                <p class="card-text">
                    Task solution:
                <div class="math mathjax-latex">
                    \( <?php  echo ($task['solution']);  ?> \)
                </div>
                </p>
                <p class="card-text">Earned points: <?php echo $task['points']; ?></p>
                <p class="card-text">Submitted: <?php echo $task['submitted']; ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>


</html>