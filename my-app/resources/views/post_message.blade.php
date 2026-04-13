<?php
session_start();
require_once 'connection.php';

if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $username = $_SESSION['username'];

        $stmt1 = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt1->execute([$username]);
        $user_id = $stmt1->fetch();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: index.php");
        exit(); 
    } 
    $message = $_POST['message'];
    
    $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;

    if (empty($message)) {
        header("Location: index.php");
        exit;
    } 

    try {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, content, parent_id) VALUES (?, ?, ?)");
        $stmt->execute([$user_id[0], $message, $parent_id]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
    } finally {
        header("Location: index.php");
        exit(); 
    }
} else {
   header("Location: index.php");
}
?>