<?php
include 'dbConnect.php';
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: signIn.php?redirect=InstructorDashboard.php");
    exit();
}

$quiz_id = $_SESSION['quizId'];

$sql = "SELECT Q.question_id, Q.question, Q.type FROM lms.question Q 
        JOIN lms.quiz K ON Q.question_id = K.question_id 
        WHERE K.quiz_id = :quizid";
$stmt = $conn->prepare($sql);
try {
    $stmt->bindParam(':quizid', $quiz_id);
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage();
}


if($_SERVER['REQUEST_METHOD']=='POST'  && isset($_POST['finish'])){
    header("Loaction: InstructorDashboard.php");
    exit();  
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Questions</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .quiz-header {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        h1 {
            color: #2c3e50;
            font-size: 2.2em;
            margin-bottom: 20px;
            text-align: center;
        }

        .question-container {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
        }

        .question-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .question-text {
            font-size: 1.1em;
            color: #34495e;
            margin-bottom: 20px;
            font-weight: 600;
            padding-left: 15px;
            border-left: 4px solid #3498db;
        }

        .answer-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .answer-input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .mcq-options, .tf-options {
            list-style: none;
        }

        .mcq-options li, .tf-options li {
            margin: 10px 0;
        }

        label {
            display: block;
            padding: 12px 15px;
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        label:hover {
            background: #e8f4fd;
            border-color: #3498db;
        }

        input[type="radio"] {
            margin-right: 10px;
            transform: scale(1.2);
        }

        button[type="submit"] {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 30px auto;
            padding: 15px 30px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(52, 152, 219, 0.2);
        }

        button[type="submit"]:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        @media (max-width: 600px) {
            body {
                padding: 20px 10px;
            }

            .quiz-header {
                padding: 20px;
            }

            h1 {
                font-size: 1.8em;
            }

            .question-container {
                padding: 20px;
            }

            .question-text {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="quiz-header">
            <h1>Quiz Questions</h1>
        </div>
        
        <form action="InstructorDashboard.php" method="post">
            <?php foreach ($questions as $question): ?>
                <div class="question-container">
                <p class="question-text"><?php echo htmlspecialchars($question['question']); ?></p>

                    <?php if ($question['type'] === 'Short Answer'): ?>
                        <textarea 
                            name="answer_<?php echo $question['question_id']; ?>" 
                            class="answer-input" 
                            placeholder="Type your answer here" 
                            
                            rows="4" 
                            cols="50"></textarea>
                    
                    
                    <?php elseif ($question['type'] === 'T/F'): ?>
                        <ul class="tf-options">
                            <li>
                                <label>
                                    <input type="radio" 
                                           name="answer_<?php echo $question['question_id']; ?>" 
                                           value="True" 
                                            >
                                    True
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" 
                                           name="answer_<?php echo $question['question_id']; ?>" 
                                           value="False">
                                    False
                                </label>
                            </li>
                        </ul>
                    
                    <?php elseif ($question['type'] === 'MCQ'): ?>
                        

                        <ul class="mcq-options">
                            <li>
                                <label>
                                    <input type="radio" 
                                           name="answer_<?php echo $question['question_id']; ?>" 
                                           value="A" 
                                            >
                                    A
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" 
                                           name="answer_<?php echo $question['question_id']; ?>" 
                                           value="B">
                                    B
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" 
                                           name="answer_<?php echo $question['question_id']; ?>" 
                                           value="C">
                                    C
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" 
                                           name="answer_<?php echo $question['question_id']; ?>" 
                                           value="D">
                                    D
                                </label>
                            </li>
                        </ul>






                        
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
            <button type="submit" name="finish">Finish</button>
        </form>
    </div>
</body>
</html>