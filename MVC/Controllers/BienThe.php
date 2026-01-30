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
                        'mausac' => $mau_sac,
                        'ram' => $ram,
                        'dungluong' => $dung_luong,
                        'gia' => $gia,
                        'soluongkho' => $so_luong_kho,
                        'dssp' => $dssp
                    ]);
                } else {
                    $kq = $this->bt->bien_the_ins($ma_bien_the, $ma_san_pham, $ten_bien_the, $mau_sac, $ram, $dung_luong, $gia, $so_luong_kho);
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

            $kq = $this->bt->BienThe_update($ma_bien_the, $ma_san_pham, $ten_bien_the, $mau_sac, $ram, $dung_luong, $gia, $so_luong_kho);
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

            // Insert
            if (!$this->bt->bien_the_ins($ma_bien_the, $ma_san_pham, $ten_bien_the, $mau_sac, $ram, $dung_luong, $gia, $so_luong_kho)) {
                $error = mysqli_error($this->bt->con);
                die("Lỗi khi thêm biến thể: " . $error);
            }
        }

        echo "<script>alert('Upload biến thể thành công!')</script>";
        $this->view('Master', ['page' => 'bienthe_up_v']);
    }
}