<?php
session_start();

if(!isset($_SESSION['language'])){
    $_SESSION['language'] = "sk";
}
$selectedLanguage = $_SESSION['language'];
$languageFile = 'languages/' . $selectedLanguage . '.php';
require_once($languageFile);

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['english'])) {
        $_SESSION['language'] = "en";
        header('Location: studentTasks.php?id=' . $_GET['id']);
        exit();
    }
    if (isset($_POST['slovak'])) {
        $_SESSION['language'] = "sk";
        header('Location: studentTasks.php?id=' . $_GET['id']);
        exit();
    }
    if(isset($_POST['logout'])){
        if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]){
            session_unset();
            session_destroy();

            session_start();
            $_SESSION['language'] = $selectedLanguage;
            header('location:index.php');
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <title><?php echo $language['tasks_page']; ?></title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class=" navbar-collapse justify-content-md-center" id="navbarsExample08">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="./index.php"><?php echo $language['main_page']; ?></a>
            </li>
            <li>
                <a class="nav-link" href="./Documentation.php"><?php echo $language['documentation']; ?></a>
            </li>
        </ul>
    </div>
    <div class="navbar-collapse justify-content-md-center" id="navbarsExample08">
        <ul class="navbar-nav">
            <li class="nav-item">
                <form action="#" method="post">
                    <input type="hidden" name="logout" value="en">
                    <button type="submit"><?php echo $language['logout'];?></button>
                </form>
            </li>
            <li class="nav-item">
                <form action="#" method="post">
                    <input type="hidden" name="english" value="en">
                    <button type="submit"><img src="./images/enflag.png" alt="English Flag"></button>
                </form>
            </li>
            <li class="nav-item">
                <form action="#" method="post">
                    <input type="hidden" name="slovak" value="sk">
                    <button type="submit"><img src="./images/skflag.png" alt="Slovak Flag"></button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<div class="container text-center mt-5">
    <h1 class="mb-4"><?php echo $language['tasks_page']; ?></h1>
    <button class="btn btn-secondary mb-4" onclick="location.href='teacher.php'"><?php echo $language['back']; ?></button>
        <?php foreach ($studentTasks as $task): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $language['task']; ?>: <?php echo $task['exercise_id']; ?></h5>
                    <p class="card-text">
                        <?php echo $language['task_text']; ?>:
                    <div class="math mathjax-latex">
                        <?php echo ($task['text']); ?>
                    </div>
                    </p>
                    <p class="card-text">
                        <?php echo $language['task_solution']; ?>:
                    <div class="math mathjax-latex">
                        \( <?php  echo ($task['solution']);  ?> \)
                    </div>
                    </p>
                    <p class="card-text"><?php echo $language['earned_points']; ?>: <?php echo $task['points']; ?></p>
                    <p class="card-text"><?php echo $language['submitted']; ?>: <?php echo $task['submitted']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
</div>
</body>


</html>