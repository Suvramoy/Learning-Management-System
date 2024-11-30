<?php
include 'dbConnect.php';
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: signIn.php?redirect=StudentDashboard.php");
    exit();
}


try {
    $sql = "SELECT Name, rollNumber FROM lms.student WHERE student_mail_id = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $_SESSION['email']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $studentName = $user['Name'] ?? 'Instructor';
    $_SESSION['rollnum']=$user['rollNumber'];
} catch(PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $studentName = 'Instructor'; // Fallback value
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - LMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --background-color: #f0f2f5;
            --text-primary: #2b2d42;
            --text-secondary: #8d99ae;
            --success-color: #4CAF50;
            --warning-color: #ff9f1c;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: var(--background-color);
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            box-shadow: var(--card-shadow);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
        }

        .logo i {
            font-size: 24px;
            color: var(--primary-color);
        }

        .logo span {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .nav-links {
            list-style: none;
            margin-top: 20px;
        }

        .nav-links li {
            margin-bottom: 10px;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            text-decoration: none;
            color: var(--text-secondary);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            background: var(--primary-color);
            color: white;
        }

        .nav-links a i {
            margin-right: 10px;
            font-size: 18px;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info i {
            font-size: 20px;
            color: var(--primary-color);
            background: #e8f0fe;
            padding: 10px;
            border-radius: 50%;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 24px;
            padding: 15px;
            border-radius: 10px;
            color: white;
        }

        .stat-info h3 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .quick-actions {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
        }

        .quick-actions h2 {
            margin-bottom: 20px;
            color: var(--text-primary);
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .action-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-primary);
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }

        .action-card:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-3px);
        }

        .action-card i {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .action-card h3 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .action-card p {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .action-card:hover p {
            color: #fff;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                padding: 10px;
            }

            .sidebar .logo span,
            .sidebar .nav-links span {
                display: none;
            }

            .main-content {
                margin-left: 70px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-graduation-cap"></i>
            <span>LMS Portal</span>
        </div>
        <ul class="nav-links">
            <li><a href="#"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="#"><i class="fas fa-book"></i><span>Courses</span></a></li>
            <li><a href="takeQuiz.php"><i class="fas fa-question-circle"></i><span>Quizzes</span></a></li>
            <li><a href="answerSurvey.php"><i class="fas fa-poll"></i><span>Surveys</span></a></li>
            <li><a href="SeeAssignments.php"><i class="fas fa-tasks"></i><span>Assignments</span></a></li>
            <li><a href="#"><i class="fas fa-chart-line"></i><span>Progress</span></a></li>
            <li><a href="#"><i class="fas fa-cog"></i><span>Settings</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <div class="user-info">
                <i class="fas fa-user"></i>
                <div>
                    <h2>Welcome, <?php echo htmlspecialchars($studentName); ?></h2>
                    <p>Student Dashboard</p>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: var(--primary-color);">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-info">
                    <h3>5</h3>
                    <p>Active Courses</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: var(--success-color);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>12</h3>
                    <p>Completed Tasks</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: var(--warning-color);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>3</h3>
                    <p>Pending Tasks</p>
                </div>
            </div>
        </div>

        <div class="quick-actions">
            <h2>Quick Actions</h2>
            <div class="actions-grid">
                <a href="takeQuiz.php" class="action-card">
                    <i class="fas fa-question-circle"></i>
                    <h3>Take Quiz</h3>
                    <p>Start a new quiz</p>
                </a>
                <a href="answerSurvey.php" class="action-card">
                    <i class="fas fa-poll"></i>
                    <h3>Answer Survey</h3>
                    <p>Provide your feedback</p>
                </a>
                <a href="AssignmentSubmission.php" class="action-card">
                    <i class="fas fa-tasks"></i>
                    <h3>Submit Assignment</h3>
                    <p>Upload your work</p>
                </a>
                <a href="#" class="action-card">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>Feedback</h3>
                    <p>How was the course?</p>
                </a>
            </div>
        </div>
    </div>
</body>
</html>