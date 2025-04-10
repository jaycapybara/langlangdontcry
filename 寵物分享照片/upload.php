

<?php
$conn = new mysqli("localhost", "owner01", "123456", "project");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["photo"])) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

    $file_name = basename($_FILES["photo"]["name"]);
    $unique_file_name = time() . "_" . $file_name; // 儲存唯一的檔案名稱
    $target_file = $target_dir . $unique_file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if ($check === false) die("檔案不是圖片");
    if ($_FILES["photo"]["size"] > 5000000) die("檔案過大");
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) die("僅支援 JPG, JPEG, PNG 和 GIF 格式");

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO photos (file_name) VALUES (?)");
        $stmt->bind_param("s", $unique_file_name); // 儲存唯一檔案名稱到資料庫
        $stmt->execute();
        $stmt->close();
        header("Location: photo.php");
    } else {
        echo "上傳失敗";
    }
}
$conn->close();
?>