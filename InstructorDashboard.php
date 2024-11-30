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

// Fetch instructor details
try {
    $sql = "SELECT Name, password_ FROM lms.instructor WHERE instructor_mail = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $_SESSION['email']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $instructorName = $user['Name'] ?? 'Instructor';
} catch(PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $instructorName = 'Instructor'; // Fallback value
}

// Fetch dashboard statistics
try {
    // Add queries for actual statistics
    $totalStudents = 1200; // Replace with actual query
    $totalCourses = 35;    // Replace with actual query
    $newEnrollments = 150; // Replace with actual query
    $pendingAssignments = 45; // Replace with actual query
} catch(PDOException $e) {
    error_log("Statistics Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard - LMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --primary-color: #4f46e5;
            --secondary-color: #818cf8;
            --background-color: #f3f4f6;
            --card-color: #ffffff;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
        }

        body {
            background-color: var(--background-color);
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 280px;
            background: var(--card-color);
            padding: 20px;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .sidebar h2 {
            color: var(--primary-color);
            font-size: 24px;
            padding: 20px;
            margin-bottom: 30px;
            border-bottom: 2px solid #e5e7eb;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li {
            padding: 15px 20px;
            margin: 8px 0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar a {
            text-decoration: none;
            color: inherit; /* Ensures the link inherits the color of the list item */
        }

        .sidebar a li:hover {
            color: white; /* Keeps the color change on hover consistent */
        }

        .sidebar li:hover {
            background: var(--primary-color);
            color: white;
        }

        .sidebar li i {
            font-size: 20px;
            width: 25px;
        }

        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
        }

        header {
            background: var(--card-color);
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-right i {
            font-size: 20px;
            color: var(--text-secondary);
            cursor: pointer;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .dashboard-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: var(--card-color);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .charts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart {
            background: var(--card-color);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .course-list {
            background: var(--card-color);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .course-list table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .course-list th, 
        .course-list td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .course-list th {
            color: var(--text-secondary);
            font-weight: 600;
        }

        .course-list button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .course-list button:hover {
            background: var(--secondary-color);
        }

        .menu-toggle {
            display: none;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
        }

        @media (max-width: 1024px) {
            .sidebar {
                width: 80px;
            }

            .sidebar h2, 
            .sidebar li span {
                display: none;
            }

            .main-content {
                margin-left: 80px;
            }
        }

        @media (max-width: 768px) {
            .charts {
                grid-template-columns: 1fr;
            }

            .dashboard-overview {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }

            header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .menu-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }

        /* Styles for the logout dropdown */
        #logout-menu {
            display: none;
            position: absolute;
            top: 60px; /* Position it below the user profile */
            right: 30px;
            background-color: var(--card-color);
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            z-index: 1001;
        }

        #logout-menu a {
            text-decoration: none;
            color: var(--text-primary);
            font-size: 16px;
            display: block;
        }

        #logout-menu a:hover {
            color: var(--primary-color);
        }

    </style>

    
</head>
<body>
    <div class="sidebar">
        <h2>LMS Dashboard</h2>
        <ul>
            <li><i class="fas fa-home"></i><span>Dashboard</span></li>
            <li><i class="fas fa-book"></i><span>Courses</span></li>
            <a href="Instructor_s_enrolled_students.php"><li><i class="fas fa-users"></i><span>Students</span></li></a>
            <li><i class="fas fa-chart-line"></i><span>Analytics</span></li>
            <a href="quiz.php">
            <li><i class="fas fa-question-circle"></i><span>Quiz</span></li></a> 
            <a href="assignallocate.php">
            <li><i class="fas fa-tasks"></i><span>Assignment Allocate</span></li></a>
            <a href="logout.php"><li><i class="fas fa-sign-out-alt"></i><span>Logout</span></li></a>
            
                   
    </ul>
    </div>

    <div class="main-content">
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($instructorName); ?></h1>
        <!-- <div class="header-right">
            <i class="fas fa-bell"></i>
           
        </div> -->
    </header>


    
    


        <section class="dashboard-overview">
            <div class="card">
                <h3>Total Students</h3>
                <p><?php echo $totalStudents; ?></p>
            </div>
            <div class="card">
                <h3>Total Courses</h3>
                <p><?php echo $totalCourses; ?></p>
            </div>
            <div class="card">
                <h3>New Enrollments</h3>
                <p><?php echo $newEnrollments; ?></p>
            </div>
            <div class="card">
                <h3>Pending Assignments</h3>
                <p><?php echo $pendingAssignments; ?></p>
            </div>
        </section>

        <section class="charts">
            <div class="chart">
                <h3>Student Progress</h3>
                <canvas id="progressChart"></canvas>
            </div>
            <div class="chart">
                <h3>Course Completion Rate</h3>
                <canvas id="completionChart"></canvas>
            </div>
        </section>

        <section class="course-list">
            <h3>Recent Courses</h3>
            <table>
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Enrolled</th>
                        <th>Progress</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>JavaScript Basics</td>
                        <td>200</td>
                        <td>80%</td>
                        <td><button>Manage</button></td>
                    </tr>
                    <tr>
                        <td>Advanced Python</td>
                        <td>150</td>
                        <td>60%</td>
                        <td><button>Manage</button></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>

    <script>
        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Progress Chart
            const progressCtx = document.getElementById('progressChart').getContext('2d');
            new Chart(progressCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Average Progress',
                        data: [65, 70, 75, 72, 80, 85],
                        borderColor: '#4f46e5',
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(79, 70, 229, 0.1)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Completion Rate Chart
            const completionCtx = document.getElementById('completionChart').getContext('2d');
            new Chart(completionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'In Progress', 'Not Started'],
                    datasets: [{
                        data: [65, 25, 10],
                        backgroundColor: ['#4f46e5', '#818cf8', '#e5e7eb']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Mobile menu toggle
            const menuToggle = document.createElement('button');
            menuToggle.classList.add('menu-toggle');
            menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
            document.body.appendChild(menuToggle);

            menuToggle.addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('active');
            });
        });
    </script>
</body>
</html>