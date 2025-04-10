<?php
$conn = new mysqli("localhost", "owner01", "123456", "project");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// 查詢照片按票數排序
$query = "SELECT id, file_name, votes FROM photos ORDER BY votes DESC";
$result = $conn->query($query);

$rankings = [];
while ($row = $result->fetch_assoc()) {
    $rankings[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投票排行榜</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        .ranking-list {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .ranking-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .ranking-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 20px;
        }
        .ranking-item span {
            font-size: 1.2em;
        }
        .ranking-item .votes {
            margin-left: auto;
            color: #e74c3c;
            font-weight: bold;
        }
        canvas {
            max-width: 100%;
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <h1>寵物照片投票排行榜</h1>

   
   <div>
    <a href="http://192.168.10.103/project/lottery.php"> <h3>抽獎區</h3></a>
   </div> 
   <div>
    <a href="langlangdontcry-index.html"> <h3>回首頁</h3></a>
   </div> 


    <!-- 排行榜 -->
    <div class="ranking-list" id="s01">
        <?php
        foreach ($rankings as $index => $photo) {
            $rank = $index + 1;
            echo "<div class='ranking-item'>";
            echo "<span>#$rank</span>";
            echo "<img src='uploads/{$photo['file_name']}' alt='{$photo['file_name']}'>";
            echo "<span>{$photo['file_name']}</span>";
            echo "<span class='votes'>{$photo['votes']} 票</span>";
            echo "</div>";
        }
        ?>
    </div>

    <!-- 圖表 -->
    <canvas id="voteChart"></canvas>

    <!-- Chart.js 庫 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('voteChart').getContext('2d');
        const rankings = <?php echo json_encode($rankings); ?>;

        new Chart(ctx, {
            type: 'bar', // 柱狀圖
            data: {
                labels: rankings.map(item => item.id), // X 軸標籤（檔案名稱）
                datasets: [{
                    label: '票數',
                    data: rankings.map(item => item.votes), // Y 軸數據（票數）
                    backgroundColor: 'rgba(231, 76, 60, 0.7)', // 柱狀圖顏色
                    borderColor: 'rgba(231, 76, 60, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: '票數' }
                    },
                    x: {
                        title: { display: true, text: '照片' }
                    }
                },
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: '投票結果圖表', font: { size: 18 } }
                }
            }
        });
    </script>
</body>
</html>