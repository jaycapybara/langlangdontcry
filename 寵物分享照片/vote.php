<?php
$conn = new mysqli("localhost", "owner01", "123456", "project");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["photo_id"])) {
    $photo_id = (int)$_POST["photo_id"];
    $ip_address = $_SERVER["REMOTE_ADDR"]; // 獲取用戶 IP

    // 檢查是否已投票
    $stmt = $conn->prepare("SELECT COUNT(*) FROM votes WHERE photo_id = ? AND ip_address = ?");
    $stmt->bind_param("is", $photo_id, $ip_address);
    $stmt->execute();
    $stmt->bind_result($vote_count);
    $stmt->fetch();
    $stmt->close();

    if ($vote_count == 0) {
        // 記錄投票
        $stmt = $conn->prepare("INSERT INTO votes (photo_id, ip_address) VALUES (?, ?)");
        $stmt->bind_param("is", $photo_id, $ip_address);
        $stmt->execute();
        $stmt->close();

        // 更新照片的投票數
        $stmt = $conn->prepare("UPDATE photos SET votes = votes + 1 WHERE id = ?");
        $stmt->bind_param("i", $photo_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: photo.php");
    exit();
}

$conn->close();
?>
