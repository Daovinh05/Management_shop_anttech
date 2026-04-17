<?php
include_once __DIR__ . '/../../Public/Classes/TimezoneHelper.php';
include_once __DIR__ . '/../../Public/Classes/UrlHelper.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Bán Hàng Điện Thoại</title>
    <base href="<?php echo UrlHelper::baseUrl(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo UrlHelper::url('Public/Css/style.css?v=3'); ?>">
    <style>

    </style>
</head>

<body>
    <div class="app-container">
        <aside class="sidebar">
            <div class="brand">
                📱 Phone Store
            </div>
            <?php $current = isset($data['page']) ? $data['page'] : ''; ?>
            <nav class="menu_left1">
                <ul>
                    <li>
                        <a href="<?php echo UrlHelper::url('Quanly'); ?>"
                            class="<?php echo (strpos($current, 'Quanly') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-chart-pie"></i> Tổng quan
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo UrlHelper::url('Users/danhsach'); ?>"
                            class="<?php echo (strpos($current, 'danhsachusers_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-users"></i> Quản lý người dùng
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo UrlHelper::url('Danhmuc/danhsach'); ?>"
                            class="<?php echo (strpos($current, 'danhsachdanhmuc_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-list"></i> Quản lý danh mục
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo UrlHelper::url('Thuonghieu/danhsach'); ?>"
                            class="<?php echo (strpos($current, 'danhsachthuonghieu_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-copyright"></i> Quản lý thương hiệu
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo UrlHelper::url('Nhacungcap/danhsach'); ?>"
                            class="<?php echo (strpos($current, 'danhsachnhacungcap_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-truck"></i> Quản lý nhà cung cấp
                        </a>
                    </li>
                    <!-- <li>
                        <a href="<?php echo UrlHelper::url('Danhgia/danhsach'); ?>"
                            class="<?php echo (strpos($current, 'danhsachdanhgia') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-truck"></i> Quản lý đánh giá
                        </a>
                    </li> -->
                    <li>
                        <a href="<?php echo UrlHelper::url('Sanpham/danhsach'); ?>"
                            class="<?php echo (strpos($current, 'danhsachsanpham_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-mobile-alt"></i> Quản lý sản phẩm
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo UrlHelper::url('BienThe/danhsach'); ?>"
                            class="<?php echo (strpos($current, 'danhsachbienthe_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-sliders-h"></i> Quản lý biến thể
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo UrlHelper::url('Khuyenmai/danhsach'); ?>"
                            class="<?php echo (strpos($current, 'danhsachkhuyenmai_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-gift"></i> Quản lý khuyến mãi
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo UrlHelper::url('Danhgia/danhsach'); ?>"
                            class="<?php echo (strpos($current, 'danhsachkhuyenmai_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-percentage"></i> Quản lý đánh giá
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo UrlHelper::url('Donhang/danhsach'); ?>"
                            class="<?php echo (strpos($current, 'danhsachdonhang_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-shopping-cart"></i> Quản lý đơn hàng
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo UrlHelper::url('Thongke'); ?>"
                            class="<?php echo (strpos($current, 'Thongkedoanhthu_v') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-chart-line"></i> Thống kê
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo UrlHelper::url('Login/logout'); ?>" class="logout-btn1 js-api-logout" data-redirect="<?php echo UrlHelper::url('Login'); ?>" title="Đăng xuất">
                            <i class="fa-solid fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="main-content">
            <header class="top-header">
                <div class="page-title">
                    <?php
                    if (strpos($current, 'Quanly') !== false) echo 'Dashboard';
                    elseif (strpos($current, 'Sanpham') !== false) echo 'Sản phẩm';
                    elseif (strpos($current, 'Danhmuc') !== false) echo 'Danh mục';
                    elseif (strpos($current, 'Thuonghieu') !== false) echo 'Thương hiệu';
                    elseif (strpos($current, 'Nhacungcap') !== false) echo 'Nhà cung cấp';
                    elseif (strpos($current, 'BienThe') !== false) echo 'Biến thể sản phẩm';
                    elseif (strpos($current, 'Khuyenmai') !== false) echo 'Khuyến mãi';
                    elseif (strpos($current, 'Donhang') !== false) echo 'Đơn hàng';
                    elseif (strpos($current, 'Users') !== false) echo 'Người dùng';
                    elseif (strpos($current, 'login') !== false) echo 'Đăng nhập';
                    elseif (strpos($current, 'register') !== false) echo 'Đăng ký';
                    else echo 'Quản trị hệ thống';
                    ?>
                </div>
                <div class="user-info">
                    <?php if (isset($_SESSION['user_name'])): ?>
                        <span>
                            Xin chào:
                            <?php
                            if (isset($_SESSION['user_id'])) {
                                // Kết nối database để lấy full_name
                                $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                                if ($conn) {
                                    $user_id = $_SESSION['user_id'];
                                    $query = "SELECT full_name FROM users WHERE ma_user = '$user_id'";
                                    $result = mysqli_query($conn, $query);
                                    if ($result && $row = mysqli_fetch_assoc($result)) {
                                        if (!empty($row['full_name'])) {
                                            echo htmlspecialchars($row['full_name']);
                                        } else {
                                            // Nếu không có full_name, hiển thị user_name
                                            echo htmlspecialchars($_SESSION['user_name']);
                                        }
                                    } else {
                                        echo htmlspecialchars($_SESSION['user_name']);
                                    }
                                    mysqli_close($conn);
                                } else {
                                    echo htmlspecialchars($_SESSION['user_name']);
                                }
                            } else {
                                echo 'Người dùng';
                            }
                            ?>
                            <?php if (isset($_SESSION['user_role'])): ?>
                                <span
                                    class="user-role">(<?php echo $_SESSION['user_role'] == 'admin' ? 'Quản trị viên' : 'Khách hàng'; ?>)</span>
                            <?php endif; ?>
                        </span>
                        <div class="avatar">
                            <?php
                            if (isset($_SESSION['user_id'])) {
                                // Kết nối database để lấy avatar
                                $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                                if ($conn) {
                                    $user_id = $_SESSION['user_id'];
                                    $query = "SELECT avatar FROM users WHERE ma_user = '$user_id'";
                                    $result = mysqli_query($conn, $query);
                                    if ($result && $row = mysqli_fetch_assoc($result)) {
                                        if (!empty($row['avatar'])) {
                                            echo '<img src="' . UrlHelper::url('Public/Pictures/users/') . htmlspecialchars($row['avatar']) . '" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%;">';
                                        } else {
                                            echo '<img src="' . UrlHelper::url('Public/Images/avatar.png') . '" alt="Avatar">';
                                        }
                                    } else {
                                        echo '<img src="' . UrlHelper::url('Public/Images/avatar.png') . '" alt="Avatar">';
                                    }
                                    mysqli_close($conn);
                                } else {
                                    echo '<img src="' . UrlHelper::url('Public/Images/avatar.png') . '" alt="Avatar">';
                                }
                            } else {
                                echo '<img src="' . UrlHelper::url('Public/Images/avatar.png') . '" alt="Avatar">';
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </header>

            <div class="content-area">
                <?php
                if (isset($data['page'])) {
                    $pagePath = __DIR__ . '/Pages/' . $data['page'] . ".php";
                    if (file_exists($pagePath)) {
                        include_once $pagePath;
                    } else {
                        echo "<div class='alert alert-danger'>Trang không tồn tại!</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        // JavaScript for additional functionality if needed
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(event) {
                var logoutLink = event.target.closest('.js-api-logout');
                if (!logoutLink) {
                    return;
                }
                event.preventDefault();
                var redirectUrl = logoutLink.getAttribute('data-redirect') || '<?php echo UrlHelper::url('Login'); ?>';
                fetch('<?php echo UrlHelper::url('Api/Auth/logout'); ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                    .then(function() {
                        window.location.href = redirectUrl;
                    })
                    .catch(function() {
                        window.location.href = logoutLink.getAttribute('href') || redirectUrl;
                    });
            });
        });
    </script>

    <!-- Chatbot Widget Start -->
    <style>
        #chatbot-widget {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 340px;
            max-width: 90vw;
            max-height: 450px;
            height: 450px;
            background: #fff;
            border-radius: 12px 12px 0 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.18);
            z-index: 9999;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        #chatbot-header {
            background: #1abc9c;
            color: #fff;
            padding: 12px 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        #chatbot-messages {
            flex: 1;
            padding: 12px;
            background: #f8f8f8;
            overflow-y: auto;
            font-size: 15px;
            max-height: 320px;
        }
        .chatbot-msg {
            margin-bottom: 10px;
            display: flex;
        }
        .chatbot-msg.user { justify-content: flex-end; }
        .chatbot-msg .msg {
            padding: 8px 14px;
            border-radius: 16px;
            max-width: 80%;
        }
        .chatbot-msg.user .msg {
            background: #1abc9c;
            color: #fff;
            border-bottom-right-radius: 4px;
        }
        .chatbot-msg.bot .msg {
            background: #eaeaea;
            color: #222;
            border-bottom-left-radius: 4px;
        }
        #chatbot-input-area {
            display: flex;
            border-top: 1px solid #eee;
            background: #fff;
        }
        #chatbot-input {
            flex: 1;
            border: none;
            padding: 10px;
            font-size: 15px;
            outline: none;
        }
        #chatbot-send {
            background: #1abc9c;
            color: #fff;
            border: none;
            padding: 0 18px;
            cursor: pointer;
            font-size: 16px;
        }
        #chatbot-toggle {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #1abc9c;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            font-size: 28px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.18);
            z-index: 9999;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    <button id="chatbot-toggle" title="Chatbot" style="display:block;"><i class="fa fa-comments"></i></button>
    <div id="chatbot-widget" style="display:none;">
        <div id="chatbot-header">
            <span>Chatbot hỗ trợ</span>
            <button onclick="document.getElementById('chatbot-widget').style.display='none';document.getElementById('chatbot-toggle').style.display='block';" style="background:none;border:none;color:#fff;font-size:18px;cursor:pointer;">&times;</button>
        </div>
        <div id="chatbot-messages"></div>
        <form id="chatbot-input-area" autocomplete="off">
            <input id="chatbot-input" type="text" placeholder="Nhập tin nhắn..." required />
            <button id="chatbot-send" type="submit">Gửi</button>
        </form>
    </div>
    <script>
        // Toggle chatbot
        document.getElementById('chatbot-toggle').onclick = function() {
            document.getElementById('chatbot-widget').style.display = 'flex';
            this.style.display = 'none';
        };
        // Chatbot logic
        const chatbotMessages = document.getElementById('chatbot-messages');
        function appendMsg(msg, sender) {
            const div = document.createElement('div');
            div.className = 'chatbot-msg ' + sender;
            div.innerHTML = `<div class="msg">${msg}</div>`;
            chatbotMessages.appendChild(div);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }
        // Welcome message
        appendMsg('Xin chào! Tôi có thể giúp gì cho bạn?', 'bot');
        document.getElementById('chatbot-input-area').onsubmit = function(e) {
            e.preventDefault();
            const input = document.getElementById('chatbot-input');
            const msg = input.value.trim();
            if (!msg) return;
            appendMsg(msg, 'user');
            input.value = '';
            // Gửi API PHP (ví dụ: /Api/Chatbot/ask)
            fetch('<?php echo UrlHelper::url('Api/Chatbot/ask'); ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ message: msg })
            })
            .then(r => r.json())
            .then(data => {
                appendMsg(data.reply || 'Xin lỗi, tôi chưa hiểu ý bạn.', 'bot');
            })
            .catch(() => {
                appendMsg('Có lỗi xảy ra, vui lòng thử lại sau.', 'bot');
            });
        };
    </script>
    <!-- Chatbot Widget End -->
</body>

</html>
