<?php
include 'dbConnect.php';
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: signIn.php?redirect=AssingmentSubmission.php");
    exit();
}




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments</title>
</head>
<body>
    See the assignments
</body>
</html>