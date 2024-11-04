<?php
// Insert new experience into the database
$conn = new mysqli('localhost', 'root', '', 'hoorforum');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $story = $_POST['story'];
    $rating = $_POST['rating'];

    // For now, we'll assume a default username; in a real system, you'd track logged-in users
    $username = "Anonymous";

    $sql = "INSERT INTO experiences (username, story, rating) VALUES ('$username', '$story', '$rating')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Experience submitted successfully!'); window.location.href='forum.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
