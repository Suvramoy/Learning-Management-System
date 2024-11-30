<?php
include 'dbConnect.php'; // Ensure this file defines $conn as a PDO object

// Start session and handle authentication if required
// session_start();
// if (!isset($_SESSION['email'])) {
//     header("Location: signIn.php?redirect=Enrollment.php");
//     exit();
// }

// Handle Enrollment
if (isset($_POST['enroll'])) {
    $studentId = $_POST['student_id'];
    $courseId = $_POST['course_id'];

    // Insert new enrollment record
    $sql = "INSERT INTO lms.enrollment (studentId, courseId) VALUES (:studentId, :courseId)";
    $stmt = $conn->prepare($sql);
    try {
        $stmt->execute([
            ':studentId' => $studentId,
            ':courseId' => $courseId,
        ]);
        header("Location: Enrollment.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle Delete Enrollment
if (isset($_GET['delete'])) {
    $enrollmentId = $_GET['delete'];

    // Delete enrollment record
    $sql = "DELETE FROM lms.enrollment WHERE enrollmentId = :enrollmentId";
    $stmt = $conn->prepare($sql);
    try {
        $stmt->execute([':enrollmentId' => $enrollmentId]);
        header("Location: Enrollment.php"); // Refresh the page after deletion
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch all enrollments
$enrollments = $conn->query("SELECT enrollment.enrollmentId, student.Name AS student_name, courses.course_name 
                             FROM lms.enrollment 
                             JOIN lms.student ON enrollment.studentId = student.rollNumber 
                             JOIN lms.courses ON enrollment.courseId = courses.course_id");

// Fetch all students and courses for dropdowns
$students = $conn->query("SELECT rollNumber, Name FROM lms.student");
$courses = $conn->query("SELECT course_id, course_name FROM lms.courses");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Enrollment</title>
</head>
<body>
    <h2>Enroll in a Course</h2>
    <form method="POST" action="enrollment.php">
        <label for="student_id">Select Student:</label>
        <select name="student_id" required>
            <option value="">--Select Student--</option>
            <?php while ($student = $students->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?php echo htmlspecialchars($student['rollNumber']); ?>">
                    <?php echo htmlspecialchars($student['Name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label for="course_id">Select Course:</label>
        <select name="course_id" required>
            <option value="">--Select Course--</option>
            <?php while ($course = $courses->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?php echo htmlspecialchars($course['course_id']); ?>">
                    <?php echo htmlspecialchars($course['course_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <button type="submit" name="enroll">Enroll</button>
    </form>

    <h2>Current Enrollments</h2>
    <table border="1">
        <tr>
            <th>Enrollment ID</th>
            <th>Student Name</th>
            <th>Course Name</th>
            <th>Actions</th>
        </tr>
        <?php if ($enrollments->rowCount() > 0): ?>
            <?php while ($enrollment = $enrollments->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($enrollment['enrollmentId']); ?></td>
                    <td><?php echo htmlspecialchars($enrollment['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($enrollment['course_name']); ?></td>
                    <td>
                        <a href="enrollment.php?delete=<?php echo htmlspecialchars($enrollment['enrollmentId']); ?>" onclick="return confirm('Are you sure you want to delete this enrollment?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No enrollments available.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
