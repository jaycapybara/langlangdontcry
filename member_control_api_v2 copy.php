<?php
const DB_SERVER   = "localhost";
const DB_USERNAME = "owner01";
const DB_PASSWORD = "123456";
const DB_NAME     = "project";

//建立連線
function create_connection()
{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        echo json_encode(["state" => false, "message" => "連線失敗!"]);
        exit;
    }
    return $conn;
}

header('Content-Type: application/json');
//取得JSON的資料
function get_json_input()
{
    $data = file_get_contents("php://input");
    return json_decode($data, true);
}


//回復JOSN訊息
//state: 狀態(成功或失敗) message: 訊息內容 data: 回傳資料(可有可無)
function respond($state, $message, $data = null)
{
    echo json_encode(["state" => $state, "message" => $message, "data" => $data]);
}

//會員註冊
// {"username" : "owner01", "password" : "123456", "phone" : "0933511199"}
// {"state" : true, "message" : "註冊成功"}
// {"state" : false, "message" : "新增失敗與相關錯誤訊息"}
// {"state" : false, "message" : "欄位錯誤"}
// {"state" : false, "message" : "欄位不得為空"}
function register_user()
{
    $input = get_json_input();
    if (isset($input["username"], $input["password"], $input["phone"],)) {
        $p_username = $input["username"];
        $p_password = password_hash(trim($input["password"]), PASSWORD_DEFAULT);
        $p_phone    = trim($input["phone"]);
        if ($p_username && $p_password && $p_phone) {
            $conn = create_connection();

            $stmt = $conn->prepare("INSERT INTO member1(Username, Password, Phone) VALUES(?, ?, ?)");
            $stmt->bind_param("sss", $p_username, $p_password, $p_phone);

            if ($stmt->execute()) {
                respond(true, "註冊成功");
            } else {
                respond(false, "註冊失敗");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不得為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }

    
}

//會員登入
// {"username" : "owner01", "password" : "123456"}
// {"state" : true, "message" : "登入成功", "data" : "使用者資訊"}
// {"state" : false, "message" : "登入失敗與相關錯誤訊息"}
// {"state" : false, "message" : "欄位錯誤"}
// {"state" : false, "message" : "欄位不得為空"}



//Uid01驗證
// {"uid01" : "owner01"}
// {"state" : true, "message" : "驗證成功", "data" : "使用者資訊"}
// {"state" : false, "message" : "驗證失敗與相關錯誤訊息"}
// {"state" : false, "message" : "欄位錯誤"}
// {"state" : false, "message" : "欄位不得為空"}
function check_uid()
{
    $input = get_json_input();
    if (isset($input["uid01"])) {
        $p_uid = trim($input["uid01"]);
        if ($p_uid) {
            $conn = create_connection();

            // $stmt = $conn->prepare("SELECT Username, Phone, Uid01, Created_at FROM member1 WHERE Uid01 = ?");
            $stmt = $conn->prepare("SELECT Username, Phone, Uid01, Created_at, membership_level FROM member1 WHERE Uid01 = ?");
            $stmt->bind_param("s", $p_uid); //一定要傳遞變數
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                //驗證成功
                $userdata = $result->fetch_assoc();
                respond(true, "驗證成功", $userdata);
            } else {
                respond(false, "驗證失敗");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不得為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}


//驗證帳號是否已經存在(給註冊介面使用)
function check_uni_username()
{
    $input = get_json_input();
    if (isset($input["username"])) {
        $p_username = trim($input["username"]);
        if ($p_username) {
            $conn = create_connection();

            $stmt = $conn->prepare("SELECT Username FROM member1 WHERE Username = ?");
            $stmt->bind_param("s", $p_username); //一定要傳遞變數
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                //帳號已存在
                respond(false, "帳號已存在, 不可以使用");
            } else {
                //帳號不存在
                respond(true, "帳號不存在, 可以使用");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不得為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

// 依會員等級顯示UI

function check_memberlevel()
{
    $input = get_json_input();
    if (isset($input["uid01"])) {  // 改用 uid01 以保持一致性
        $p_uid = trim($input["uid01"]);
        if ($p_uid) {
            $conn = create_connection();

            $stmt = $conn->prepare("SELECT membership_level FROM member1 WHERE Uid01 = ?");
            $stmt->bind_param("s", $p_uid);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                respond(true, "等級驗證成功", ["level" => $row["membership_level"]]);
            } else {
                respond(false, "找不到會員等級");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不得為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

// 修改 login_user 函數以包含會員等級
function login_user()
{
    $input = get_json_input();
    error_log("登入輸入: " . print_r($input, true)); // 記錄輸入數據
    if (isset($input["username"], $input["password"])) {
        $p_username = trim($input["username"]);
        $p_password = trim($input["password"]);
        if ($p_username && $p_password) {
            $conn = create_connection();

            $stmt = $conn->prepare("SELECT * FROM member1 WHERE Username = ?");
            $stmt->bind_param("s", $p_username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                error_log("資料庫密碼: " . $row["Password"]); // 記錄查到的密碼
                if (password_verify($p_password, $row["Password"])) {
                    $uid01 = substr(hash('sha256', time()), 10, 4) . substr(bin2hex(random_bytes(16)), 4, 4);
                    $update_stmt = $conn->prepare("UPDATE member1 SET Uid01 = ? WHERE Username = ?");
                    $update_stmt->bind_param('ss', $uid01, $p_username);
                    
                    if ($update_stmt->execute()) {
                        $user_stmt = $conn->prepare("SELECT Username, Phone, Uid01, Created_at, membership_level FROM member1 WHERE Username = ?");
                        $user_stmt->bind_param("s", $p_username);
                        $user_stmt->execute();
                        $user_data = $user_stmt->get_result()->fetch_assoc();
                        
                        error_log("登入成功，返回數據: " . print_r($user_data, true)); // 記錄成功數據
                        respond(true, "登入成功", $user_data);
                    } else {
                        error_log("UID更新失敗: " . $conn->error); // 記錄錯誤
                        respond(false, "登入失敗, UID更新失敗");
                    }
                } else {
                    error_log("密碼驗證失敗"); // 記錄密碼錯誤
                    respond(false, "登入失敗, 密碼錯誤");
                }
            } else {
                error_log("帳號不存在"); // 記錄帳號問題
                respond(false, "登入失敗, 該帳號不存在");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不得為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

function login1_user() {
    $input = get_json_input();
    if (isset($input["username"], $input["password"])) {
        $p_username = trim($input["username"]);
        $p_password = trim($input["password"]);
        if ($p_username && $p_password) {
            $conn = create_connection();
            $stmt = $conn->prepare("SELECT * FROM member1 WHERE Username = ?");
            $stmt->bind_param("s", $p_username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                if (password_verify($p_password, $row["Password"])) {
                    $uid01 = substr(hash('sha256', time()), 10, 4) . substr(bin2hex(random_bytes(16)), 4, 4);
                    $update_stmt = $conn->prepare("UPDATE member1 SET Uid01 = ? WHERE Username = ?");
                    $update_stmt->bind_param('ss', $uid01, $p_username);
                    
                    if ($update_stmt->execute()) {
                        $user_data = [
                            'user_id' => $row['ID'],
                            'username' => $row['Username'],
                            'uid01' => $uid01
                        ];
                        respond(true, "登入成功", $user_data);
                    } else {
                        respond(false, "登入失敗, UID更新失敗");
                    }
                } else {
                    respond(false, "登入失敗, 密碼錯誤");
                }
            } else {
                respond(false, "登入失敗, 該帳號不存在");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不得為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

// 修改 get_all_user_data 以包含會員等級
function get_all_user_data()
{
    $conn = create_connection();
    $stmt = $conn->prepare("SELECT ID, Username, Phone, Created_at, membership_level FROM member1 ORDER BY ID DESC");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $mydata = array();
        while ($row = $result->fetch_assoc()) {
            $mydata[] = $row;
        }
        respond(true, "取得所有會員資料成功", $mydata);
    } else {
        respond(false, "查無資料");
    }
    $stmt->close();
    $conn->close();
}


//會員更新
// {"id" : "xxxxxx", "phone" : "xxxxxx"}
// {"state" : true, "message" : "會員更新成功"}
// {"state" : false, "message" : "會員更新失敗與相關錯誤訊息"}
// {"state" : false, "message" : "欄位錯誤"}
// {"state" : false, "message" : "欄位不得為空白"}
function update_user()
{
    $input = get_json_input();
    if (isset($input["id"], $input["phone"])) {
        $p_id = trim($input["id"]);
        $p_phone = trim($input["phone"]);
        if ($p_id && $p_phone) {
            $conn = create_connection();

            $stmt = $conn->prepare("UPDATE member1 SET Phone = ? WHERE ID = ?");
            $stmt->bind_param("si", $p_phone, $p_id); //一定要傳遞變數

            if ($stmt->execute()) {
                if ($stmt->affected_rows === 1) {
                    respond(true, "會員更新成功");
                } else {
                    respond(false, "會員更新失敗, 並無更新行為!");
                }
            } else {
                respond(false, "會員更新失敗");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不得為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

//會員刪除
// {"id" : "xxxxxx"}
// {"state" : true, "message" : "會員刪除成功"}
// {"state" : false, "message" : "會員刪除失敗與相關錯誤訊息"}
// {"state" : false, "message" : "欄位錯誤"}
// {"state" : false, "message" : "欄位不得為空白"}
function delete_user()
{
    $input = get_json_input();
    if (isset($input["id"])) {
        $p_id = trim($input["id"]);
        if ($p_id) {
            $conn = create_connection();

            $stmt = $conn->prepare("DELETE FROM member1 WHERE ID = ?");
            $stmt->bind_param("i", $p_id); //一定要傳遞變數

            if ($stmt->execute()) {
                if ($stmt->affected_rows === 1) {
                    respond(true, "會員刪除成功");
                } else {
                    respond(false, "會員刪除失敗, 並無刪除行為!");
                }
            } else {
                respond(false, "會員刪除失敗");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不得為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}
function get_player_info() {
    $input = get_json_input();
    if (isset($input["uid01"])) {
        $p_uid = trim($input["uid01"]);
        if ($p_uid) {
            $conn = create_connection();
            $stmt = $conn->prepare("
                SELECT m.ID as user_id, p.player_name 
                FROM member1 m 
                LEFT JOIN player_profiles p ON m.ID = p.user_id 
                WHERE m.Uid01 = ?
            ");
            $stmt->bind_param("s", $p_uid);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $data = $result->fetch_assoc();
                respond(true, "取得玩家資訊成功", $data);
            } else {
                respond(false, "找不到玩家資訊");
            }
            $stmt->close();
            $conn->close();
        } else {
            respond(false, "欄位不得為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

// 新增設定玩家名稱的函數
function set_player_name() {
    $input = get_json_input();
    if (isset($input["uid01"], $input["player_name"])) {
        $p_uid = trim($input["uid01"]);
        $p_name = trim($input["player_name"]);
        if ($p_uid && $p_name) {
            $conn = create_connection();
            
            // 先取得 user_id
            $stmt = $conn->prepare("SELECT ID FROM member1 WHERE Uid01 = ?");
            $stmt->bind_param("s", $p_uid);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                $user_id = $user['ID'];
                
                // 檢查是否已有玩家名稱
                $check_stmt = $conn->prepare("SELECT player_id FROM player_profiles WHERE user_id = ?");
                $check_stmt->bind_param("i", $user_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                
                if ($check_result->num_rows > 0) {
                    // 更新現有名稱
                    $update_stmt = $conn->prepare("UPDATE player_profiles SET player_name = ? WHERE user_id = ?");
                    $update_stmt->bind_param("si", $p_name, $user_id);
                    $success = $update_stmt->execute();
                } else {
                    // 插入新名稱
                    $insert_stmt = $conn->prepare("INSERT INTO player_profiles (user_id, player_name) VALUES (?, ?)");
                    $insert_stmt->bind_param("is", $user_id, $p_name);
                    $success = $insert_stmt->execute();
                }
                
                if ($success) {
                    respond(true, "玩家名稱設定成功", ['player_name' => $p_name]);
                } else {
                    respond(false, "玩家名稱設定失敗");
                }
            } else {
                respond(false, "無效的使用者");
            }
            $conn->close();
        } else {
            respond(false, "欄位不得為空");
        }
    } else {
        respond(false, "欄位錯誤");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? '';
    switch ($action) {
        case 'register':
            register_user();
            break;
        case 'login':
            login_user();
            break;
            case 'login1':
                login1_user();
                break;


        
        case 'checkuid':
            check_uid();
            break;

        case 'checkuni':
            check_uni_username();
            break;
        case 'update':
            update_user();
            break;

            case 'getplayerinfo':
                get_player_info();
                break;
            case 'setplayername':
                set_player_name();
                break;
        default:
            respond(false, "無效的操作");
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    switch ($action) {
        case 'getalldata':
            get_all_user_data();
            break;
        case 'memberlevel':
            check_memberlevel();
        default:
            respond(false, "無效的操作");
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $action = $_GET['action'] ?? '';
    switch ($action) {
        case 'delete':
            delete_user();
            break;
        default:
            respond(false, "無效的操作");
    }
} else {
    respond(false, "無效的請求方法");
}
