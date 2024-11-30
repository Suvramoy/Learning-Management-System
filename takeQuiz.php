<?php
include 'dbConnect.php';
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: signIn.php?redirect=StudentDashboard.php");
    exit();
}

$student_id = $_SESSION['rollnum'];
$selected_course = isset($_POST['course_id']) ? $_POST['course_id'] : null;
$quizzes = [];

// Fetch courses the student is enrolled in
$sql1 = "SELECT C.course_id, C.course_name 
         FROM lms.courses C 
         JOIN lms.enrollment E ON C.course_id = E.courseId 
         WHERE E.studentId = :stuID";

try {
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bindParam(':stuID', $student_id);
    $stmt1->execute();
    $Courses = $stmt1->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Fetch quizzes for selected course
if ($selected_course) {
    $sql2 = "SELECT DISTINCT Q.quiz_id, Q.quiz_name 
             FROM lms.quiz Q 
             WHERE Q.course_id = :courseID";

    try {
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bindParam(':courseID', $selected_course);
        $stmt2->execute();
        $quizzes = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle quiz start request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['startquiz'])) {
    $_SESSION['quiz_id'] = $_POST['quiz_id'];

    // Get the max attempt number for the student and quiz
    $sql3 = "SELECT MAX(attempt_number) AS max_attempt 
             FROM lms.quiz_attempt_ 
             WHERE student_id = :studentId 
             AND quiz_id = :quizId";

    try {
        $stmt3 = $conn->prepare($sql3);
        $stmt3->bindParam(':studentId', $student_id);
        $stmt3->bindParam(':quizId', $_POST['quiz_id']);
        $stmt3->execute();
        $attemptnum = $stmt3->fetch(PDO::FETCH_ASSOC);

        // Determine the next attempt number
        $new_attempt_number = $attemptnum['max_attempt'] ? $attemptnum['max_attempt'] + 1 : 1;

        // Insert new attempt
        $sqlInsert = "INSERT INTO lms.quiz_attempt_ (student_id, quiz_id, attempt_number) 
                      VALUES (:student_id, :quiz_id, :attempt_number)";

        try {
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bindParam(':student_id', $student_id);
            $stmtInsert->bindParam(':quiz_id', $_POST['quiz_id']);
            $stmtInsert->bindParam(':attempt_number', $new_attempt_number);
            $stmtInsert->execute();

            // Retrieve the attempt ID of the new entry
            $sql4 = "SELECT attempt_id
                     FROM lms.quiz_attempt_ 
                     WHERE student_id = :studentId 
                     AND quiz_id = :quizId AND attempt_number = :attempt_number";

            $stmt4 = $conn->prepare($sql4);
            $stmt4->bindParam(':studentId', $student_id);
            $stmt4->bindParam(':quizId', $_POST['quiz_id']);
            $stmt4->bindParam(':attempt_number', $new_attempt_number);
            $stmt4->execute();

            $attemptid = $stmt4->fetch(PDO::FETCH_ASSOC);
            
            // Set attempt ID in session
            var_dump($attemptid['attempt_id']);
            $_SESSION['attempt_id'] = $attemptid['attempt_id'];

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Redirect to quiz exam page
    header('Location: StudentquizExamPage.php');
    exit();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Quiz</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6;
            min-height: 100vh;
        }
        .select-wrapper {
            position: relative;
        }
        .select-wrapper::after {
            content: '▼';
            font-size: 1rem;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            position: absolute;
            pointer-events: none;
            color: #6b7280;
        }
        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Take Quiz</h1>
                <p class="text-gray-600">Select your course and quiz to begin</p>
            </div>

            <form method="POST" action="" class="space-y-6">
                <div>
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">Select Course</label>
                    <div class="select-wrapper">
                        <select name="course_id" id="course_id" required onchange="this.form.submit()" 
                                class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                            <option value="">Select Course</option>
                            <?php foreach ($Courses as $course): ?>
                                <option value="<?php echo htmlspecialchars($course['course_id']); ?>" 
                                    <?php echo ($selected_course == $course['course_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($course['course_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>

            <?php if ($selected_course && !empty($quizzes)): ?>
                <form method="POST" action="takeQuiz.php" class="space-y-6">
                    <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($selected_course); ?>">
                    <div class="mt-6">
                        <label for="quiz_id" class="block text-sm font-medium text-gray-700 mb-2">Select Quiz</label>
                        <div class="select-wrapper">
                            <select name="quiz_id" id="quiz_id" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                <option value="">Select Quiz</option>
                                <?php foreach ($quizzes as $quiz): ?>
                                    <option value="<?php echo htmlspecialchars($quiz['quiz_id']); ?>">
                                        <?php echo htmlspecialchars($quiz['quiz_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="startquiz" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                        Start Quiz
                    </button>
                </form>
            <?php elseif ($selected_course): ?>
                <div class="mt-6 text-center py-4 px-6 bg-yellow-50 rounded-md">
                    <p class="text-yellow-800">No quizzes available for the selected course.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>