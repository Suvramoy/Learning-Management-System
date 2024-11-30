<?php
session_start();
include 'dbConnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userType = $_POST['userType'];

    if ($userType ==="instructor") {
        $sql = "SELECT password_ FROM lms.instructor WHERE instructor_mail=:email";
        $stmt = $conn->prepare($sql);
       
       try{
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // echo $user['Password_'];
        if($user){
        if (password_verify($password,$user['password_'])) {
            // Redirect based on user type
            $_SESSION['email'] = $email;
            header("Location: InstructorDashboard.php");
            exit();
        } else {
            echo "<script>alert('Wrong Password'); window.location.href = 'signIn.php';</script>";
            exit();
        }
       }else{
        echo "<script>alert('Email not found'); window.location.href = 'signIn.php';</script>";
        exit();
       }
    }catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
       }
    }
    
    
    else {
        $sql = "SELECT password_ FROM lms.student WHERE student_mail_id=:email";
        $stmt = $conn->prepare($sql);
       
       try{
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // echo $user['Password_'];
        if ($user) {
            if (password_verify($password, $user['password_'])) {
                $_SESSION['email'] = $email;
                header("Location: StudentDashboard.php");
                exit();
            } else {
                echo "<script>alert('Wrong Password'); window.location.href = 'signIn.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Email not found'); window.location.href = 'signIn.php';</script>";
            exit();
        }
       }catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
       }
    }

    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Learning Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        /* Body and Background */
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: linear-gradient(135deg, #6A0DAD, #4A63E7);
            color: #333;
            overflow: hidden;
            position: relative;
        }

        /* Background Circles for Decoration */
        .background-circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.2;
            background: #fff;
            filter: blur(80px);
        }
        .circle1 {
            width: 400px;
            height: 400px;
            top: 15%;
            left: 5%;
        }
        .circle2 {
            width: 300px;
            height: 300px;
            bottom: 20%;
            right: 10%;
        }

        /* Container Styling */
        .container {
            background-color: white;
            color: #333;
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
            z-index: 1;
            position: relative;
        }

        /* Title */
        .container h2 {
            font-size: 2rem;
            color: #4A63E7;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        /* Form Group Styling */
        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            font-weight: 500;
            color: #666;
            display: block;
            margin-bottom: 0.5rem;
        }

        /* Input Fields */
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            background-color: #f9f9f9;
            transition: border 0.3s;
        }
        .form-group input[type="email"]:focus,
        .form-group input[type="password"]:focus {
            outline: none;
            border-color: #4A63E7;
        }

        /* Radio Group Styling */
        .radio-group {
            display: flex;
            gap: 10px;
            margin-top: 0.5rem;
        }
        .radio-group label {
            font-size: 0.9rem;
            color: #333;
        }
        .radio-group input[type="radio"] {
            accent-color: #4A63E7;
            margin-right: 5px;
            transform: scale(1.2);
        }

        /* Sign In Button */
        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #6A0DAD, #4A63E7);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s ease, transform 0.2s;
            margin-top: 1rem;
        }
        button[type="submit"]:hover {
            background: linear-gradient(135deg, #4A63E7, #6A0DAD);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Background Circles for Aesthetic Effect -->
    <div class="background-circle circle1"></div>
    <div class="background-circle circle2"></div>

    <!-- Sign In Form Container -->
    <div class="container animate__animated animate__fadeIn">
        <h2>Sign In</h2>
        <form action="signin.php" method="POST" >
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label>User Type</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="userType" value="instructor" required>
                        Instructor
                    </label>
                    <label>
                        <input type="radio" name="userType" value="student">
                        Student
                    </label>
                </div>
            </div>
            
            <button type="submit">Sign In</button>
        </form>
        <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
            Don't have an account? 
            <a href="signUp.php" style="color: #4A63E7; text-decoration: none;">Sign Up</a>
        </p>
    </div>
</body>
</html>
