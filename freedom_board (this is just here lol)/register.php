<?php
// Start the session
session_start();
require_once 'connection.php'; // Needs to use $conn from connection.php

// Check if the user is already logged in, redirect to index.php
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Basic validation
    if (empty($username) || empty($password)) {
        $error = "All fields are required.";
    } else {
        try {
            // Check if username already exists
            $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $check->execute([$username]); // Stores in check object res. of execute

            if ($check->fetch()) { // Fetch that row
                $error = "Username already taken.";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
            }
        } catch (PDOException $e) {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>


<!-- To do: Implement register.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/log-reg.css">
</head>
<body>
    <div id="main-container">
        <p id="card-eyebrow">AENS Freedom Board</p>
        <p id="card-title">Create Account</p>
        <p id="card-sub">Join the board and start expressing yourself.</p>

        <?php 
            if (isset($error)) { echo "<p class='error'>$error</p>"; } 
        ?>
        <form id="field" method="post" action="">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button id="btn-submit" type="submit">Register</button>
        </form>

    <p id="card-footer">Already have an account? <a href="login.php">Log in here</a></p>
    <a id="home-link" href="index.php">Home</a>
</body>
</html>