<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if(!isset($_SESSION['language'])){
    $_SESSION['language'] = "sk";
}
$selectedLanguage = $_SESSION['language'];
$languageFile = 'languages/' . $selectedLanguage . '.php';

require_once($languageFile);

require_once 'config.php';
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
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
    if(isset($_POST['register'])){
        $msg = "";
        $errmsg = "";

        if (isset($_POST['user_role'])) {
            $selectedUserRole = $_POST['user_role'];
            if (studentExist($pdo, $_POST['login']) === true) {
                $errmsg .="<p>". $language['user_exists']."</p>";
            }
            if (teacherExists($pdo, $_POST['login']) === true) {
                $errmsg .="<p>". $language['user_exists']."</p>";
            }
        }

        if (checkEmpty($_POST['login']) === true) {
            $errmsg .= "<p>".$language['login_not']."</p>";
        } elseif (checkLength($_POST['login'], 6,32) === false) {
            $errmsg .= "<p>".$language['login_req']."</p>";
        } elseif (checkUsername($_POST['login']) === false) {
            $errmsg .= "<p>".$language['login_req']."</p>";
        }


        if(checkEmpty($_POST['password']) === true){
            $errmsg .="<p>".$language['password_not']."</p>";
        } elseif(checkLength($_POST['password'],6,32) === false) {
            $errmsg .= "<p>".$language['password_req']."</p>";
        } elseif(checkPassword($_POST['password']) === true) {
            $errmsg .= "<p>".$language['password_req']."</p>";
        }

        if(checkEmpty($_POST['name']) === true){
            $errmsg .="<p>".$language['name_not']."</p>";
        } elseif(checkLength($_POST['name'],1,32) === false) {
            $errmsg .= "<p>".$language['name_req']."</p>";
        } elseif(checkName($_POST['name']) === false){
            $errmsg .= "<p>".$language['name_req']."</p>";
        }

        if(checkEmpty($_POST['surname']) === true){
            $errmsg .="<p>".$language['surname_not']."</p>";
        } elseif(checkLength($_POST['surname'],1,32) === false) {
            $errmsg .= "<p>".$language['surname_req']."</p>";
        } elseif(checkName($_POST['surname']) === false){
            $errmsg .= "<p>".$language['surname_req']."</p>";
        }

        if (empty($errmsg)) {
            $sql = "INSERT INTO " . $selectedUserRole . " (login, password, name, surname) VALUES (:login, :password, :name, :surname)";

            $login = $_POST['login'];
            $hashed_password = password_hash($_POST['password'], PASSWORD_ARGON2ID);
            $name = $_POST['name'];
            $surname = $_POST['surname'];

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(":login", $login, PDO::PARAM_STR);
            $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":surname", $surname, PDO::PARAM_STR);
            $stmt->execute();
            unset($stmt);
            $msg = $language['registration_success'];
        } else {

        }
        unset($pdo);
    }
}
function checkEmpty($field) {
    if (empty(trim($field))) {
        return true;
    }
    return false;
}
function checkLength($field, $min, $max) {
    $string = trim($field);
    $length = strlen($string);
    if ($length < $min || $length > $max) {
        return false;
    }
    return true;
}
function checkUsername($username) {
    if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($username))) {
        return false;
    }
    return true;
}
function checkPassword($password){
    if (!preg_match('/^[a-zA-Z0-9_!@#$%&*]+$/', $password)) {
        return true;
    }
    return false;
}
function checkName($name){
    if (!preg_match('/^[a-zA-Z]+$/', trim($name))) {
        return false;
    }
    return true;
}
function studentExist($db, $login) {
    $exist = false;
    $param_login = trim($login);
    $sql = "SELECT id FROM student WHERE login = :login";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":login", $param_login, PDO::PARAM_STR);

    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $exist = true;
    }
    unset($stmt);
    return $exist;
}
function teacherExists($db, $login) {
    $exist = false;
    $param_login = trim($login);
    $sql = "SELECT id FROM teacher WHERE login = :login";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":login", $param_login, PDO::PARAM_STR);

    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $exist = true;
    }
    unset($stmt);
    return $exist;
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
    <h1><?php echo $language['registration'];?></h1>

    <form method="post">
        <label for="login">
            <?php echo $language['login_name'];?>:
            <input type="text" name="login" class="form-control" value="" id="login" placeholder="napr. xpavlisn" required">
        </label>
        <br>
        <label for="password">
            <?php echo $language['password'];?>:
            <input type="password" name="password" class="form-control" value="" id="password" required>
        </label>
        <br>
        <label for="name">
            <?php echo $language['name'];?>:
            <input type="name" name="name" class="form-control" value="" id="name" required>
        </label>
        <br>
        <label for="surname">
            <?php echo $language['surname'];?>:
            <input type="surname" name="surname" class="form-control" value="" id="surname" required>
        </label>
        <br>
        <fieldset>
            <legend><?php echo $language['role_select'];?></legend>
            <label>
                <input type="radio" name="user_role" value="student" required>
                <?php echo $language['student'];?>
            </label>
            <label>
                <input type="radio" name="user_role" value="teacher" required>
                <?php echo $language['teacher'];?>
            </label>
        </fieldset>
        <br>
        <button type="submit" name="register" class="btn btn-primary">Create account</button>
        <br>
        <?php
        if (!empty($errmsg)) {
            echo $errmsg;
        }
        if(isset($msg)) {
            if ($msg != "") {
                echo '<p>' . $msg . '</p>';
            }
        }
        ?>
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/4.9.3/js/tabulator.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>
</body>
</html>
