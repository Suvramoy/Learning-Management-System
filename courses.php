<?php
session_start();
include 'dbConnect.php';

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    header("Location: signin.php");
    exit();
}

// Fetch the student's name
$student_name = htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8');
$student_id = $_SESSION['user_id'];

// Fetch all courses the student is enrolled in using a prepared statement
$sql = "SELECT enrollment.enrollmentId, courses.course_name 
        FROM enrollment 
        JOIN courses ON enrollment.courseId = courses.course_id 
        WHERE enrollment.studentId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id); // Assuming student_id is an integer
$stmt->execute();
$enrolled_courses = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo $student_name; ?>!</h2>

    <h3>Your Enrolled Courses</h3>
    <table border="1">
        <tr>
            <th>Enrollment ID</th>
            <th>Course Name</th>
        </tr>

        <?php if ($enrolled_courses->num_rows > 0): ?>
            <?php while($course = $enrolled_courses->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($course['enrollmentId'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($course['course_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="2">You are not enrolled in any courses.</td>
            </tr>
        <?php endif; ?>
    </table>
    <br>

    <!-- Link to enroll in new courses -->
    <a href="enrollment.php">Enroll in a New Course</a>
    <br><br>

    <!-- Logout link -->
    <a href="logout.php">Logout</a>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
