<?php
$conn = new mysqli("localhost", "owner01", "123456", "project");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// 假設 photos 表已有資料，獲取所有照片的 ID
$result = $conn->query("SELECT id FROM photos");
$photo_ids = [];
while ($row = $result->fetch_assoc()) {
    $photo_ids[] = $row['id'];
}

if (empty($photo_ids)) {
    die("請先上傳一些照片到 photos 表！");
}

// 模擬 IP 地址池（隨機生成）
$ip_pool = [];
for ($i = 1; $i <= 50; $i++) {
    $ip_pool[] = "192.168.1." . $i; // 簡單模擬，可替換為更真實的 IP
}

// 生成隨機投票數據
$vote_count = 100; // 生成 100 筆投票記錄
for ($i = 0; $i < $vote_count; $i++) {
    $photo_id = $photo_ids[array_rand($photo_ids)]; // 隨機選一張照片
    $ip_address = $ip_pool[array_rand($ip_pool)]; // 隨機選一個 IP

    // 檢查是否已存在該 IP 對該照片的投票
    $stmt = $conn->prepare("SELECT COUNT(*) FROM votes WHERE photo_id = ? AND ip_address = ?");
    $stmt->bind_param("is", $photo_id, $ip_address);
    $stmt->execute();
    $stmt->bind_result($exists);
    $stmt->fetch();
    $stmt->close();

    if ($exists == 0) {
        // 插入投票記錄
        $stmt = $conn->prepare("INSERT INTO votes (photo_id, ip_address) VALUES (?, ?)");
        $stmt->bind_param("is", $photo_id, $ip_address);
        $stmt->execute();
        $stmt->close();

        // 更新 photos 表的 votes 欄位
        $stmt = $conn->prepare("UPDATE photos SET votes = votes + 1 WHERE id = ?");
        $stmt->bind_param("i", $photo_id);
        $stmt->execute();
        $stmt->close();
    }
}

// 統計並顯示結果
$result = $conn->query("SELECT id, file_name, votes FROM photos ORDER BY votes DESC");
echo "<h2>投票數據生成結果</h2>";
echo "<table border='1'><tr><th>照片 ID</th><th>檔案名稱</th><th>票數</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['id']}</td><td>{$row['file_name']}</td><td>{$row['votes']}</td></tr>";
}
echo "</table>";

$conn->close();
echo "<p>已生成 $vote_count 筆投票記錄（重複投票已自動過濾）。</p>";
?>