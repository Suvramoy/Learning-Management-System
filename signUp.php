<?php
include "dbConnect.php";
function validatePassword($password) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{7,}$/', $password);
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password=$_POST['password'];

    if(validatePassword($password)){
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    }
    else{
       echo "<script>alert('Weak Password'); window.location.href = 'signUp.php';</script>";
        exit();
    }


    $userType = $_POST['userType'];


    if($userType==="student"){

    $sql = "INSERT INTO lms.student(student_mail_id,Name,password_) VALUES(:student_mail_id, :Name, :password_)";
    $stmt = $conn->prepare($sql);
    try {
        $stmt->execute(array(
            ':student_mail_id' => $email,
            ':Name' => $name,
            ':password_' => $password,
        ));
        header("Location: signIn.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


else{
    $sql = "INSERT INTO lms.instructor(instructor_mail,Name,password_) VALUES(:student_mail_id, :Name, :password_)";
    $stmt = $conn->prepare($sql);
    try {
        $stmt->execute(array(
            ':student_mail_id' => $email,
            ':Name' => $name,
            ':password_' => $password,
        ));
        header("Location: signIn.php");
        exit();
    } catch (PDOException $e) {
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
    <title>Sign Up</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        :root {
            --primary: #7C3AED;
            --primary-dark: #6D28D9;
            --secondary: #EC4899;
            --background: #F9FAFB;
            --white: #FFFFFF;
            --text: #1F2937;
            --text-light: #6B7280;
            --border: #E5E7EB;
        }

        body {
            background: var(--background);
            min-height: 100vh;
            display: flex;
            margin: 0;
        }

        .split-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        .image-panel {
            flex: 1;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: var(--white);
            position: relative;
            overflow: hidden;
        }

        .image-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.2;
        }

        .image-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 400px;
        }

        .image-content h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .image-content p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .form-panel {
            flex: 1;
            background: var(--white);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-width: 600px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            color: var(--primary);
            font-weight: bold;
            margin-bottom: 2rem;
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-header h2 {
            font-size: 1.8rem;
            color: var(--text);
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: var(--text-light);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text);
            font-weight: 500;
        }

        input, select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.3s;
            background-color: var(--white);
        }

        input:focus, select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .password-input {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            cursor: pointer;
            background: none;
            border: none;
            padding: 0.25rem;
        }

        .btn-submit {
            width: 100%;
            padding: 0.75rem;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            background: var(--primary-dark);
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-light);
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 1024px) {
            .image-panel {
                display: none;
            }
            
            .form-panel {
                max-width: none;
            }
        }

        @media (max-width: 640px) {
            .form-panel {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- <h2>Sign Up</h2>
    <form method="POST" action="">
        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>
        
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        
        <label>User Type:</label><br>
        <select name="userType" required>
            <option value="instructor">Instructor</option>
            <option value="student">Student</option>
        </select><br><br>
        
        <button type="submit">Sign Up</button>
    </form> -->







    <div class="split-container">
        <div class="image-panel">
            <div class="image-content">
                <h1>Welcome to LMS</h1>
                <p>Join our community of learners and educators. Access world-class courses, connect with experts, and achieve your learning goals.</p>
            </div>
        </div>
        
        <div class="form-panel">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                Learning Management System
            </div>
            
            <div class="form-header">
                <h2>Create your account</h2>
                <p>Start your learning journey today</p>
            </div>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required placeholder="Chayan Halder">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="chayan@gmai;.com">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-input">
                        <input type="password" id="password" name="password" required 
                               placeholder="Min. 7 characters with uppercase, lowercase, number & special character">
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="userType">Join as</label>
                    <select id="userType" name="userType" required>
                        <option value="student">Student</option>
                        <option value="instructor">Instructor</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Create Account</button>

                <div class="login-link">
                    Already have an account? <a href="login.php">Log in</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>