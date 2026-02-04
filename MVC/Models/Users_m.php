<?php
class Users_m extends connectDB
{

    function users_ins($ma_user, $ten_user, $full_name, $password, $email, $phan_quyen, $so_dien_thoai)
    {
        $sql = "INSERT INTO users (ma_user, ten_user, full_name, password, email, phan_quyen, so_dien_thoai) VALUES ('$ma_user', '$ten_user', '$full_name', '$password', '$email', '$phan_quyen','$so_dien_thoai')";
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


    function Users_update($ma_user, $ten_user, $full_name, $password, $email, $phan_quyen, $so_dien_thoai)
    {
        $sql = "UPDATE users SET ten_user = '$ten_user', full_name = '$full_name', password = '$password', email = '$email', phan_quyen = '$phan_quyen', so_dien_thoai = '$so_dien_thoai' WHERE ma_user = '$ma_user'";
        return mysqli_query($this->con, $sql);
    }


    function Users_delete($ma_user)
    {
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
    function createUser($username, $email,  $full_name, $password, $role, $so_dien_thoai)
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

        $sql = "INSERT INTO users (ma_user, ten_user, email, full_name, password, phan_quyen, so_dien_thoai) VALUES ('$ma_user', '$username', '$email', '$full_name', '$password', '$role', '$so_dien_thoai')";
        return mysqli_query($this->con, $sql);
    }
}
