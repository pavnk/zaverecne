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

    if(isset($_POST['upload'])){
        if (isset($_FILES["latexFile"]) && $_FILES["latexFile"]["error"] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES["latexFile"]["tmp_name"];
            $fileName = $_FILES["latexFile"]["name"];
    
            $uploadPath = "uploads/" . $fileName;
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
    
            if (!is_writable('uploads')) {
                echo 'Directory not writable';
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
                    echo "File uploaded successfully.";
                } else {
                    echo "File moving failed.";
                }
            } else {
                echo "File already exists in database.";
            }
        } else {
            echo 'File upload error: ' . $_FILES["latexFile"]["error"];
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
    <h1>Upload latex file</h1>
<div>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data" class="form-control">
        <input type="file" name="latexFile" id="latexFile" accept=".tex" required>
        <input type="submit" name="upload" value="Upload">
    </form>
</div>

<div class="container text-center mt-5">
    <h1>All students</h1>
    <div id="students"></div>

</div>
<br>

<button id="exportBtn" class="btn btn-primary">Export to CSV</button>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/4.9.3/js/tabulator.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>
<script src="studentsTable.js"></script>
<script>
    document.getElementById("exportBtn").addEventListener("click", function(){
    table.download("csv", "students.csv");
});

</script>

</body>
</html>