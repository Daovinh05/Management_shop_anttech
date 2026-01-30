<?php
class Khuyenmai extends controller
{
    private $km;

    function __construct()
    {
        $this->km = $this->model("KhuyenMai_m");
    }

    function Get_data()
    {
        // Hàm mặc định - hiển thị danh sách khuyến mãi
        $this->danhsach();
    }

    function danhsach()
    {
        $result = $this->km->KhuyenMai_getAll();

        $this->view('Master', [
            'page' => 'Danhsachkhuyenmai_v',
            'dulieu' => $result
        ]);
    }

    function themmoi()
    {
        $result = $this->km->KhuyenMai_getAll();

        $this->view('Master', [
            'page' => 'Khuyenmai_v',
            'ma_khuyen_mai' => '',
            'ten_khuyen_mai' => '',
            'tien_khuyen_mai' => '',
            'ngay_bat_dau' => '',
            'ngay_ket_thuc' => '',
            'dulieu' => $result
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_khuyen_mai = $_POST['txtMakhuyenmai'];
            $ten_khuyen_mai = $_POST['txtTenkhuyenmai'];
            $tien_khuyen_mai = $_POST['txtTienkhuyenmai'];
            $ngay_bat_dau = $_POST['txtNgaybatdau'];
            $ngay_ket_thuc = $_POST['txtNgayketthuc'];

            // Calculate status based on current date and end date
            $current_date = date('Y-m-d');
            $trang_thai_khuyen_mai = (strtotime($ngay_ket_thuc) < strtotime($current_date)) ? 'het' : 'con';

            if ($ma_khuyen_mai == '') {
                echo "<script>alert('Mã khuyến mãi không được rỗng!')</script>";
                $this->themmoi();
            } else if ($ten_khuyen_mai == '') {
                echo "<script>alert('Tên khuyến mãi không được rỗng!')</script>";
                $this->themmoi();
            } else {
                $kq1 = $this->km->checktrungMaKM($ma_khuyen_mai);
                if ($kq1) {
                    echo "<script>alert('Mã khuyến mãi đã tồn tại! Vui lòng nhập mã khác.')</script>";
                    $this->view('Master', [
                        'page' => 'Khuyenmai_v',
                        'ma_khuyen_mai' => $ma_khuyen_mai,
                        'ten_khuyen_mai' => $ten_khuyen_mai,
                        'tien_khuyen_mai' => $tien_khuyen_mai,
                        'ngay_bat_dau' => $ngay_bat_dau,
                        'ngay_ket_thuc' => $ngay_ket_thuc,
                    ]);
                } else {
                    $kq = $this->km->khuyenmai_ins($ma_khuyen_mai, $ten_khuyen_mai, $tien_khuyen_mai, $ngay_bat_dau, $ngay_ket_thuc, $trang_thai_khuyen_mai);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!')</script>";
                        $this->danhsach();
                    } else {
                        echo "<script>alert('Thêm mới thất bại!')</script>";
                        $this->view('Master', [
                            'page' => 'Khuyenmai_v',
                            'ma_khuyen_mai' => $ma_khuyen_mai,
                            'ten_khuyen_mai' => $ten_khuyen_mai,
                            'tien_khuyen_mai' => $tien_khuyen_mai,
                            'ngay_bat_dau' => $ngay_bat_dau,
                            'ngay_ket_thuc' => $ngay_ket_thuc,
                        ]);
                    }
                }
            }
        }
    }

    function Timkiem()
    {
        // Lấy các tham số tìm kiếm từ biểu mẫu
        $ma_khuyen_mai = $_POST['txtMakhuyenmai'] ?? '';
        $ten_khuyen_mai = $_POST['txtTenkhuyenmai'] ?? '';
        $ngay_bat_dau = $_POST['txtNgaybatdau'] ?? '';
        $ngay_ket_thuc = $_POST['txtNgayketthuc'] ?? '';

        // 👉 LẤY DỮ LIỆU THEO MÃ KHUYẾN MÃI + TÊN KHUYẾN MÃI
        $result = $this->km->KhuyenMai_find($ma_khuyen_mai, $ten_khuyen_mai);
        // ====== XUẤT EXCEL ======
        if (isset($_POST['btnXuatexcel'])) {

            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachKhuyenMai');

            // Header tương ứng với ảnh CSDL
            $sheet->setCellValue('A1', 'Mã Khuyến Mãi');
            $sheet->setCellValue('B1', 'Tên Khuyến Mãi');
            $sheet->setCellValue('C1', 'Tiền Khuyến Mãi');
            $sheet->setCellValue('D1', 'Ngày Bắt Đầu');
            $sheet->setCellValue('E1', 'Ngày Kết Thúc');
            $sheet->setCellValue('F1', 'Trạng Thái');


            $rowCount = 2; // Starting from row 2 since row 1 is headers
            mysqli_data_seek($result, 0); // Đặt lại con trỏ kết quả về đầu
            while ($row = mysqli_fetch_assoc($result)) {
                // Mapping field according to database table
                $sheet->setCellValue('A' . $rowCount, $row['ma_khuyen_mai']);
                $sheet->setCellValue('B' . $rowCount, $row['ten_khuyen_mai']);
                $sheet->setCellValue('C' . $rowCount, $row['tien_khuyen_mai']);
                $sheet->setCellValue('D' . $rowCount, $row['ngay_bat_dau']);
                $sheet->setCellValue('E' . $rowCount, $row['ngay_ket_thuc']);
                $sheet->setCellValue('F' . $rowCount, $row['trang_thai_khuyen_mai']);
                $rowCount++;
            }

            foreach (range('A', 'F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="DanhSachKhuyenMai.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        // ====== HIỂN THỊ GIAO DIỆN ======
        $this->view('Master', [
            'page' => 'Danhsachkhuyenmai_v',
            'ma_khuyen_mai' => $ma_khuyen_mai, // Consistent with view variable name
            'ten_khuyen_mai' => $ten_khuyen_mai, // Consistent with view variable name
            'ngay_bat_dau' => $ngay_bat_dau,
            'ngay_ket_thuc' => $ngay_ket_thuc,
            'dulieu' => $result
        ]);
    }

    function sua($ma_khuyen_mai)
    {
        $result = $this->km->KhuyenMai_getById($ma_khuyen_mai);
        $row = mysqli_fetch_array($result);

        $this->view('Master', [
            'page' => 'Khuyenmai_sua',
            'ma_khuyen_mai' => $row['ma_khuyen_mai'],
            'ten_khuyen_mai' => $row['ten_khuyen_mai'],
            'tien_khuyen_mai' => $row['tien_khuyen_mai'],
            'ngay_bat_dau' => $row['ngay_bat_dau'],
            'ngay_ket_thuc' => $row['ngay_ket_thuc'],
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_khuyen_mai = $_POST['txtMakhuyenmai']; // Fixed field name
            $ten_khuyen_mai = $_POST['txtTenkhuyenmai'];
            $tien_khuyen_mai = $_POST['txtTienkhuyenmai'];
            $ngay_bat_dau = $_POST['txtNgaybatdau'];
            $ngay_ket_thuc = $_POST['txtNgayketthuc']; // Fixed field name

            // Calculate status based on current date and end date
            $current_date = date('Y-m-d');
            $trang_thai_khuyen_mai = (strtotime($ngay_ket_thuc) < strtotime($current_date)) ? 'het' : 'con';

            $kq = $this->km->KhuyenMai_update($ma_khuyen_mai, $ten_khuyen_mai, $tien_khuyen_mai, $ngay_bat_dau, $ngay_ket_thuc, $trang_thai_khuyen_mai);
            if ($kq)
                echo "<script>alert('Cập nhật thành công!')</script>";
            else
                echo "<script>alert('Cập nhật thất bại!')</script>";

            $this->Get_data();
        }
    }

    function xoa($ma_khuyen_mai)
    {
        $kq = $this->km->KhuyenMai_delete($ma_khuyen_mai);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Khuyenmai/danhsach') . "';</script>";
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Khuyenmai/danhsach') . "';</script>";
    }
}