<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>寵物餐廳社群互動</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/mycolor.css">
    <style>
        /* body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        h1, h2 { color: #333; }
        .gallery { display: flex; flex-wrap: wrap; gap: 10px; }
        .gallery img { width: 150px; height: 150px; object-fit: cover; border: 1px solid #ddd; } */
        body {
            font-family: "Zen Maru Gothic", serif;
        }
        .comment {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }

        textarea {
            width: 100%;
            height: 80px;
        }

        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .body {
            font-family: "Zen Maru Gothic", serif;
            /* font-family: 'Arial', sans-serif; */
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            color: #333;
        }

        h1 {
            text-align: center;
            font-size: 2.5em;
            color: #2c3e50;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 1.8em;
            color: #34495e;
            margin-bottom: 20px;
        }

        .fw900 {
            text-align: center;
            font-size: 2.5em;
            color: #2c3e50;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
            padding: 20px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .gallery-item:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .gallery-item img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
        }

        .vote-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background 0.3s ease;
        }

        .vote-btn:hover {
            background: #c0392b;
        }

        .vote-btn:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }

        .vote-count {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
        }
        .banner nav ul {
            display: inline-block;
        }

        .banner nav ul li {
            float: left;
            list-style-type: none;
        }

        .banner nav ul li a {
            padding-top: 15px;
            padding-bottom: 15px;
            padding-left: 20px;
            padding-right: 20px;
            margin-right: 5px;
            text-decoration: none;
            color: var(--mycolor30);
            font-size: 20px;
        }

        .banner nav ul li a:hover {
            color: var(--mycolor30);
            background-color: rgba(50, 67, 95, 0.5);
            border-radius: 25px;
        }
        .myroller6 {
            position: relative
        }

        .myroller6::before {
            content: "";

            position: absolute;
            width: 80px;
            height: 110px;
            text-align: center;
            background-image: url(images/icon/b-icon-2.png);

            margin-top: 30px;
            background-position: center center;
            background-size: cover;




        }

        .myroller6:hover::before {
            background-image: url(images/icon/b-icon-1.png);

            margin-top: 0;
            top: 0;
            transition-duration: 2s;
            overflow: hidden;



        }

        .myroller9 {
            position: relative
        }

        .myroller9::before {
            content: "";

            position: absolute;
            width: 80px;
            height: 110px;
            text-align: center;
            background-image: url(images/icon/p-icon-2.png);

            margin-top: 30px;
            background-position: center center;
            background-size: cover;




        }

        .myroller9:hover::before {
            background-image: url(images/icon/p-icon-1.png);

            margin-top: 0;
            top: 0;
            transition-duration: 2s;
            overflow: hidden;



        }

        .myroller10 {
            position: relative
        }

        .myroller10::before {
            content: "";

            position: absolute;
            width: 80px;
            height: 110px;
            text-align: center;
            background-image: url(images/icon/pk-icon-2.png);

            margin-top: 30px;
            background-position: center center;
            background-size: cover;




        }

        .myroller10:hover::before {
            background-image: url(images/icon/pk-icon-1.png);

            margin-top: 0;
            top: 0;
            transition-duration: 2s;
            overflow: hidden;



        }

        .myroller7 {
            position: relative
        }

        .myroller7::before {
            content: "";

            position: absolute;
            width: 80px;
            height: 110px;
            text-align: center;
            background-image: url(images/icon/c-icon-2.png);

            margin-top: 30px;
            background-position: center center;
            background-size: cover;




        }

        .myroller7:hover::before {
            background-image: url(images/icon/c-icon-1.png);

            margin-top: 0;
            top: 0;
            transition-duration: 2s;
            overflow: hidden;



        }

        .myroller8 {
            position: relative
        }

        .myroller8::before {
            content: "";

            position: absolute;
            width: 80px;
            height: 110px;
            text-align: center;
            background-image: url(images/icon/d-icon-2.png);

            margin-top: 30px;
            background-position: center center;
            background-size: cover;




        }

        .myroller8:hover::before {
            background-image: url(images/icon/d-icon-1.png);

            margin-top: 0;
            top: 0;
            transition-duration: 2s;
            overflow: hidden;



        }
    </style>
</head>

<body style="background-color:rgba(236, 229, 217, 1);">
    <section id="s01">
        <div class="row ">
            <div class="col-6">
                <div class="p-header_title">
                    <a href="langlangdontcry-index.html"><img src="images/dogshop08.png" width="100px"></a>
                </div>
            </div>
        </div>
    </section>

    <div class="row justify-content-end mb-5">

        <div class="col-1">
            <span class="h4 text-003 fw-900 me-3 d-none" id="s02_username_showtext">歡迎會員: <span class="h3 text-003"
                    id="s02_username_text">XXX</span></span>
            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#loginModal"
                id="s02_login_btn">登入</button>
        </div>
        <div class="col-1">
            <button class="btn btn-secondary d-none" id="s02_logout_btn">登出</button>
        </div>
    </div>


    <div class="row justify-content-end box01 d-none" id="myElement">
        <div class="col-12">
            <nav class="navbar" style="background-color:var(--mycolor44);">
                <div class="container">
                    <a class="navbar" href="#"></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse fw-900 h5" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="langlangdontcry-index.html">首頁</a>
                            </li>
                            <li class="nav-item d-none" id="s02_menu_btn">
                                <a class="nav-link fw-900" href="langlangdontcry-index.html">菜單</a>
                            </li>
                            <li class="nav-item d-none" id="s02_orderseat_btn">
                                <a class="nav-link fw-900" href="langlangdontcry-book-index.html">訂位</a>
                            </li>
                            <li class="nav-item d-none" id="s03_orderseat_btn">
                                <a class="nav-link fw-900" href="langlangdontcry-yt-index.html">youtube</a>
                            </li>
                            <li class="nav-item d-none" id="s04_orderseat_btn">
                                <a class="nav-link fw-900" href="langlangdontcry-map-index.html">分店資訊</a>
                            </li>
                            <li class="nav-item dropdown d-none" id="s02_control_panel_btn_navbar">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">管理後台</a>
                                <ul class="dropdown-menu text-003">
                                    <li class="nav-item d-none text-003" id="s04_control_panel_btn">
                                        <a class="nav-link h3 fw-900"
                                            href="langlangdontcry-member-control-panel_v1.html" target="_blank">會員管理</a>
                                    </li>
                                    <li class="nav-item d-none text-003" id="s03_menu_panel_btn_navbar">
                                        <a class="nav-link h3 fw-900" href="20250109-C.html" target="_blank">菜單上架</a>
                                    </li>
                                    <li class="nav-item d-none text-003" id="s02_orderseat_btn_navbar">
                                        <a class="nav-link h3 fw-900" href="20250109-C.html" target="_blank">訂單管理</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>

 
    <section id="s021" class="mb-5">
        <img src="images/dogshop14.png" style="width: 1980px;" class="bg-cover">
    </section>

    <section>
        <div class="row  justify-content-end  mt-5">
            <div class="col-md-6 col-lg-4 fw-900 ">
                <div class="banner">
                    <nav>
                        <ul id="menu-global-nav" class="p-header_lists">

                            <li class="p-header_list d-none " id="s01_menu_btn"><a
                                    href="langlangdontcry-index.html">菜單</a></li>
                            <li class="p-header_list d-none " id="s01_orderseat_btn"><a
                                    href="langlangdontcry-book-index.html">訂位</a></li>
                            <li class="p-header_list d-none " id="s02_youtube_btn"><a
                                    href="langlangdontcry-yt-index.html">youtube</a></li>
                            <li class="p-header_list d-none " id="s01_map_btn"><a
                                    href="langlangdontcry-map-index.html">分店資訊</a></li>
                            <li class="p-header_list  " id=" "><a href="langlangdontcry-photo-index.html">寵物分享區</a></li>

                            <!-- <li class="p-header_list d-none" id="s03_menu_panel_btn"><a href="20250109-C.html"
                                target="_blank">菜單上架</a></li>
                        <li class="p-header_list d-none" id="s02_control_panel_btn"><a
                                href="langlangdontcry-member-control-panel_v1.html" target="_blank">控制台</a></li> -->

                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>


    <h1 class="mt-5 mb-5">與寵物共享美食時光</h1>

    <!-- 照片上傳 -->
    <!-- 照片展示 -->
    <section class="body mt-5">
        <h2>上傳你與寵物的照片</h2>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="photo" accept="image/*" class="btn btn-secondary" required>
            <button type="submit">上傳</button>
        </form>
        <h2 class="mt-3">顧客照片牆</h2>

        <section class="mt-3 mb-3">
            <div>
                <a href="http://192.168.10.103/project/ranking.php" class=" fw900 ">排行榜</a>
            </div>
        </section>
        <div class="gallery">
            <?php
            $conn = new mysqli("localhost", "owner01", "123456", "project");
            if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

            $ip_address = $_SERVER["REMOTE_ADDR"];
            $result = $conn->query("SELECT * FROM photos ORDER BY upload_time DESC");
            while ($row = $result->fetch_assoc()) {
                $photo_id = $row['id'];
                // 檢查當前 IP 是否已對此照片投票
                $stmt = $conn->prepare("SELECT COUNT(*) FROM votes WHERE photo_id = ? AND ip_address = ?");
                $stmt->bind_param("is", $photo_id, $ip_address);
                $stmt->execute();
                $stmt->bind_result($has_voted);
                $stmt->fetch();
                $stmt->close();

                echo "<div class='gallery-item'>";
                echo "<img src='uploads/{$row['file_name']}' alt='Pet Photo'>";
                echo "<span class='vote-count'>{$row['votes']} 票</span>";
                echo "<form method='POST' action='vote.php'>";
                echo "<input type='hidden' name='photo_id' value='$photo_id'>";
                echo "<button type='submit' class='vote-btn'" . ($has_voted ? " disabled" : "") . ">投票</button>";
                echo "</form>";
                echo "</div>";
            }
            $conn->close();
            ?>
        </div>
    </section>





    <!-- 評論區 -->
    <section class="body mt-3 mb-3">
        <h2>分享你的用餐體驗</h2>
        <form action="comment.php" method="POST">
            <textarea name="comment" placeholder="寫下你的想法..." required></textarea>
            <button type="submit">提交評論</button>
        </form>
        <div>
            <?php
            $conn = new mysqli("localhost", "owner01", "123456", "project");
            if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

            $result = $conn->query("SELECT * FROM comments ORDER BY submit_time DESC");
            while ($row = $result->fetch_assoc()) {
                echo "<div class='comment'>" . htmlspecialchars($row['comment_text']) . "</div>";
            }
            $conn->close();
            ?>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-1 myroller6">
                    <a href="https://x.com/rockakuofficial/" class=" p-3 " target="_blank">
                        <!-- <img src="images/icon/b-icon-1.png" alt="Xアイコン"
                            width="80" height="80" loading="lazy" decoding="async"> -->
                    </a>
                </div>
                <div class="col-1">
                    <a href="https://x.com/rockakuofficial/" class=" p-3 myroller7" target="_blank">
                        <!-- <img src="images/icon/b-icon-1.png" alt="Xアイコン"
                            width="80" height="80" loading="lazy" decoding="async"> -->
                    </a>
                </div>
                <div class="col-1">
                    <a href="https://x.com/rockakuofficial/" class=" p-3 myroller8" target="_blank">
                        <!-- <img src="images/icon/b-icon-1.png" alt="Xアイコン"
                            width="80" height="80" loading="lazy" decoding="async"> -->
                    </a>
                </div>
                <div class="col-1">
                    <a href="https://x.com/rockakuofficial/" class=" p-3 myroller9" target="_blank">
                        <!-- <img src="images/icon/b-icon-1.png" alt="Xアイコン"
                            width="80" height="80" loading="lazy" decoding="async"> -->
                    </a>
                </div>
                <div class="col-1">
                    <a href="https://x.com/rockakuofficial/" class="p-3 myroller10" target="_blank">
                        <!-- <img src="images/icon/b-icon-1.png" alt="Xアイコン"
                            width="80" height="80" loading="lazy" decoding="async"> -->
                    </a>
                </div>
            </div>
        </div>

    </section>
</body>

</html>