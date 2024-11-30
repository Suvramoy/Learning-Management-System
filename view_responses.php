<?php
include 'dbConnect.php';
session_start();

$quiz_id = $_POST['quiz_id'] ?? '';
$student_id = $_POST['student_id'] ?? '';

// Handle grade update submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_grade'])) {
    $new_grade = $_POST['new_grade'];
    $attempt_id = $_POST['attempt_id'];
    
    if (is_numeric($new_grade) && $new_grade >= 0 && $new_grade <= 100) {
        $update_sql = "UPDATE quiz_attempt_ SET grade = ? WHERE attempt_id = ?";
        try {
            $stmt_update = $conn->prepare($update_sql);
            $stmt_update->execute([$new_grade, $attempt_id]);
        } catch (PDOException $e) {
            echo "<p class='text-red-600'>Error updating grade: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        echo "<p class='text-red-600'>Invalid grade. Please enter a number between 0 and 100.</p>";
    }
}

// Fetch responses
$responses = [];
if ($quiz_id && $student_id) {
    $sql_responses = "
        SELECT 
            A.attempt_id, 
            A.attempt_number, 
            A.grade, 
            Q.question_id, 
            Q.question,
            Q.type, 
            R.question_solution 
        FROM 
            quiz_attempt_ A
        JOIN 
            quiz_response R ON A.attempt_id = R.attempt_id
        JOIN 
            question Q ON R.question_id = Q.question_id
        WHERE 
            A.student_id = :studentId 
            AND A.quiz_id = :quizId
        ORDER BY 
            A.attempt_number, Q.question_id;
    ";
    try {
        $stmt_responses = $conn->prepare($sql_responses);
        $stmt_responses->bindParam(':studentId', $student_id);
        $stmt_responses->bindParam(':quizId', $quiz_id);
        $stmt_responses->execute();
        $responses = $stmt_responses->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching responses: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Responses</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-4 text-center">Quiz Responses</h1>

        <?php if (!empty($responses)): ?>
            <?php 
            $current_attempt = null;
            foreach ($responses as $response): 
                if ($current_attempt !== $response['attempt_number']):
                    if ($current_attempt !== null) {
                        echo "</div>"; // Close previous attempt's container
                    }
                    $current_attempt = $response['attempt_number'];
            ?>
                    <div class="attempt-card max-w-4xl mx-auto mb-8 bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-indigo-600 px-6 py-4 text-white font-bold">
                            Attempt <?php echo htmlspecialchars($response['attempt_number']); ?>
                        </div>
                        <div class="p-6 space-y-6">
                            <form method="POST" class="mb-4">
                                <!-- Persist quiz_id and student_id -->
                                <input type="hidden" name="quiz_id" value="<?php echo htmlspecialchars($quiz_id); ?>">
                                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">
                                <input type="hidden" name="attempt_id" value="<?php echo htmlspecialchars($response['attempt_id']); ?>">
                                <label for="grade_<?php echo $response['attempt_id']; ?>" class="block text-gray-700 font-bold mb-2">
                                    Grade:
                                </label>
                                <div class="flex items-center space-x-2">
                                    <input 
                                        type="number" 
                                        id="grade_<?php echo $response['attempt_id']; ?>" 
                                        name="new_grade" 
                                        value="<?php echo htmlspecialchars($response['grade']); ?>" 
                                        class="w-16 px-2 py-1 border rounded-md" 
                                        min="0" 
                                        max="100" 
                                        required>
                                    <button type="submit" name="update_grade" 
                                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                        Save
                                    </button>
                                </div>
                            </form>
            <?php endif; ?>
                <div class="question-card bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h4 class="text-lg font-medium text-gray-900">
                        <?php echo htmlspecialchars($response['type']); ?> Question:
                    </h4>
                    <p class="text-gray-700">
                        <?php echo htmlspecialchars($response['question']); ?>
                    </p>
                    <hr class="my-2">
                    <h5 class="text-md font-semibold">Solution:</h5>
                    <p><?php echo htmlspecialchars($response['question_solution']); ?></p>
                </div>
            <?php endforeach; ?>
            <form method="POST" action="view_quizzes.php">
                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">
                <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($_POST['course_id'] ?? ''); ?>">
                <button type="submit" 
                        class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Go Back
                </button>
            </form>

            </div> <!-- Close the last attempt's container -->
        <?php else: ?>
            <p class="text-center text-gray-600">No responses found for this quiz.</p>
        <?php endif; ?>
    </div>
</body>
</html>
