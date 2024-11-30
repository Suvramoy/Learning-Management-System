<?php
// Include the database connection file
include 'dbConnect.php';

$ins_id = null;

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: signIn.php?redirect=assignallocate.php");
    exit();
}else {
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




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $duedate = $_POST['duedate'];
    $course_id = $_POST['course_id'];

    // Check if a file was uploaded without errors
    if (isset($_FILES['assignment']) && $_FILES['assignment']['error'] == 0) {
        // Read the file contents
        $file_tmp_path = $_FILES['assignment']['tmp_name'];
        $file_data = file_get_contents($file_tmp_path); // Read binary data

        try {
            // Prepare the SQL insert statement with named placeholders
            $sql = "INSERT INTO assignallocate (duedate, assignment, course_id) VALUES (:assignmentId, :duedate, :assignment, :course_id)";
            $stmt = $conn->prepare($sql);

            // Bind the parameters using bindParam for secure insertion
            $stmt->bindParam(':duedate', $duedate, PDO::PARAM_STR);
            $stmt->bindParam(':assignment', $file_data, PDO::PARAM_LOB);
            $stmt->bindParam(':course_id', $course_id, PDO::PARAM_STR);

            // Execute the statement
            if ($stmt->execute()) {
                echo "<script>alert('Assignment added successfully! for $course_id'); window.location.href = 'instructorDashboard.php';</script>";

            } else {
                echo "Error: " . implode(", ", $stmt->errorInfo());
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error uploading file.";
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Assignment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            color: #2d3748;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
            position: relative;
            padding-bottom: 10px;
        }

        h2::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            height: 4px;
            width: 50px;
            background: #4f46e5;
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #4a5568;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        label i {
            margin-right: 8px;
            color: #4f46e5;
        }

        input[type="number"],
        input[type="date"],
        input[type="text"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: #f8fafc;
            color: #2d3748;
            appearance: none;
        }

        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%234f46e5' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 40px;
        }

        input[type="number"]:focus,
        input[type="date"]:focus,
        input[type="text"]:focus,
        select:focus {
            border-color: #4f46e5;
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .file-input-container {
            position: relative;
            margin-bottom: 25px;
            text-align: center;
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            padding: 20px;
            cursor: pointer;
            background-color: #f8fafc;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .file-input-container:hover {
            background-color: #eff6ff;
            border-color: #4f46e5;
        }

        .file-input-container input[type="file"] {
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            cursor: pointer;
        }

        .file-input-text {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 15px;
            font-weight: 500;
        }

        .file-input-text i {
            margin-right: 10px;
            font-size: 20px;
            color: #4f46e5;
        }

        input[type="submit"] {
            width: 100%;
            padding: 14px;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        input[type="submit"]:hover {
            background: #4338ca;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.3);
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 24px;
            }

            input[type="submit"] {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Assignment</h2>
        <form action="assignallocate.php" method="post" enctype="multipart/form-data">
                        
            <div class="form-group">
                <label for="duedate">Due Date</label>
                <input type="date" name="duedate" id="duedate" required>
            </div>
            
            <div class="form-group">
                <label for="course_id">
                    <i class="fas fa-book"></i>
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

            <div class="file-input-container">
                <input type="file" name="assignment" id="assignment" required>
                <div class="file-input-text">
                    <i class="fas fa-upload"></i> Choose a file or drag it here
                </div>
            </div>
            
            <input type="submit" value="Add Assignment">
        </form>
    </div>
</body>
</html>
