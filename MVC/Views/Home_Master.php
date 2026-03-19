<?php
include_once __DIR__ . '/../../Public/Classes/UrlHelper.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - TechZone</title>
    <base href="<?php echo UrlHelper::baseUrl(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Include any common CSS files here -->
    <style>
    /* Common styles can go here */
    </style>
</head>

<body>
    <!-- Load the specific page content -->
    <?php
    if (isset($data['page'])) {
        $pagePath = __DIR__ . '/Pages/' . $data['page'] . '.php';
        if (file_exists($pagePath)) {
            include_once $pagePath;
        } else {
            echo "<div class='alert alert-danger'>Trang không tồn tại!</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Không có nội dung để hiển thị!</div>";
    }
    ?>

    <script>
    // Common JavaScript can go here
    </script>
</body>

</html>