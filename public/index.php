<?php

session_start();
if(!isset($_SESSION['language'])){
    $_SESSION['language'] = "sk";
}
$selectedLanguage = $_SESSION['language'];
$languageFile = 'languages/' . $selectedLanguage . '.php';

require_once($languageFile);

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if ($_SESSION['user_type'] == 'student') {
        header("location: student.php");
        exit();
    } elseif ($_SESSION['user_type'] == 'teacher') {
        header("location: teacher.php");
        exit();
    }
}

require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
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
    if(isset($_POST['loginform'])){
        $sql = "SELECT id, login, password, 'student' AS user_type FROM student WHERE login = :login
    UNION
    SELECT id, login, password, 'teacher' AS user_type FROM teacher WHERE login = :login";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":login", $_POST["login"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch();
                $hashed_password = $row["password"];
                $_SESSION['user_type'] = $row["user_type"];
                if (password_verify($_POST['password'], $hashed_password)) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["login"] = $row['login'];
                    $_SESSION["user_id"] = $row['id']; // pridaná nová línia
                    if ($_SESSION['user_type'] == 'student') {
                        header("location: student.php");
                    } elseif ($_SESSION['user_type'] == 'teacher') {
                        header("location: teacher.php");
                    }
                } else {
                    echo "Wrong name or password.";
                }
            } else {
                echo "Wrong name or password.";
            }
        } else {
            echo "Something went wrong!";
        }

        unset($stmt);
        unset($pdo);
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
    <h1>Login</h1>

    <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="">
            <label for="login">
                <?php echo $language['login_name'];?>:
                <input type="text" name="login" class="form-control" value="" id="login" required>
            </label>
            <span id="login-error"></span>
        </div>
        <br>
        <div class="">
            <label for="password">
                <?php echo $language['password'];?>:
                <input type="password" name="password" class="form-control" value="" id="password" required>
            </label>
            <span id="password-error"></span>
        </div>
        <br>
        <button type="submit" name="loginform" class="btn btn-primary"><?php echo $language['login'];?>:</button>
    </form>
    <p><a href="register.php"><?php echo $language['register'];?></a></p>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/4.9.3/js/tabulator.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>
</body>
</html>
