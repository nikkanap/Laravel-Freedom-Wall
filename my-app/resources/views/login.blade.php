<?php
// Start the session
session_start();
require_once 'connection.php'; // Needs to use $conn from connection.php

// Check if the user is already logged in, redirect to index.php
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Basic validationn
    if (empty($username) || empty($password)) {
        $error = "All fields are required.";
    } else {
        try {
            $check = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $check->execute([$username]); 
            $cred_row = $check->fetch();
            
            if (password_verify($password, $cred_row["password"])) { 
                $_SESSION['username'] = $cred_row['username']; 
                $_SESSION['user_id'] = $cred_row['id'];
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<!-- To do: add any modification to the login page
 Currently using default design from documentation: https://codeswithpankaj.com/php-session-tutorial-with-login-and-logout-example/ -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/log-reg.css">
</head>
<body>

    <div id="main-container">
        <p id="card-eyebrow">AENS Freedom Board</p>
        <p id="card-title">Welcome Back</p>
        <p id="card-sub">Log in to your account to continue.</p>

        <?php 
            if (isset($error)) { echo "<p class='error'>$error</p>"; } 
        ?>
        <form id="field" method="post" action="">
            <label for="username" autocomplete="off">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button id="btn-submit" type="submit">Login</button>
        </form>

        <p id="card-footer">Don't have an account? <a href="register.php">Register here</a></p>
        <a id="home-link" href="index.php">Home</a>
    </div>
</body>
</html>
