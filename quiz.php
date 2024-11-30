<?php
include 'dbConnect.php';
session_start();
$ins_id = null;

if (!isset($_SESSION['email'])) {
    header("Location: signIn.php?redirect=quiz.php");
    exit();
} else {
    $id = "SELECT I.instructor_id FROM lms.instructor I WHERE instructor_mail = :mail";
    $stmt2 = $conn->prepare($id);
    try {
        $stmt2->bindParam(':mail', $_SESSION['email']);
        $stmt2->execute();
        $ins_data = $stmt2->fetch(PDO::FETCH_ASSOC);
        $ins_id = $ins_data['instructor_id'] ?? null; // Using null coalescing operator to avoid undefined index errors

        $_SESSION['instructor_id']=$ins_id;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if (!$ins_id) {
    echo "<script>alert('You are not an instructor'); window.location.href = 'quiz.php';</script>";
    exit();
}

try {
    $sql = "SELECT * FROM lms.questions";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $questions = [];
}

try {
    $sql1 = "SELECT C.course_id, C.course_name FROM lms.courses C 
             JOIN lms.instructor I ON C.instructor_id = I.instructor_id 
             WHERE I.instructor_id = :ins_id";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bindParam(':ins_id', $ins_id);
    $stmt1->execute();
    $Courses = $stmt1->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['newsub'])) {
    $type = $_POST['questionType'];
    $course_id = $_POST['course_id'];
    $question_text = $_POST['questionText'];
    
    // echo "Course ID from form: $course_id"; 

    // try {
    //     $sql6 = "SELECT C.course_id from lms.courses C where C.course_name=:courseName";
    //     $stmt6 = $conn->prepare($sql6);
    //     $stmt6->bindParam(':courseName', $course_id); 
    //     $stmt6->execute();
        
    //     $course = $stmt6->fetch(PDO::FETCH_ASSOC);
    //     if ($course) {
    //         $course_id = $course['course_id']; 
    //     } else {
    //         echo "Course not found!";
    //         exit();
    //     }

    // } catch(PDOException $e) {
    //     echo "Error: " . $e->getMessage();
    //     exit();
    // }

    // var_dump($course_id);
    
    try {
        $sql5 = "INSERT INTO lms.question (type, course_id, question) VALUES (:type, :course_id, :question)";
        $stmt5 = $conn->prepare($sql5);
        
        $stmt5->bindParam(':type', $type);
        $stmt5->bindParam(':course_id', $course_id);
        $stmt5->bindParam(':question', $question_text);

        $stmt5->execute();

        echo "<script>alert('New question added to $course_id'); window.location.href = 'quiz.php';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nextsub'])) {
    $quizName = $_POST['quizName'];
    $course_id = $_POST['course_id_quiz'];
    $quiz_id=$_POST['quizId'];

        $_SESSION['quizName'] = $quizName;
        $_SESSION['course_id_quiz'] = $course_id;
        $_SESSION['quizId']=$quiz_id;

        header("Location: quizquestions.php");
        exit();  
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #f9fafb;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
            min-height: 500px;
            position: relative;
        }
        .container h2 {
            margin-bottom: 30px;
            font-size: 1.8rem;
            color: #333;
            position: relative;
            padding-bottom: 10px;
        }
        
        .container h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: #4f46e5;
            border-radius: 2px;
        }
        
        .button-container {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 30px;
            transition: opacity 0.3s ease;
        }
        .action-button {
            background-color: #4f46e5;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
            flex: 1;
            max-width: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .action-button:hover {
            background-color: #3b34d1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }
        .initial-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: opacity 0.3s ease;
        }
        .form-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            padding: 40px;
            visibility: hidden;
            opacity: 0;
            transition: all 0.5s ease;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            border-radius: 12px;
        }
        .form-container.show {
            visibility: visible;
            opacity: 1;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
            height: calc(100% - 70px); /* Subtract header height */
            overflow-y: auto;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            color: #444;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .form-group:focus-within label {
            color: #4f46e5;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            font-size: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            transition: all 0.3s ease;
            background-color: #fff;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
            max-height: 200px;
            line-height: 1.5;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #4f46e5;
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #9ca3af;
        }
        
        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 20px;
            padding-right: 40px !important;
        }
        
        .submit-button-container {
            margin-top: auto;
            padding-top: 20px;
        }
        
        .submit-button-container button {
            background-color: #4f46e5;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .submit-button-container button:hover {
            background-color: #3b34d1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }
        
        @media (max-width: 640px) {
            .container {
                padding: 24px;
            }
            
            .form-group {
                margin-bottom: 16px;
            }
            
            .form-group label {
                font-size: 14px;
            }
            
            .form-group input,
            .form-group select,
            .form-group textarea {
                padding: 8px 10px;
                font-size: 14px;
            }
        }
    </style>
    <script>
        function showQuizForm() {
            document.querySelector('.initial-content').style.opacity = '0';
            setTimeout(() => {
                document.querySelector('.initial-content').style.display = 'none';
                document.getElementById('quizForm').classList.add('show');
            }, 300);
        }

        function showQuestionForm() {
            document.querySelector('.initial-content').style.opacity = '0';
            setTimeout(() => {
                document.querySelector('.initial-content').style.display = 'none';
                document.getElementById('questionForm').classList.add('show');
            }, 300);
        }
    </script>
</head>
<body>

<div class="container">
    <div class="initial-content">
        <h2>Create a New Quiz / Add Questions</h2>
        <div class="button-container">
            <button class="action-button" onclick="showQuizForm()">
                <i class="fas fa-plus-circle"></i>
                Create Quiz
            </button>
            <button class="action-button" onclick="showQuestionForm()">
                <i class="fas fa-question-circle"></i>
                Add Question
            </button>
        </div>
    </div>

    <div id="quizForm" class="form-container">
        <h2>Create Quiz</h2>
        <form action="quiz.php" method="POST">
            <div class="form-group">
                <label for="quizId">
                    <i class="fas fa-file-alt" style="color: #4f46e5; margin-right: 8px;"></i>
                    Quiz Id
                </label>
                <input type="number" name="quizId" id="quizId" required placeholder="Enter quiz id">
            </div>

            <div class="form-group">
                <label for="quizName">
                    <i class="fas fa-file-alt" style="color: #4f46e5; margin-right: 8px;"></i>
                    Quiz Name
                </label>
                <input type="text" name="quizName" id="quizName" required placeholder="Enter quiz name">
            </div>

            <div class="form-group">
                <label for="course_id_quiz">
                    <i class="fas fa-book" style="color: #4f46e5; margin-right: 8px;"></i>
                    Select Course
                </label>
                <select name="course_id_quiz" id="course_id_quiz" required>
                    <option value="">Select Course</option>
                    <?php foreach ($Courses as $course): ?>
                        <option value="<?php echo htmlspecialchars($course['course_id']); ?>">
                            <?php echo htmlspecialchars($course['course_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="submit-button-container">
                <button class="action-button" onclick="showNextForm()" type="submit" name="nextsub">
                    <i class="fas fa-arrow-right"></i>
                    Next
                </button>
            </div>
        </form>
    </div>


    <div id="quizcartForm" class="form-container">
            <h2>Select Questions to Add to Quiz</h2>
            <form action="quiz.php" method="POST">
                <?php foreach ($question_ids as $question): ?>
                    <div>
                        <input type="checkbox" name="selected_questions[]" value="<?php echo $question['question_id']; ?>">
                        <?php echo htmlspecialchars($question['question']); ?>
                    </div>
                <?php endforeach; ?>
                <input type="hidden" name="quizName" value="<?php echo htmlspecialchars($_SESSION['quizName']); ?>">
                <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($_SESSION['course_id']); ?>">
                <button type="submit" name="addToCart">Add to Quiz Cart</button>
            </form>
        </div>

    <div id="questionForm" class="form-container">
        <h2>Add New Question</h2>
        <form action="quiz.php" method="POST">
            <div class="form-group">
                <label for="questionType">
                    <i class="fas fa-list-ul" style="color: #4f46e5; margin-right: 8px;"></i>
                    Question Type
                </label>
                <select name="questionType" id="questionType" required>
                    <option value="">Select Question Type</option>
                    <option value="T/F">True/False</option>
                    <option value="MCQ">Multiple Choice</option>
                    <option value="Short Answer">Short Answer</option>
                </select>
            </div>        
            <div class="form-group">
                <label for="course_id">
                    <i class="fas fa-book" style="color: #4f46e5; margin-right: 8px;"></i>
                    Select Course
                </label>
                <select name="course_id" id="course_id" required>
                    <option value="">Select Course</option>
                    <?php foreach ($Courses as $course): ?>
                        <option value="<?php echo htmlspecialchars($course['course_id']); ?>">
                            <?php echo htmlspecialchars($course['course_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="questionText">
                    <i class="fas fa-question-circle" style="color: #4f46e5; margin-right: 4px;"></i>
                    Question
                </label>
                <textarea 
                    name="questionText" 
                    id="questionText" 
                    required 
                    placeholder="Enter your question here..."
                ></textarea>
            </div>
            <div class="submit-button-container">
                <button type="submit" name="newsub">
                    <i class="fas fa-paper-plane"></i>
                    Submit Question
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>