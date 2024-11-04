<?php
$conn = new mysqli("localhost", "root", "", "hoorforum");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful! <a href='index.html'>Login Now</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
