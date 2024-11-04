<?php
session_start();
$conn = new mysqli("localhost", "root", "", "hoorforum");

if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $experience = $_POST['experience'];
    $username = $_SESSION['username'];

    // Get user ID
    $user_sql = "SELECT id FROM users WHERE username = '$username'";
    $user_result = $conn->query($user_sql);
    $user_id = $user_result->fetch_assoc()['id'];

    // Handle document upload (if provided)
    $document = "";
    if (!empty($_FILES['document']['name'])) {
        $document = $_FILES['document']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($document);
        move_uploaded_file($_FILES["document"]["tmp_name"], $target_file);
    }

    // Insert experience into the database
    $sql = "INSERT INTO experiences (user_id, experience, document) VALUES ('$user_id', '$experience', '$document')";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
