<?php
$conn = new mysqli("localhost", "owner01", "123456", "project");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$query = "SELECT id, file_name, votes FROM photos ORDER BY votes DESC LIMIT 5";
$result = $conn->query($query);

$top_five = [];
while ($row = $result->fetch_assoc()) {
    $top_five[] = $row;
}
$conn->close();

if (empty($top_five)) {
    die("目前沒有足夠的照片進行抽獎！");
}
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>前五名抽獎輪盤</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at center, #1e3c72, #2a5298);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .container {
            position: relative;
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        h1 {
            color: #ff6f61;
            font-size: 2.5em;
            text-shadow: 0 2px 10px rgba(255, 111, 97, 0.5);
            margin-bottom: 30px;
            animation: glow 2s infinite alternate;
        }

        #wheel-container {
            position: relative;
            width: 450px;
            height: 450px;
            margin: 0 auto;
        }

        #wheel {
            display: block;
            border-radius: 50%;
            box-shadow: 0 0 20px rgba(255, 111, 97, 0.6), inset 0 0 15px rgba(0, 0, 0, 0.2);
        }

        #pointer {
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 40px;
            background: #ff6f61;
            clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
            z-index: 10;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        #spin-btn {
            margin-top: 40px;
            padding: 15px 40px;
            font-size: 1.3em;
            color: #fff;
            background: linear-gradient(45deg, #ff6f61, #de4d4d);
            border: none;
            border-radius: 30px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(255, 111, 97, 0.5);
            transition: all 0.3s ease;
        }

        #spin-btn:hover {
            background: linear-gradient(45deg, #de4d4d, #ff6f61);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 111, 97, 0.7);
        }

        #spin-btn:disabled {
            background: #b0bec5;
            transform: none;
            box-shadow: none;
            cursor: not-allowed;
        }

        #result {
            margin-top: 30px;
            font-size: 1.8em;
            color: #ffca28;
            text-shadow: 0 2px 10px rgba(255, 202, 40, 0.5);
            display: none;
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes glow {
            from {
                text-shadow: 0 2px 10px rgba(255, 111, 97, 0.5);
            }

            to {
                text-shadow: 0 2px 15px rgba(255, 111, 97, 0.8);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>前五名抽獎輪盤</h1>
        <div id="wheel-container">
            <canvas id="wheel" width="450" height="450"></canvas>
            <div id="pointer"></div>
        </div>
        <button id="spin-btn">開始抽獎</button>
        <div id="result"></div>
        <div>
             <h3 class="mb-5 mt-5">
            <a href="http://192.168.10.103/project/ranking.php">回排行榜</a>
        </h3>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('wheel');
        const ctx = canvas.getContext('2d');
        const spinBtn = document.getElementById('spin-btn');
        const resultDiv = document.getElementById('result');
        const items = <?php echo json_encode($top_five); ?>;
        let currentAngle = 0;
        let isSpinning = false;

        // 載入現成的輪盤圖片
        const wheelImage = new Image();
        wheelImage.src = 'uploads/wheel.png'; // 替換為你的輪盤圖片路徑
        wheelImage.onload = () => drawWheel();

        function drawWheel() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.save();
            ctx.translate(canvas.width / 2, canvas.height / 2);
            ctx.rotate(currentAngle * Math.PI / 180);
            ctx.drawImage(wheelImage, -canvas.width / 2, -canvas.height / 2, canvas.width, canvas.height);
            ctx.restore();
        }

        spinBtn.addEventListener('click', () => {
            if (isSpinning || !wheelImage.complete) return;
            isSpinning = true;
            spinBtn.disabled = true;

            const winnerIndex = Math.floor(Math.random() * items.length);
            const winner = items[winnerIndex];
            const spins = 6; // 轉動圈數增加到 6 圈
            const anglePerItem = 360 / items.length;
            const targetAngle = spins * 360 + (winnerIndex * anglePerItem);

            let startTime = null;
            const duration = 5000; // 5秒動畫，加強視覺效果

            function animate(timestamp) {
                if (!startTime) startTime = timestamp;
                const progress = (timestamp - startTime) / duration;
                currentAngle = easeOut(progress) * targetAngle;
                drawWheel();

                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    resultDiv.style.display = 'block';
                    resultDiv.textContent = `恭喜 ${winner.votes} 獲得大獎！（票數：${winner.votes}）`;
                    isSpinning = false;
                    spinBtn.textContent = '抽獎結束';
                }
            }

            requestAnimationFrame(animate);
        });

        // 減速動畫效果
        function easeOut(t) {
            return 1 - Math.pow(1 - t, 4); // 更平滑的減速
        }
    </script>
</body>

</html>