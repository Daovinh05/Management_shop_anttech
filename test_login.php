<?php
/**
 * File test để kiểm tra kết nối database và đăng nhập
 * Truy cập: https://ten-mien-cua-ban.com/test_login.php
 */

// Start session
session_start();

// Include config
include_once __DIR__ . '/MVC/Core/Config.php';

echo "<h1>KIỂM TRA ĐĂNG NHẬP</h1>";
echo "<hr>";

// 1. Kiểm tra base URL
echo "<h2>1. Base URL:</h2>";
echo "<p><strong>BASE_URL = </strong>" . BASE_URL . "</p>";
echo "<p><strong>SCRIPT_NAME = </strong>" . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p><strong>HTTP_HOST = </strong>" . $_SERVER['HTTP_HOST'] . "</p>";
echo "<p><strong>REQUEST_URI = </strong>" . $_SERVER['REQUEST_URI'] . "</p>";
echo "<hr>";

// 2. Kiểm tra kết nối database
echo "<h2>2. Kết nối Database:</h2>";
include_once __DIR__ . '/MVC/Core/connectDB.php';

try {
    $db = new connectDB();
    echo "<p style='color: green;'>✓ Kết nối database THÀNH CÔNG!</p>";
    echo "<p>DB_HOST: " . DB_HOST . "</p>";
    echo "<p>DB_NAME: " . DB_NAME . "</p>";
    echo "<p>DB_USER: " . DB_USER . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Kết nối database THẤT BẠI: " . $e->getMessage() . "</p>";
}
echo "<hr>";

// 3. Kiểm tra users trong database
echo "<h2>3. Danh sách Users:</h2>";
$sql = "SELECT ma_user, ten_user, email, phan_quyen FROM users LIMIT 10";
$result = mysqli_query($db->con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ma_user</th><th>ten_user</th><th>email</th><th>phan_quyen</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['ma_user']) . "</td>";
        echo "<td>" . htmlspecialchars($row['ten_user']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['phan_quyen']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>✗ Không có user nào trong database!</p>";
}
echo "<hr>";

// 4. Test validate user
echo "<h2>4. Test Validate User:</h2>";
echo "<form method='POST'>";
echo "<p>Username: <input type='text' name='test_username' required></p>";
echo "<p>Password: <input type='password' name='test_password' required></p>";
echo "<p><button type='submit' name='test_login'>Test Đăng Nhập</button></p>";
echo "</form>";

if (isset($_POST['test_login'])) {
    $username = $_POST['test_username'];
    $password = $_POST['test_password'];
    
    echo "<h3>Kết quả test:</h3>";
    echo "<p>Username: <strong>" . htmlspecialchars($username) . "</strong></p>";
    echo "<p>Password: <strong>" . htmlspecialchars($password) . "</strong></p>";
    
    // Test query
    $sql = "SELECT * FROM users WHERE (ten_user = '$username' OR email = '$username') AND password = '$password'";
    echo "<p>SQL Query: <code>" . htmlspecialchars($sql) . "</code></p>";
    
    $result = mysqli_query($db->con, $sql);
    
    if ($result) {
        $count = mysqli_num_rows($result);
        echo "<p style='color: " . ($count > 0 ? 'green' : 'red') . ";'>";
        echo ($count > 0 ? '✓' : '✗') . " Tìm thấy <strong>" . $count . "</strong> user phù hợp";
        echo "</p>";
        
        if ($count > 0) {
            $user = mysqli_fetch_assoc($result);
            echo "<pre>";
            print_r($user);
            echo "</pre>";
            
            // Test session
            $_SESSION['user_id'] = $user['ma_user'];
            $_SESSION['user_name'] = $user['ten_user'];
            $_SESSION['user_role'] = $user['phan_quyen'];
            
            echo "<p style='color: green;'>✓ Session đã được set!</p>";
            echo "<p>Session user_id: " . $_SESSION['user_id'] . "</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Lỗi query: " . mysqli_error($db->con) . "</p>";
    }
}

echo "<hr>";
echo "<p><a href='index.php'>← Quay lại trang chủ</a></p>";
?>
