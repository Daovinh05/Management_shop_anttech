<?php
class Users_m extends connectDB
{

    function users_ins($ma_user, $ten_user, $full_name, $password, $email, $phan_quyen, $so_dien_thoai, $avatar)
    {
        $sql = "INSERT INTO users (ma_user, ten_user, full_name, password, email, phan_quyen, so_dien_thoai, avatar) VALUES ('$ma_user', '$ten_user', '$full_name', '$password', '$email', '$phan_quyen','$so_dien_thoai', '$avatar')";
        return mysqli_query($this->con, $sql);
    }


    function checktrungMaUser($ma_user)
    {
        $sql = "SELECT * FROM users WHERE ma_user = '$ma_user'";
        $result = mysqli_query($this->con, $sql);
        return (mysqli_num_rows($result) > 0);
    }

    // Method to check if phone number already exists
    function checkTrungSoDienThoai($so_dien_thoai)
    {
        $sql = "SELECT * FROM users WHERE so_dien_thoai = '$so_dien_thoai'";
        $result = mysqli_query($this->con, $sql);
        return (mysqli_num_rows($result) > 0);
    }
    public function checktrungEmail($email, $ma_user)
    {
        $sql = "SELECT * FROM users
            WHERE email = '$email'
            AND ma_user != '$ma_user'";
        return mysqli_query($this->con, $sql);
    }

    function Users_find($ma_user, $ten_user)
    {
        $sql = "SELECT * FROM users WHERE ma_user LIKE '%$ma_user%' AND ten_user LIKE '%$ten_user%' ORDER BY CAST(SUBSTRING(ma_user, 2) AS UNSIGNED) DESC";
        return mysqli_query($this->con, $sql);
    }


    function Users_update($ma_user, $ten_user, $full_name, $password, $email, $phan_quyen, $so_dien_thoai, $avatar)
    {
        if ($avatar !== null) {
            $sql = "UPDATE users SET ten_user = '$ten_user', full_name = '$full_name', password = '$password', email = '$email', phan_quyen = '$phan_quyen', so_dien_thoai = '$so_dien_thoai', avatar = '$avatar' WHERE ma_user = '$ma_user'";
        } else {
            $sql = "UPDATE users SET ten_user = '$ten_user', full_name = '$full_name', password = '$password', email = '$email', phan_quyen = '$phan_quyen', so_dien_thoai = '$so_dien_thoai' WHERE ma_user = '$ma_user'";
        }
        return mysqli_query($this->con, $sql);
    }


    function Users_delete($ma_user)
    {
        // Xóa avatar liên quan trước khi xóa user
        $getImageSql = "SELECT avatar FROM users WHERE ma_user = '$ma_user'";
        $result = mysqli_query($this->con, $getImageSql);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            $imagePath = __DIR__ . '/../Public/Pictures/users/' . $row['avatar'];
            if (!empty($row['avatar']) && file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $sql = "DELETE FROM users WHERE ma_user = '$ma_user'";
        return mysqli_query($this->con, $sql);
    }


    function Users_getAll()
    {
        $sql = "SELECT * FROM users ORDER BY CAST(SUBSTRING(ma_user, 2) AS UNSIGNED) DESC";
        return mysqli_query($this->con, $sql);
    }


    function Users_getById($ma_user)
    {
        $sql = "SELECT * FROM users WHERE ma_user = '$ma_user'";
        return mysqli_query($this->con, $sql);
    }

    function validateUser($username, $password)
    {
        // Check login by username OR email
        $sql = "SELECT * FROM users WHERE (ten_user = '$username' OR email = '$username') AND password = '$password'";
        $result = mysqli_query($this->con, $sql);
        return $result;
    }

    function authenticateUser($username, $password)
    {
        $result = $this->validateUser($username, $password);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user['ma_user'];
            $_SESSION['user_name'] = $user['ten_user'];
            $_SESSION['user_role'] = $user['phan_quyen'];

            return $user;
        }

        return false;
    }

    // lấy tên để check tên đăng nhập và email có tồn tại không
    function getUserByUsername($username)
    {
        $sql = "SELECT * FROM users WHERE ten_user = '$username' OR email = '$username'";
        $result = mysqli_query($this->con, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        return false;
    }

    // Hàm tạo user mới với mã user tự động tăng U01, U02, ...U10 nhé dùng cho đăng ký
    function createUser($username, $email,  $full_name, $password, $role, $so_dien_thoai, $avatar = '')
    {
        $sql_max = "SELECT ma_user FROM users WHERE ma_user LIKE 'U%' ORDER BY CAST(SUBSTRING(ma_user, 2) AS UNSIGNED) DESC LIMIT 1";
        $result = mysqli_query($this->con, $sql_max);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $last_id = substr($row['ma_user'], 1);
            $next_id = intval($last_id) + 1;
        } else {
            $next_id = 1;
        }
        $ma_user = 'U' . str_pad($next_id, 2, '0', STR_PAD_LEFT);

        $sql = "INSERT INTO users (ma_user, ten_user, email, full_name, password, phan_quyen, so_dien_thoai, avatar) VALUES ('$ma_user', '$username', '$email', '$full_name', '$password', '$role', '$so_dien_thoai', '$avatar')";
        return mysqli_query($this->con, $sql);
    }
    
    // Cập nhật thông tin hồ sơ người dùng (chỉ cập nhật các trường cần thiết)
    function Users_update_profile($ma_user, $full_name, $so_dien_thoai, $email, $dia_chi = null, $avatar = null)
    {
        // Update the users table
        if ($avatar !== null && !empty($avatar)) {
            $sql_users = "UPDATE users SET full_name = '$full_name', so_dien_thoai = '$so_dien_thoai', email = '$email', avatar = '$avatar' WHERE ma_user = '$ma_user'";
        } else {
            $sql_users = "UPDATE users SET full_name = '$full_name', so_dien_thoai = '$so_dien_thoai', email = '$email' WHERE ma_user = '$ma_user'";
        }
        
        $result_users = mysqli_query($this->con, $sql_users);
        
        // If address is provided, update or insert address in dia_chi_giao_hang table
        if ($dia_chi !== null && !empty($dia_chi)) {
            // Check if user already has a default address
            $check_sql = "SELECT ma_dia_chi FROM dia_chi_giao_hang WHERE ma_user = '$ma_user' AND mac_dinh = 1";
            $check_result = mysqli_query($this->con, $check_sql);
            
            if (mysqli_num_rows($check_result) > 0) {
                // Update existing default address
                $update_address_sql = "UPDATE dia_chi_giao_hang SET dia_chi = '$dia_chi' WHERE ma_user = '$ma_user' AND mac_dinh = 1";
                $result_address = mysqli_query($this->con, $update_address_sql);
            } else {
                // Insert new address as default (mac_dinh = 1)
                // First, check if any address exists for this user
                $any_address_sql = "SELECT ma_dia_chi FROM dia_chi_giao_hang WHERE ma_user = '$ma_user' LIMIT 1";
                $any_result = mysqli_query($this->con, $any_address_sql);
                
                if (mysqli_num_rows($any_result) > 0) {
                    // If user has addresses but none is default, update the first one to be default
                    $row = mysqli_fetch_assoc($any_result);
                    $existing_ma_dia_chi = $row['ma_dia_chi'];
                    $update_address_sql = "UPDATE dia_chi_giao_hang SET dia_chi = '$dia_chi' WHERE ma_dia_chi = '$existing_ma_dia_chi'";
                    $result_address = mysqli_query($this->con, $update_address_sql);
                } else {
                    // Generate new address ID
                    $get_new_id_sql = "SELECT CONCAT('DC', LPAD(COALESCE(MAX(CAST(SUBSTRING(ma_dia_chi, 3) AS UNSIGNED)), 0) + 1, 2, '0')) as new_id FROM dia_chi_giao_hang";
                    $id_result = mysqli_query($this->con, $get_new_id_sql);
                    $id_row = mysqli_fetch_assoc($id_result);
                    $new_ma_dia_chi = $id_row['new_id'];
                    
                    // If no previous records exist, start with DC01
                    if (!$new_ma_dia_chi) {
                        $new_ma_dia_chi = 'DC01';
                    }
                    
                    $insert_address_sql = "INSERT INTO dia_chi_giao_hang (ma_dia_chi, ma_user, ho_ten, so_dien_thoai, dia_chi, mac_dinh) VALUES ('$new_ma_dia_chi', '$ma_user', '$full_name', '$so_dien_thoai', '$dia_chi', 1)";
                    $result_address = mysqli_query($this->con, $insert_address_sql);
                }
            }
        } else {
            $result_address = true; // No address to update, consider successful
        }
        
        // Return true only if both operations succeeded
        return $result_users && $result_address;
    }

    /**
     * Cập nhật thông tin user (dùng cho API)
     */
    function updateUser($ma_user, $data)
    {
        $fields = [];
        
        if (isset($data['email'])) {
            $fields[] = "email = '{$data['email']}'";
        }
        if (isset($data['so_dien_thoai'])) {
            $fields[] = "so_dien_thoai = '{$data['so_dien_thoai']}'";
        }
        if (isset($data['full_name'])) {
            $fields[] = "full_name = '{$data['full_name']}'";
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE ma_user = '$ma_user'";
        return mysqli_query($this->con, $sql);
    }

    /**
     * Đổi mật khẩu
     */
    function changePassword($ma_user, $newPassword)
    {
        $sql = "UPDATE users SET password = '$newPassword' WHERE ma_user = '$ma_user'";
        return mysqli_query($this->con, $sql);
    }
}
