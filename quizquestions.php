<?php
session_start();
include 'dbConnect.php';

if (!isset($_SESSION['email'])) {
    header("Location: signIn.php?redirect=quizquestions.php");
    exit();
}

$sql="select instructor_id from lms.instructor where instructor_mail=:email";
$stmt = $conn->prepare($sql);
       
    try{
    $stmt->bindParam(':email', $_SESSION['email']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        echo "Error :" . $e->getMessage();
    }

$quiz_name = $_SESSION['quizName'];
$course_id = $_SESSION['course_id_quiz'];
$quiz_id = $_SESSION['quizId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'])) {
    $question_id = $_POST['question_id'];
    
    // Insert the selected question into the quiz table
    $sqlInsert = "INSERT INTO quiz (quiz_id, course_id, question_id, quiz_name,instructor_id) 
                  VALUES (:quiz_id, :course_id, :question_id, :quiz_name, :inst_id)
                  ON DUPLICATE KEY UPDATE quiz_id=quiz_id"; // Avoid duplicates

    try {
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bindParam(':quiz_id', $quiz_id);
        $stmtInsert->bindParam(':course_id', $course_id);
        $stmtInsert->bindParam(':question_id', $question_id);
        $stmtInsert->bindParam(':quiz_name', $quiz_name);
        $stmtInsert->bindParam(':inst_id', $user['instructor_id']);

        $stmtInsert->execute();
        echo "<script>alert('Questions add successfully to the quizId $quiz_id'); window.location.href = 'quizquestions.php';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch questions for the course
$question_ids = null;
$sql7 = "SELECT q.question_id, q.question FROM lms.question q WHERE q.course_id = :course__id";
try {
    $stmt7 = $conn->prepare($sql7);
    $stmt7->bindParam(':course__id', $course_id);
    $stmt7->execute();
    $question_ids = $stmt7->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['seepaper'])){
    
    $quiz_id=$_POST['quizId'];
    $_SESSION['quizId']=$quiz_id;

    header("Location: seePaper.php");
    exit();  
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Questions - <?php echo htmlspecialchars($quiz_name); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #f8fafc;
            --accent-color: #06b6d4;
            --text-color: #1e293b;
            --border-radius: 16px;
            --box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            min-height: 100vh;
            padding: 2.5rem 1rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .header {
            text-align: center;
            margin-bottom: 3.5rem;
            position: relative;
        }

        .header h1 {
            color: var(--primary-color);
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 800;
            letter-spacing: -1px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            background-clip: text; /* Standard property for compatibility */
            -webkit-background-clip: text; /* WebKit-prefixed for Safari, Chrome, etc. */
            -webkit-text-fill-color: transparent;
        }

        .header p {
            color: #64748b;
            font-size: 1.25rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .questions-list {
            list-style: none;
            margin-bottom: 2.5rem;
        }

        .question-item {
            background-color: var(--secondary-color);
            margin-bottom: 1.5rem;
            padding: 2rem;
            border-radius: var(--border-radius);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(148, 163, 184, 0.1);
        }

        .question-item:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
            background-color: white;
        }

        .question-text {
            flex: 1;
            padding-right: 2.5rem;
            font-size: 1.15rem;
            color: var(--text-color);
            line-height: 1.7;
        }

        .add-button {
            background: linear-gradient(135deg, var(--primary-color), #6366f1);
            color: white;
            border: none;
            padding: 1rem 2.5rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 1.1rem;
            white-space: nowrap;
        }

        .add-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(79, 70, 229, 0.3);
        }

        .add-button i {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .add-button:hover i {
            transform: rotate(90deg);
        }

        .action-container {
            margin-top: 3rem;
            text-align: center;
        }

        .see-quiz-link {
            display: inline-block;
            text-decoration: none;
            padding: 1.4rem 3rem;
            background: linear-gradient(135deg, var(--accent-color), #0891b2);
            color: white;
            border-radius: var(--border-radius);
            font-weight: 600;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2);
        }

        .see-quiz-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(6, 182, 212, 0.3);
        }

        .see-quiz-link i {
            margin-right: 0.8rem;
        }

        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            background: linear-gradient(135deg, var(--secondary-color), white);
            border-radius: var(--border-radius);
            margin-top: 2rem;
        }

        .empty-state i {
            font-size: 5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            background-clip: text; /* Standard property for compatibility */
            -webkit-background-clip: text; /* WebKit-prefixed for Safari, Chrome, etc. */
            -webkit-text-fill-color: transparent;
            margin-bottom: 2rem;
        }


        .empty-state p:first-of-type {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .empty-state p:last-of-type {
            font-size: 1.15rem;
            color: #64748b;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .container {
                padding: 2rem;
            }

            .header h1 {
                font-size: 2.3rem;
            }

            .header p {
                font-size: 1.1rem;
            }

            .question-item {
                flex-direction: column;
                gap: 1.5rem;
                padding: 1.5rem;
            }

            .question-text {
                padding-right: 0;
            }

            .add-button {
                width: 100%;
                justify-content: center;
                padding: 1.2rem;
            }

            .see-quiz-link {
                width: 100%;
                padding: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo htmlspecialchars($quiz_name); ?></h1>
            <p>Select questions to add to your quiz</p>
        </div>

        <?php if ($question_ids): ?>
            <ul class="questions-list">
                <?php foreach ($question_ids as $question): ?>
                    <li class="question-item">
                        <div class="question-text">
                            <?php echo htmlspecialchars($question['question']); ?>
                        </div>
                        <form action="quizquestions.php" method="post">
                            <input type="hidden" name="question_id" value="<?php echo $question['question_id']; ?>">
                            <button type="submit" class="add-button">
                                <i class="fas fa-plus"></i>
                                Add to Quiz
                            </button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="action-container">
                <form action="seePaper.php" method="post">
                    <button type="submit" class="see-quiz-link" name="seepaper">
                        <i class="fas fa-file-alt"></i> Preview the Paper
                    </button>
                </form>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-clipboard-question"></i>
                <p>No questions available for this course.</p>
                <p>Please add some questions to get started.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>