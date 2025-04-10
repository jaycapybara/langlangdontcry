<?php
$conn = new mysqli("localhost", "owner01", "123456", "project");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comment"])) {
    $comment = trim($_POST["comment"]);
    if (!empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO comments (comment_text) VALUES (?)");
        $stmt->bind_param("s", $comment);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: photo.php");
}
$conn->close();
?>