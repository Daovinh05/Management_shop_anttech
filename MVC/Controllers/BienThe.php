<?php
class BienThe extends controller
{
    private $bt;
    private $sp;

    function __construct()
    {
        $this->bt = $this->model("BienThe_m");
        $this->sp = $this->model("SanPham_m");
    }

    function Get_data()
    {
        // Hàm mặc định - hiển thị danh sách biến thể
        $this->danhsach();
    }

    function danhsach()
    {
        $result = $this->bt->BienThe_getAll();

        $this->view('Master', [
            'page' => 'danhsachbienthe_v',
            'dulieu' => $result
        ]);
    }

    function themmoi()
    {
        // Lấy danh sách sản phẩm cho dropdown
        $dssp = $this->sp->SanPham_getAll();
        $result = $this->bt->BienThe_getAll();

        $this->view('Master', [
            'page' => 'bienthe_v',
            'mabienthe' => '',
            'masanpham' => '',
            'tenbienthe' => '',
            'imgbienthe' => '',
            'mausac' => '',
            'ram' => '',
            'dungluong' => '',
            'gia' => '',
            'soluongkho' => '',
            'dssp' => $dssp,
            'dulieu' => $result
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_bien_the = $_POST['txtMaBienThe'];
            $ma_san_pham = $_POST['ddlSanPham'];
            $ten_bien_the = $_POST['txtTenBienThe'];
            $mau_sac = $_POST['txtMauSac'];
            $ram = $_POST['txtRAM'];
            $dung_luong = $_POST['txtDungLuong'];
            $gia = $_POST['txtGia'];
            $so_luong_kho = $_POST['txtSoLuongKho'];

            // Xử lý upload hình ảnh biến thể
            $img_bien_the = '';
            if (isset($_FILES['txtImage']) && $_FILES['txtImage']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $filename = $_FILES['txtImage']['name'];
                $filetmp = $_FILES['txtImage']['tmp_name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (in_array($ext, $allowed)) {
                    // Làm sạch tên tệp gốc
                    $original_name = pathinfo($filename, PATHINFO_FILENAME);
                    $original_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $original_name);
                    $original_name = str_replace('-', '_', $original_name);
                    $new_filename = $original_name . '.' . $ext;

                    // Kiểm tra nếu tên tệp đã tồn tại, thêm hậu tố cho đến khi không trùng
                    $counter = 1;
                    $final_filename = $new_filename;
                    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . BASE_URL . 'Public/Pictures/bien_the/';

                    while (file_exists($upload_dir . $final_filename)) {
                        $final_filename = $original_name . '_' . $counter . '.' . $ext;
                        $counter++;
                    }

                    // Tạo thư mục nếu chưa tồn tại
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }

                    // Sử dụng đường dẫn tuyệt đối đến thư mục Public/Pictures/bien_the
                    $upload_path = $upload_dir . $final_filename;

                    if (move_uploaded_file($filetmp, $upload_path)) {
                        $img_bien_the = $final_filename; // Chỉ lưu tên tệp vào DB
                    } else {
                        echo "<script>alert('Upload hình ảnh biến thể thất bại! Đường dẫn: $upload_path');</script>";
                        $this->themmoi();
                        return;
                    }
                } else {
                    echo "<script>alert('Định dạng hình ảnh không hợp lệ!');</script>";
                    $this->themmoi();
                    return;
                }
            }

            $dssp = $this->sp->SanPham_getAll();

            if ($ma_bien_the == '') {
                echo "<script>alert('Mã biến thể không được rỗng!')</script>";
                $this->themmoi();
            } else if ($ma_san_pham == '') {
                echo "<script>alert('Vui lòng chọn sản phẩm!')</script>";
                $this->themmoi();
            } else {
                $kq1 = $this->bt->checktrungMaBT($ma_bien_the);
                if ($kq1) {
                    echo "<script>alert('Mã biến thể đã tồn tại! Vui lòng nhập mã khác.')</script>";
                    $this->view('Master', [
                        'page' => 'bienthe_v',
                        'mabienthe' => $ma_bien_the,
                        'masanpham' => $ma_san_pham,
                        'tenbienthe' => $ten_bien_the,
                        'imgbienthe' => $img_bien_the,
                        'mausac' => $mau_sac,
                        'ram' => $ram,
                        'dungluong' => $dung_luong,
                        'gia' => $gia,
                        'soluongkho' => $so_luong_kho,
                        'dssp' => $dssp
                    ]);
                } else {
                    $kq = $this->bt->bien_the_ins($ma_bien_the, $ma_san_pham, $ten_bien_the, $img_bien_the, $mau_sac, $ram, $dung_luong, $gia, $so_luong_kho);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!')</script>";
                        $this->danhsach();
                    } else {
                        $error = mysqli_error($this->bt->con);
                        echo "<script>alert('Thêm mới thất bại! Lỗi: " . addslashes($error) . "')</script>";
                        $this->view('Master', [
                            'page' => 'bienthe_v',
                            'mabienthe' => $ma_bien_the,
                            'masanpham' => $ma_san_pham,
                            'tenbienthe' => $ten_bien_the,
                            'imgbienthe' => $img_bien_the,
                            'mausac' => $mau_sac,
                            'ram' => $ram,
                            'dungluong' => $dung_luong,
                            'gia' => $gia,
                            'soluongkho' => $so_luong_kho,
                            'dssp' => $dssp
                        ]);
                    }
                }
            }
        }
    }

    function Timkiem()
    {
        // Lấy các tham số tìm kiếm từ biểu mẫu
        $ma_bien_the = $_POST['txtMaBienThe'] ?? '';
        $ten_bien_the = $_POST['txtTenBienThe'] ?? '';

        // 👉 LẤY DỮ LIỆU THEO MÃ BIẾN THỂ + TÊN BIẾN THỂ
        $result = $this->bt->BienThe_find($ma_bien_the, $ten_bien_the);

        // DISPLAY VIEW
        $this->view('Master', [
            'page' => 'danhsachbienthe_v',
            'mabienthe' => $ma_bien_the,
            'tenbienthe' => $ten_bien_the,
            'dulieu' => $result
        ]);
    }

    function sua($ma_bien_the)
    {
        $result = $this->bt->BienThe_getById($ma_bien_the);
        $row = mysqli_fetch_array($result);

        // Lấy danh sách sản phẩm cho dropdown
        $dssp = $this->sp->SanPham_getAll();

        $this->view('Master', [
            'page' => 'bienthe_sua',
            'mabienthe' => $row['ma_bien_the'],
            'masanpham' => $row['ma_san_pham'],
            'tenbienthe' => $row['ten_bien_the'],
            'imgbienthe' => $row['img_bien_the'],
            'mausac' => $row['mau_sac'],
            'ram' => $row['ram'],
            'dungluong' => $row['dung_luong'],
            'gia' => $row['gia'],
            'soluongkho' => $row['so_luong_kho'],
            'dssp' => $dssp
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_bien_the = $_POST['txtMaBienThe'];
            $ma_san_pham = $_POST['ddlSanPham'];
            $ten_bien_the = $_POST['txtTenBienThe'];
            $mau_sac = $_POST['txtMauSac'];
            $ram = $_POST['txtRAM'];
            $dung_luong = $_POST['txtDungLuong'];
            $gia = $_POST['txtGia'];
            $so_luong_kho = $_POST['txtSoLuongKho'];

            // Lấy hình ảnh hiện tại từ database trước
            $current_record = $this->bt->BienThe_getById($ma_bien_the);
            $current_row = mysqli_fetch_array($current_record);
            $img_bien_the = $current_row['img_bien_the']; // Giữ hình ảnh hiện tại mặc định

            // Xử lý upload hình ảnh mới (nếu có)
            if (isset($_FILES['txtImage']) && $_FILES['txtImage']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $filename = $_FILES['txtImage']['name'];
                $filetmp = $_FILES['txtImage']['tmp_name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (in_array($ext, $allowed)) {
                    // Làm sạch tên tệp gốc
                    $original_name = pathinfo($filename, PATHINFO_FILENAME);
                    $original_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $original_name); // Chỉ giữ các ký tự an toàn
                    $original_name = str_replace('-', '_', $original_name); // Thay thế dấu gạch nối bằng dấu gạch dưới
                    $new_filename = $original_name . '.' . $ext;

                    // Kiểm tra nếu tên tệp đã tồn tại, thêm hậu tố cho đến khi không trùng
                    $counter = 1;
                    $final_filename = $new_filename;
                    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . BASE_URL . 'Public/Pictures/bien_the/';

                    while (file_exists($upload_dir . $final_filename)) {
                        $final_filename = $original_name . '_' . $counter . '.' . $ext;
                        $counter++;
                    }

                    // Nếu tên tệp mới khác với tên tệp gốc, có nghĩa là đã có tệp trùng
                    if ($final_filename !== $new_filename) {
                        // Tạo tên tệp mới với timestamp để đảm bảo duy nhất
                        $final_filename = $original_name . '_' . time() . '.' . $ext;
                    }

                    // Tạo thư mục nếu chưa tồn tại
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }

                    // Sử dụng đường dẫn tuyệt đối đến thư mục Public/Pictures/bien_the
                    $upload_path = $upload_dir . $final_filename;

                    if (move_uploaded_file($filetmp, $upload_path)) {
                        // Xóa hình ảnh cũ nếu tồn tại
                        $old_image_path = $_SERVER['DOCUMENT_ROOT'] . BASE_URL . 'Public/Pictures/bien_the/' . $current_row['img_bien_the'];
                        if (!empty($current_row['img_bien_the']) && file_exists($old_image_path) && strpos($old_image_path, '/Public/Pictures/bien_the/') !== false) {
                            unlink($old_image_path);
                        }

                        $img_bien_the = $final_filename; // Chỉ lưu tên tệp vào DB
                    } else {
                        echo "<script>alert('Upload hình ảnh biến thể thất bại! Đường dẫn: $upload_path');</script>";
                        $this->sua($ma_bien_the);
                        return;
                    }
                } else {
                    echo "<script>alert('Định dạng hình ảnh không hợp lệ!');</script>";
                    $this->sua($ma_bien_the);
                    return;
                }
            }
            // Nếu không có file upload mới, giữ nguyên hình ảnh hiện tại (đã được lấy ở trên)

            $kq = $this->bt->BienThe_update($ma_bien_the, $ma_san_pham, $ten_bien_the, $img_bien_the, $mau_sac, $ram, $dung_luong, $gia, $so_luong_kho);
            if ($kq)
                echo "<script>alert('Cập nhật thành công!')</script>";
            else {
                $error = mysqli_error($this->bt->con);
                echo "<script>alert('Cập nhật thất bại! Lỗi: " . addslashes($error) . "')</script>";
            }

            $this->Get_data();
        }
    }

    function xoa($ma_bien_the)
    {
        $kq = $this->bt->BienThe_delete($ma_bien_the);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('BienThe/danhsach') . "';</script>";
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('BienThe/danhsach') . "';</script>";
    }

    // Hiển thị form nhập Excel
    function import_form()
    {
        $this->view('Master', [
            'page' => 'bienthe_up_v'
        ]);
    }


    function up_l()
    {
        if (!isset($_FILES['txtfile']) || $_FILES['txtfile']['error'] != 0) {
            echo "<script>alert('Upload file lỗi')</script>";
            return;
        }

        $file = $_FILES['txtfile']['tmp_name'];

        $objReader = PHPExcel_IOFactory::createReaderForFile($file);
        $objExcel  = $objReader->load($file);

        $sheet     = $objExcel->getSheet(0);
        $sheetData = $sheet->toArray(null, true, true, true);

        for ($i = 2; $i <= count($sheetData); $i++) {

            $ma_bien_the = trim($sheetData[$i]['A']);
            $ma_san_pham = trim($sheetData[$i]['B']);
            $ten_bien_the = trim($sheetData[$i]['C']);
            $mau_sac = trim($sheetData[$i]['D']);
            $ram = trim($sheetData[$i]['E']);
            $dung_luong = trim($sheetData[$i]['F']);
            $gia = trim($sheetData[$i]['G']);
            $so_luong_kho = trim($sheetData[$i]['H']);

            if ($ma_bien_the == '') continue;

            // ✅ CHECK TRÙNG MÃ BIẾN THỂ
            if ($this->bt->checktrungMaBT($ma_bien_the)) {
                echo "<script>
                alert('Mã biến thể $ma_bien_the đã tồn tại! Vui lòng kiểm tra lại file.');
                window.location.href='" . $this->url('BienThe/import_form') . "';
            </script>";
                return;
            }

            // Insert - Không bao gồm hình ảnh trong import từ Excel
            if (!$this->bt->bien_the_ins($ma_bien_the, $ma_san_pham, $ten_bien_the, '', $mau_sac, $ram, $dung_luong, $gia, $so_luong_kho)) {
                $error = mysqli_error($this->bt->con);
                die("Lỗi khi thêm biến thể: " . $error);
            }
        }

        echo "<script>alert('Upload biến thể thành công!')</script>";
        $this->view('Master', ['page' => 'bienthe_up_v']);
    }
}