<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // soft delete instead of removing row
    $stmt = $conn->prepare("
        UPDATE posts 
        SET deleted = 1, content = '' 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$post_id, $_SESSION['user_id']]);
}

header("Location: index.php");
exit();
?>