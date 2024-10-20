<?php
session_start();
$conn = new mysqli("localhost", "root", "root", "hoorforum");

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
    <title>HoorForum Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <h1>Welcome, <?php echo $username; ?>!</h1>
    <p>Share your experience about evil spirits or rate others' experiences!</p>
</header>

<!-- Share Experience Form -->
<form action="upload_experience.php" method="POST" enctype="multipart/form-data">
    <h2>Share Your Experience</h2>
    <textarea name="experience" rows="4" cols="50" placeholder="Describe your experience..." required></textarea><br>
    <input type="file" name="document" accept=".pdf, .jpg, .jpeg, .png, .doc">
    <button type="submit">Share</button>
</form>

<hr>

<h2>Other User Experiences</h2>

<?php
// SQL query to fetch all experiences from all users, with their usernames
$experiences_sql = "SELECT experiences.*, users.username 
                    FROM experiences 
                    INNER JOIN users 
                    ON experiences.user_id = users.id 
                    ORDER BY timestamp DESC";

// Debug: Echo the SQL query to check if it's correct (optional)
//echo "<p>SQL Query: " . $experiences_sql . "</p>";   You can comment this out later

// Execute the query and check for errors
$experiences_result = $conn->query($experiences_sql);

if (!$experiences_result) {
    die("Query failed: " . $conn->error);
}

// Check if there are experiences to display
if ($experiences_result->num_rows > 0) {
    // Loop through all experiences and display them
    while($row = $experiences_result->fetch_assoc()) {
        echo "<div class='post'>";
        echo "<h3>" . $row['username'] . "</h3>";
        echo "<p>" . $row['experience'] . "</p>";

        // If the user uploaded a document, show a link to view it
        if ($row['document']) {
            echo "<a href='uploads/" . $row['document'] . "'>View Document</a>";
        }

        echo "<p>Posted on: " . $row['timestamp'] . "</p>";

        // Like button
        echo "<form action='like.php' method='POST'>
                <input type='hidden' name='experience_id' value='" . $row['id'] . "'>
                <button type='submit'>Like</button>
              </form>";

        // Comment form
        echo "<form action='comment.php' method='POST'>
                <textarea name='comment' rows='2' cols='50' placeholder='Write a comment...'></textarea><br>
                <input type='hidden' name='experience_id' value='" . $row['id'] . "'>
                <button type='submit'>Comment</button>
              </form>";

        // Fetch and display comments for this experience
        $experience_id = $row['id'];
        $comments_sql = "SELECT comments.*, users.username 
                         FROM comments 
                         INNER JOIN users 
                         ON comments.user_id = users.id 
                         WHERE experience_id = $experience_id";

        $comments_result = $conn->query($comments_sql);
        if ($comments_result->num_rows > 0) {
            while($comment = $comments_result->fetch_assoc()) {
                echo "<div class='comment'>";
                echo "<p><b>" . $comment['username'] . ":</b> " . $comment['comment'] . "</p>";
                echo "</div>";
            }
        }

        echo "</div><hr>";
    }
} else {
    echo "No experiences shared yet.";
}
?>

</body>
</html>
