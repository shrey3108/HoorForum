<?php
session_start();
$conn = new mysqli("localhost", "root", "", "hoorforum");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Get the current logged-in user's username
$username = $_SESSION['username'];

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horror Experience Forum</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>

<header>
    <div class="header-logo">
        <a href="index.html">
            <img src="https://th.bing.com/th/id/OIP.i6B71TT1Ox9F8G09svG5wAHaHa?rs=1&pid=ImgDetMain" alt="Phantom Horror Forum Logo" class="logo">
        </a>
        <h1>Phantom</h1>
    </div>
</header>

<main>
    <!-- Share Experience Form -->
    <section class="share-experience">
        <h2>Share Your Experience</h2>
        <form action="upload_experience.php" method="POST" enctype="multipart/form-data">
            <textarea name="experience" rows="4" placeholder="Describe your experience..." required></textarea>
            <input type="file" name="document" accept=".pdf, .jpg, .jpeg, .png, .doc">
            <button type="submit">Share</button>
        </form>
    </section>

    <hr>

    <!-- Display User Experiences -->
    <section class="experience-feed">
        <h2 style="color: white;">Other User Experiences</h2>
        <?php
        // Fetch and display user experiences
        $experiences_sql = "SELECT experiences.*, users.username 
                            FROM experiences 
                            INNER JOIN users 
                            ON experiences.user_id = users.id 
                            ORDER BY timestamp DESC";
        $experiences_result = $conn->query($experiences_sql);

        if ($experiences_result && $experiences_result->num_rows > 0) {
            while ($row = $experiences_result->fetch_assoc()) {
                echo "<div class='post'>";
                echo "<h3>" . htmlspecialchars($row['username']) . "</h3>";
                echo "<p>" . htmlspecialchars($row['experience']) . "</p>";

                if ($row['document']) {
                    echo "<a href='uploads/" . htmlspecialchars($row['document']) . "' target='_blank'>View Document</a>";
                }

                echo "<p class='timestamp'>Posted on: " . htmlspecialchars($row['timestamp']) . "</p>";

                // Like button
                echo "<form action='like.php' method='POST'>
                        <input type='hidden' name='experience_id' value='" . htmlspecialchars($row['id']) . "'>
                        <button type='submit'>Like</button>
                      </form>";

                // Comment form
                echo "<form action='comment.php' method='POST'>
                        <textarea name='comment' rows='2' placeholder='Write a comment...'></textarea>
                        <input type='hidden' name='experience_id' value='" . htmlspecialchars($row['id']) . "'>
                        <button type='submit'>Comment</button>
                      </form>";

                // Display comments
                $experience_id = $row['id'];
                $comments_sql = "SELECT comments.*, users.username 
                                 FROM comments 
                                 INNER JOIN users 
                                 ON comments.user_id = users.id 
                                 WHERE experience_id = $experience_id";
                $comments_result = $conn->query($comments_sql);
                if ($comments_result && $comments_result->num_rows > 0) {
                    echo "<div class='comments-section'>";
                    while ($comment = $comments_result->fetch_assoc()) {
                        echo "<div class='comment'>";
                        echo "<p><strong>" . htmlspecialchars($comment['username']) . ":</strong> " . htmlspecialchars($comment['comment']) . "</p>";
                        echo "</div>";
                    }
                    echo "</div>";
                }

                echo "</div><hr>";
            }
        } else {
            echo "<p>No experiences shared yet. Be the first to share yours!</p>";
        }
        ?>
    </section>
</main>

</body>
</html>
