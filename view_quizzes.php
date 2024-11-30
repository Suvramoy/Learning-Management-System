<?php
include 'dbConnect.php';
session_start();

$student_id = $_POST['student_id'] ?? '';
$course_id = $_POST['course_id'] ?? '';

$quizzes = [];
if ($course_id) {
    $sql_quizzes = "SELECT quiz_id, quiz_name FROM lms.quiz WHERE course_id = :courseId group by(quiz_id)";
    try {
        $stmt_quizzes = $conn->prepare($sql_quizzes);
        $stmt_quizzes->bindParam(':courseId', $course_id);
        $stmt_quizzes->execute();
        $quizzes = $stmt_quizzes->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching quizzes: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Quiz</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .quiz-card {
            transition: all 0.3s ease;
        }
        .quiz-card:hover {
            transform: translateY(-2px);
        }
        .gradient-bg {
            background: linear-gradient(120deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-10">
            <div class="gradient-bg inline-block p-2 rounded-lg mb-4">
                <i class="fas fa-clipboard-list text-4xl text-white"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Select Quiz to View Responses</h1>
            <p class="text-gray-600">Choose a quiz to view student's answers</p>
        </div>

        <?php if (!empty($quizzes)): ?>
            <div class="max-w-4xl mx-auto grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($quizzes as $quiz): ?>
                    <div class="quiz-card bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="p-6">
                            <div class="h-12 flex items-center justify-center mb-4">
                                <i class="fas fa-file-alt text-2xl text-indigo-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 text-center mb-4">
                                <?php echo htmlspecialchars($quiz['quiz_name']); ?>
                            </h3>
                            <form method="POST" action="view_responses.php">
                                <input type="hidden" name="quiz_id" value="<?php echo $quiz['quiz_id']; ?>">
                                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                                <button type="submit" 
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg transition duration-150 ease-in-out flex items-center justify-center">
                                    <i class="fas fa-eye mr-2"></i>
                                    View Responses
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Quizzes Found</h3>
                        <p class="text-gray-600">There are currently no quizzes available for this course.</p>
                        

                        <button onclick="window.location.href='Instructor_s_enrolled_students.php'" 
                                class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Go Back
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>