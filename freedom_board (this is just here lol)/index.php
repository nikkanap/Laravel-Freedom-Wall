<?php
// Start the session
session_start();
require_once 'connection.php'; //note: added by Nikka

$postsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) $page = 1;
$offset = ($page - 1) * $postsPerPage; 

try {
    $countStmt = $conn->query('SELECT COUNT(*) FROM posts');
    $totalPosts = $countStmt -> fetchColumn();
    $totalPages = ceil($totalPosts / $postsPerPage);
} catch(PDOException $e){
    $totalPages = 1;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AENS Freedom Board</title>
    <link rel="stylesheet" href="css/index.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <?php
    function displayThread($post, $replies, $isLoggedIn, $sessionUserId = null) {
        echo "<div class='reply'>";
            echo "<div class='post-row'>";
                if ($post['deleted']) {
                    echo "<em>[deleted]</em>";
                } else {
                    echo "<strong>" . htmlspecialchars($post['username']) . "</strong>: " . htmlspecialchars($post['content']);

                    if ($isLoggedIn && !$post['deleted']) {
                        echo " <a href='#' class='reply-btn' onclick=\"toggleReply(" . $post['id'] . "); return false;\">Reply</a>";
                    }

                    // trashcan
                    if ($isLoggedIn && $sessionUserId !== null && $post['user_id'] == $sessionUserId) {
                        echo " <a href='delete_post.php?id=" . $post['id'] . "' class='delete-btn' title='Delete'><i class='fa fa-trash'></i></a>";
                    }
                }
            echo "</div>"; // close post-row

            // Hidden reply form
            if ($isLoggedIn && !$post['deleted']) {
                echo "<div id='reply-form-" . $post['id'] . "' style='display:none; margin-top:8px;'>";
                echo "<form action='post_message.php' method='POST' style='display:flex; gap:8px; align-items:center;'>";
                echo "<input type='hidden' name='parent_id' value='" . $post['id'] . "'>";
                echo "<input type='text' name='message' placeholder='Write a reply...' required style='width:80%; padding:5px;'>";
                echo " <button type='submit'>Reply</button>";
                echo "</form>";
                echo "</div>";
            }

            if (isset($replies[$post['id']])) {
                echo "<div class='thread'>";
                foreach ($replies[$post['id']] as $reply) {
                    displayThread($reply, $replies, $isLoggedIn, $sessionUserId);
                }
                echo "</div>";
            }

        echo "</div>";
    }
    ?>

    <?php
    if (!isset($_SESSION['username'])) {
        // If not set, display content for guests
    ?>
        <header>
            <h1>AENS Freedom Board</h1>
            <p>Freely express your mind.</p>
        </header>
        
        <section class="onboarding">
            <a class="btn-onboard" href="register.php">Register</a>
            <a class="btn-onboard" href="login.php">Login</a>
        </section>

        <h2>Recent Messages</h2>
        <?php
            // Guest view
            try {
                $stmt = $conn->prepare('SELECT p.id, p.user_id, u.username, p.content, p.parent_id, p.deleted FROM posts AS p JOIN users AS u ON p.user_id = u.id ORDER BY p.id DESC LIMIT :limit OFFSET :offset');
                $stmt->bindValue(':limit', $postsPerPage, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                $stmt->execute();
                $allPosts = $stmt->fetchAll();
            } catch(PDOException $e) {
                echo "<p>An error occurred fetching data.</p>";
            }

            if (empty($allPosts)) {
                echo "<p class='empty-state'>No messages yet. Be the first to post!</p>";
            }

            $topPosts = [];
            $replies = [];
            foreach ($allPosts as $row) {
                if ($row['parent_id'] === null) {
                    $topPosts[] = $row;
                } else {
                    $replies[$row['parent_id']][] = $row;
                }
            }

            $sessionUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
            foreach ($topPosts as $row) {
                displayThread($row, $replies, true, $sessionUserId);
            }
        ?>
    <?php
    } else {
        // If set, display content for logged-in users
    ?>  
        <header style="flex-direction: row">
            <h1 style="margin-bottom: 13px">AENS Freedom Board</h1>
            <div style="display:flex; align-items:center; gap:12px;">
                <span class="username">Logged in as <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                <a id="logout" href="logout.php">Logout</a>
            </div>
        </header>
        
        <form class="post-form" action="post_message.php" method="POST">
            <textarea placeholder="Write a message..." required name="message"></textarea>
            <div class="post-form-footer">
                <button type="submit" name="submit" value="submit">Post to Board</button>
            </div>
        </form>

        <h2>Recent Messages</h2>

        <?php
            //note: modifications made by Nikka
            try {
                $stmt = $conn->prepare('SELECT p.id, p.user_id, u.username, p.content, p.parent_id, p.deleted FROM posts AS p JOIN users AS u ON p.user_id = u.id ORDER BY p.id DESC LIMIT :limit OFFSET :offset');
                $stmt->bindValue(':limit', $postsPerPage, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                $stmt->execute();
                $allPosts = $stmt->fetchAll();
            } catch(PDOException $e) {
                echo "<p>An error occurred fetching data.</p>";
            }

            if (empty($allPosts)) {
                echo "<p class='empty-state'>No messages yet. Be the first to post!</p>";
            }

            $topPosts = [];
            $replies = [];
            foreach ($allPosts as $row) {
                if ($row['parent_id'] === null) {
                    $topPosts[] = $row;
                } else {
                    $replies[$row['parent_id']][] = $row;
                }
            }

            $sessionUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
            foreach ($topPosts as $row) {
                displayThread($row, $replies, true, $sessionUserId);
            }
            //end of modifications
        ?>
    <?php
    }

    // Only clear conn and statement (when not needed)
    $stmt = null;
    $conn = null;
    ?>

    <div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?>" class="button">Previous</a>
    <?php endif; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?page=<?php echo $page + 1; ?>" class="button">Next</a>
    <?php endif; ?>
    </div>


    <script>
    function toggleReply(postId) {
        var form = document.getElementById('reply-form-' + postId);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
    </script>

</body>
</html>