<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('config.php');

if(!isset($_SESSION['language'])){
    $_SESSION['language'] = "sk";
}
$selectedLanguage = $_SESSION['language'];
$languageFile = 'languages/' . $selectedLanguage . '.php';

require_once($languageFile);
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]){
    if(isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "student"){
        $login = $_SESSION["login"];
    } else {
        header('Location: index.php');
        exit();
    }
}


try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo $e->getMessage();
}

if(isset($_SESSION["user_id"])) {
    $studentId = $_SESSION["user_id"];
    $stmt = $pdo->prepare("SELECT * FROM student_exercise INNER JOIN exercise ON student_exercise.exercise_id = exercise.id WHERE student_exercise.student_id = :student_id AND (student_exercise.date_start <= NOW() AND student_exercise.date_end >= NOW() OR student_exercise.date_start IS NULL AND student_exercise.date_end IS NULL)");
    $stmt->execute(['student_id' => $studentId]);
    $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    if(isset($_POST['generate_task']) && isset($_POST['files'])) {
        $selectedFiles = $_POST['files'];
        $randomFile = $selectedFiles[array_rand($selectedFiles)];
    
        $selectedExercise = array_filter($exercises, function($exercise) use ($randomFile) {
            return $exercise['file_name'] == $randomFile;
        });
        $selectedExercise = reset($selectedExercise);
        $selectedExerciseId = $selectedExercise['exercise_id'];
    
        $fileName = "./uploads/" . $randomFile;
        $latexContent = file_get_contents($fileName);
        
      
        
        $tasks = parseLatexFile($latexContent);
        if ($tasks === false || empty($tasks)) {
            echo "Failed to parse the file or the file does not contain tasks";
        } else {
            
            $randomTask = $tasks[array_rand($tasks)];
        }
        

        $points = 0;
        $submitted = 0;

        $stmt = $pdo->prepare("INSERT INTO task (student_id, exercise_id, text, solution, points, submitted)
                        VALUES (:student_id, :exercise_id, :text, :solution, :points, :submitted)");
        $stmt->bindParam(':student_id', $studentId);
        $stmt->bindParam(':exercise_id', $selectedExerciseId);
        $stmt->bindParam(':text', $randomTask["task"]);
        $stmt->bindParam(':solution', $randomTask["solution"]);
        $stmt->bindParam(':points', $points);
        $stmt->bindParam(':submitted', $submitted);

        $stmt->execute();

        // Save generated task to a variable
        $generatedTask = $randomTask["task"];
    }

    if(isset($_POST['submit_solution']) && isset($_POST['task_id']) && isset($_POST['solution']) && isset($_SESSION["user_id"])){
        $taskId = $_POST['task_id'];
        $solution = $_POST['solution'];

        $stmt = $pdo->prepare("UPDATE task SET completed = TRUE, solution = :solution WHERE id = :task_id AND assigned_to = :user_id");
        $stmt->execute(['solution' => $solution, 'task_id' => $taskId, 'user_id' => $_SESSION["user_id"]]);
    }

}

function parseLatexFile($latexContent) {
    $tasks = array();
    preg_match_all('/\\\\begin{task}(.*?)\\\\end{task}.*?\\\\begin{solution}(.*?)\\\\end{solution}/s', $latexContent, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        $imagePath = "";
        if (preg_match('/\\\\includegraphics{(.*?)}/', $match[1], $imageMatch)) {
            $imagePath = $imageMatch[1];
        }
        $task = preg_replace('/\\\\includegraphics{(.*?)}/', '', trim($match[1]));
        $task = preg_replace('/\\\\dfrac{(.*?)}{(.*?)}/', '\\\\frac{$1}{$2}', $task);
        $solution = trim($match[2]);
        $solution = preg_replace('/\\\\begin{equation\*}(.*?)\\\\end{equation\*}/s', '$1', $solution); // удаление тегов equation
        $tasks[] = array(
            'task' => $task,
            'imagePath' => $imagePath,
            'solution' => $solution
        );
       
    }
    return $tasks;
}






?>
<!DOCTYPE html>
<html lang="<?php echo $selectedLanguage; ?>">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://unpkg.com/tabulator-tables@5.4.4/dist/css/tabulator.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script type="text/javascript" id="MathJax-script" async
  src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js">
</script>
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

<div class="container">
    <h2><?php echo $language['task_overview'];?></h2>
    <form action="#" method="post">
        <input type="hidden" name="generate_task" value="true">
        <button type="submit"><?php echo $language['generate_task'];?></button>
        <?php foreach($exercises as $exercise) {
        echo '<label><input type="checkbox" name="files[]" value="' . $exercise['file_name'] . '">' . $exercise['file_name'] . '</label><br>';
        }?>
    </form>



<script>
MathJax = {
  tex: {
    inlineMath: [['$', '$'], ['\\(', '\\)']]
  },
  svg: {
    fontCache: 'global'
  }
};

document.addEventListener('DOMContentLoaded', (event) => {
  document.querySelectorAll('p.mathjax-latex').forEach((node) => {
    MathJax.typesetPromise([node]).catch((err) => {
      console.log('Error typesetting math: ' + err.message);
    });
  });
});
</script>

<?php if (isset($randomTask)): ?>
    <h2><?php echo $language['generated_task'];?></h2>
    <?php if (!empty($randomTask['imagePath'])): ?>
        <div><img src="/uploads/<?php echo $randomTask['imagePath']; ?>.jpg" alt="Task Image"></div>
    <?php endif; ?>
    <p class="mathjax-latex"><?php echo $randomTask["task"]; ?></p>
    <form action="#" method="post">
        <input type="hidden" name="submit_solution" value="true">
        <input type="hidden" name="task_id" value="<?php echo $taskId; ?>">
        <textarea name="solution"></textarea>
        <button type="submit">Send</button>
    </form>
<?php endif; ?>



<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/4.9.3/js/tabulator.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.4/dist/js/tabulator.min.js"></script>
</body>
</html>