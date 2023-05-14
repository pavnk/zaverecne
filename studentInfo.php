<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$studentId = $_GET['id'];

if(!isset($_SESSION['language'])){
    $_SESSION['language'] = "sk";
}
$selectedLanguage = $_SESSION['language'];
$languageFile = 'languages/' . $selectedLanguage . '.php';

require_once($languageFile);
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]){
    if($_SESSION["user_type"] == "teacher"){
        $login = $_SESSION["login"];
    } else {
        header('Location: index.php');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}

require_once 'config.php';

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("SELECT e.id, e.file_name
                          FROM exercise e
                          WHERE NOT EXISTS (
                              SELECT 1
                              FROM student_exercise se
                              WHERE e.id = se.exercise_id AND se.student_id = :studentId
                          )");
    $stmt->bindValue(':studentId', $studentId, PDO::PARAM_INT);
    $stmt->execute();

    $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['english'])) {
        $_SESSION['language'] = "en";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
    if (isset($_POST['slovak'])) {
        $_SESSION['language'] = "sk";
        header('Location: ' . $_SERVER['PHP_SELF']);
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
    if(isset($_POST['exerciseSubmit'])){
        if (isset($_POST['exerciseSubmit'])) {
            // Get the selected exercise ID from the dropdown
            $selectedExerciseId = $_POST['exercise'];

            if(isset($_POST['date-start'])){

                if(isset($_POST['date-end'])){

                    if(isset($_POST['max-points'])){
                        $startDate = $_POST['date-start'];
                        $endDate = $_POST['date-end'];
                        $maxPoints = $_POST['max-points'];

                        $stmt = $db->prepare("INSERT INTO student_exercise (student_id, exercise_id, date_start, date_end, max_points)
                            VALUES (:student_id, :exercise_id, :date_start, :date_end, :max_points)");

                        $stmt->bindParam(':student_id', $studentId);
                        $stmt->bindParam(':exercise_id', $selectedExerciseId);
                        $stmt->bindParam(':date_start', $startDate);
                        $stmt->bindParam(':date_end', $endDate);
                        $stmt->bindParam(':max_points', $maxPoints);

                        $stmt->execute();
                    }
                }
            }
        }
    }
}


?>
<!DOCTYPE html>
<html lang="<?php echo $selectedLanguage; ?>">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://unpkg.com/tabulator-tables@5.4.4/dist/css/tabulator.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class=" navbar-collapse justify-content-md-center" id="navbarsExample08">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="./index.php"><?php echo $language['page_name']; ?></a>
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

<div>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $studentId; ?>">
        <label for="exercise">Select an exercise for this student:</label>
        <select name="exercise" id="exercise">
            <?php foreach ($exercises as $exercise): ?>
                <option value="<?php echo $exercise['id']; ?>"><?php echo $exercise['file_name']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="date-start">Start date:</label>
        <input type="date" id="date-start" name="date-start">
        <br>
        <label for="date-end">End date:</label>
        <input type="date" id="date-end" name="date-end">
        <br>
        <label for="max-points">Maximum obtainable points:</label>
        <input type="number" id="max-points" name="max-points">
        <br>
        <input type="submit" name="exerciseSubmit" value="Submit">
    </form>
</div>



<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/4.9.3/js/tabulator.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>
<script src="studentsTable.js"></script>
</body>
</html>
