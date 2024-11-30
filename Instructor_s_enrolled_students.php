<?php
include 'dbConnect.php';
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: signIn.php?redirect=Instructor_s_enrolled_students.php");
    exit();
}

// Check if 'instructor_id' exists in session
if (!isset($_SESSION['instructor_id'])) {
    echo "Instructor ID not found in session.";
    exit();
}

$instructor_id = $_SESSION['instructor_id'];
$selected_course_id = $_POST['course_id'] ?? '';
$students = [];
$courses = [];

// Fetch all courses for the instructor
$sql_courses = "SELECT course_id, course_name FROM lms.courses WHERE instructor_id = :instructorId";
try {
    $stmt_courses = $conn->prepare($sql_courses);
    $stmt_courses->bindParam(':instructorId', $instructor_id);
    $stmt_courses->execute();
    $courses = $stmt_courses->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching courses: " . $e->getMessage();
}

// Fetch students based on selected course ID
if ($selected_course_id) {
    // var_dump($selected_course_id);
    $sql_students = "
        SELECT E.studentId, S.Name 
        FROM lms.enrollment E 
        JOIN lms.student S ON E.studentId = S.rollNumber 
        WHERE E.courseId = :courseId;
    ";
    try {
        $stmt_students = $conn->prepare($sql_students);
        $stmt_students->bindParam(':courseId', $selected_course_id);
        $stmt_students->execute();
        $students = $stmt_students->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching students: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolled Students</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .select-wrapper {
            position: relative;
        }
        .select-wrapper::after {
            content: '▼';
            font-size: 0.8rem;
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
        .student-card {
            transition: all 0.3s ease;
        }
        .student-card:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                <i class="fas fa-users mr-3 text-indigo-600"></i>
                Enrolled Students
            </h1>
            <p class="text-gray-600">Select a course to view enrolled students</p>
        </div>

        <div class="max-w-xl mx-auto mb-12">
            <form method="POST" action="" class="w-full">
                <div class="select-wrapper">
                    <select name="course_id" 
                            onchange="this.form.submit()" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-700">
                        <option value="">-- Select a Course --</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo htmlspecialchars($course['course_id']); ?>" 
                                    <?php if ($selected_course_id === $course['course_id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($course['course_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>

        <?php if ($selected_course_id && !empty($students)): ?>
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-4xl mx-auto">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 pb-4 border-b border-gray-200">
                    <i class="fas fa-user-graduate mr-2 text-indigo-600"></i>
                    Enrolled Students
                </h2>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <?php foreach ($students as $student): ?>
                        <div class="student-card bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-indigo-300">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-indigo-600"></i>
                                    </div>
                                    <span class="ml-3 font-medium text-gray-800">
                                        <?php echo htmlspecialchars($student['Name']); ?>
                                    </span>
                                </div>
                            </div>
                            <form method="POST" action="view_quizzes.php">
                                <input type="hidden" name="student_id" value="<?php echo $student['studentId']; ?>">
                                <input type="hidden" name="course_id" value="<?php echo $selected_course_id; ?>">
                                <button type="submit" 
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out flex items-center justify-center">
                                    <i class="fas fa-clipboard-list mr-2"></i>
                                    View Answers
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php elseif ($selected_course_id): ?>
    <div class="max-w-4xl mx-auto">
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        No students are currently enrolled in this course.
                    </p>
                </div>
            </div>

            <!-- Center the Go Back Button -->
            <div class="text-center mt-6">
                <button onclick="window.location.href='InstructorDashboard.php'" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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