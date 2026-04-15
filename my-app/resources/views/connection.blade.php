<?php
$servername = "localhost";
$username = "root";
$password = "nanaputo22503"; // Remember to empty this slot (pass slot)

/*
    Establish connection with local MySQL db
*/
try {
    $conn = new PDO("mysql:host=$servername;", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Could not connect. " . $e->getMessage());
}

/*
    Create freedom_board database
*/
try {
    $create_db = "CREATE DATABASE IF NOT EXISTS freedom_board;";
    $conn->exec($create_db);
} catch(PDOException $e) {
    // Handle errors during db creation
}

/*
    Use existing freedom_board database
*/
$conn->exec("USE freedom_board");

/*
    Create users and posts Tables
*/
try {
    $conn->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT,
        username VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    )");

    $conn->exec("CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT,
        user_id INT,
        content VARCHAR(255),
        parent_id INT DEFAULT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (parent_id) REFERENCES posts(id) ON DELETE CASCADE
    )");
} catch(PDOException $e) {
    die("Error creating tables: " . $e->getMessage());
}

/*
    Add parent_id column if table already exists without it
*/
try {
    $conn->exec("ALTER TABLE posts ADD COLUMN parent_id INT DEFAULT NULL");
    $conn->exec("ALTER TABLE posts ADD FOREIGN KEY (parent_id) REFERENCES posts(id) ON DELETE CASCADE");
} catch(PDOException $e) {
    // Column already exists, ignore
}

/*
    Add deleted column if not exists
*/
try {
    $conn->exec("ALTER TABLE posts ADD COLUMN deleted TINYINT(1) DEFAULT 0");
} catch(PDOException $e) {
    // Column already exists
}
?>