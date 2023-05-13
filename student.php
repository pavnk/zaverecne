<?php
session_start();

if(!isset($_SESSION['language'])){
    $_SESSION['language'] = "sk";
}
$selectedLanguage = $_SESSION['language'];
$languageFile = 'languages/' . $selectedLanguage . '.php';

require_once($languageFile);
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]){
    if($_SESSION["user_type"] == "student"){
        $login = $_SESSION["login"];
    } else {
        header('Location: index.php');
        exit();
    }
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

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/4.9.3/js/tabulator.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>
</body>
</html>
