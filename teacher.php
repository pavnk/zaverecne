<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

session_start();

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
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("SELECT e.id, e.file_name
                          FROM exercise e
                          WHERE NOT EXISTS (
                              SELECT 1
                              FROM student_exercise se
                              WHERE e.id = se.exercise_id
                          )");
    $stmt->execute();

    $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo $e->getMessage();
}
$msg = "";

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

    if(isset($_POST['upload'])){
        if (isset($_FILES["latexFile"]) && $_FILES["latexFile"]["error"] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES["latexFile"]["tmp_name"];
            $fileName = $_FILES["latexFile"]["name"];
    
            $uploadPath = "uploads/" . $fileName;
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
    
            if (!is_writable('uploads')) {
                $msg .= $language['dir_not'];
                exit;
            }
    
            $sql = "SELECT COUNT(*) FROM exercise WHERE file_name = :file_name";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":file_name", $fileName, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetchColumn();
    
            if ($count === 0) {
                if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                    $sql = "INSERT INTO exercise (file_name) VALUES (:file_name)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(":file_name", $fileName, PDO::PARAM_STR);
                    $stmt->execute();
                    header("Refresh: 0");
                    exit;
                } else {
                    $msg .= $language['file_fail'];
                }
            } else {
                $msg .= $language['file_fail'];
            }
        } else {
            $msg .= $language['file_fail'];
        }
    }
    if(isset($_POST['exerciseSubmit'])){
        if (isset($_POST['exerciseSubmit'])) {
            $selectedExerciseId = $_POST['exercise'];

            if(isset($_POST['date-start'])){

                if(isset($_POST['date-end'])){

                    if(isset($_POST['max-points'])){
                        $startDate = $_POST['date-start'];
                        $endDate = $_POST['date-end'];
                        $maxPoints = $_POST['max-points'];

                        $stmt = $pdo->prepare("INSERT INTO student_exercise (exercise_id, date_start, date_end, max_points)
                            VALUES (:exercise_id, :date_start, :date_end, :max_points)");

                        $stmt->bindParam(':exercise_id', $selectedExerciseId);
                        $stmt->bindParam(':date_start', $startDate);
                        $stmt->bindParam(':date_end', $endDate);
                        $stmt->bindParam(':max_points', $maxPoints);

                        $stmt->execute();
                        header("Refresh: 0");
                        exit;
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
    <h1><?php echo $language['upload_latex']; ?></h1>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="latexFile" id="latexFile" accept=".tex" required>
                        <label class="custom-file-label" for="latexFile"><?php echo $language['choose_file']; ?></label>
                        <div class="invalid-feedback"><?php echo $language['choose_latex']; ?>.</div>
                    </div>
                </div>
                <button type="submit" name="upload" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>
    <br>
    <div>
        <h1><?php echo $language['assign_exercise'] ?></h1>
        <form method="POST" action="">
            <div class="form-group row">
                <label for="exercise" class="col-sm-4 col-form-label text-right"><?php echo $language['select_exercise']; ?></label>
                <div class="col-sm-8">
                    <select name="exercise" id="exercise" class="form-control">
                        <?php foreach ($exercises as $exercise): ?>
                            <option value="<?php echo $exercise['id']; ?>"><?php echo $exercise['file_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="date-start" class="col-sm-4 col-form-label text-right"><?php echo $language['start_date']; ?>:</label>
                <div class="col-sm-8">
                    <input type="date" id="date-start" name="date-start" class="form-control">
                </div>
            </div>

            <div class="form-group row">
                <label for="date-end" class="col-sm-4 col-form-label text-right"><?php echo $language['end_date']; ?>:</label>
                <div class="col-sm-8">
                    <input type="date" id="date-end" name="date-end" class="form-control">
                </div>
            </div>

            <div class="form-group row">
                <label for="max-points" class="col-sm-4 col-form-label text-right"><?php echo $language['max_points']; ?>:</label>
                <div class="col-sm-8">
                    <input type="number" id="max-points" name="max-points" class="form-control">
                </div>
            </div>
                <button type="submit" name="exerciseSubmit" class="btn btn-primary"><?php echo $language['submit'];?>:</button>
        </form>
    </div>


<div class="container text-center mt-5">
    <h1><?php echo $language['all_students'];?></h1>
    <div id="students"></div>

</div>
<br>

<button id="exportBtn" class="btn btn-primary"><?php echo $language['export_csv'];?></button>

</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/4.9.3/js/tabulator.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>
<script>
    var language = "<?php echo $_SESSION['language']; ?>";
</script>
<script src="studentsTable.js"></script>

<script>
    document.getElementById("exportBtn").addEventListener("click", function(){
    table.download("csv", "students.csv");
});

</script>

</body>
</html>