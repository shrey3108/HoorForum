<?php
session_start();
$conn = new mysqli("localhost", "root", "", "hoorforum");

if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comment = $_POST['comment'];
    $experience_id = $_POST['experience_id'];
    $username = $_SESSION['username'];

    // Get user ID
    $user_sql = "SELECT id FROM users WHERE username = '$username'";
    $user_result = $conn->query($user_sql);
    $user_id = $user_result->fetch_assoc()['id'];

    // Insert comment into the database
    $sql = "INSERT INTO comments (experience_id, user_id, comment) VALUES ('$experience_id', '$user_id', '$comment')";
    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
