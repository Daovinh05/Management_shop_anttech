<?php
class Danhgia extends controller
{
    private $dg;

    function __construct()
    {
        $this->dg = $this->model("DanhGia_m");
    }

    function index()
    {
        $this->danhsach();
    }

    function Get_data()
    {
        $this->danhsach();
    }

    function danhsach()
    {
        $result = $this->dg->DanhGia_getAll();
        $this->view('Master', [
            'page' => 'Danhsachdanhgia_v',
            'ma_danh_gia' => '',
            'ten_danh_gia' => '',
            'dulieu' => $result
        ]);
    }

    function themmoi()
    {
        $this->view('Master', [
            'page' => 'Danhgia_v',
            'ma_danh_gia' => '',
            'ma_user' => '',
            'ma_san_pham' => '',
            'so_sao' => '',
            'noi_dung' => '',
            'phan_hoi' => ''
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_danh_gia = $_POST['txtMadanhgia'];
            $ma_user = $_POST['txtMauser'];
            $ma_san_pham = $_POST['txtMasanpham'];
            $so_sao = $_POST['txtSosao'];
            $noi_dung = $_POST['txtNoidung'];
            $phan_hoi = $_POST['txtPhanhoi'];

            if ($ma_danh_gia == '') {
                echo "<script>alert('Mã đánh giá không được rỗng!')</script>";
            } else {
                $kq1 = $this->dg->checktrungMaDG($ma_danh_gia);

                if ($kq1) {
                    echo "<script>alert('Mã đánh giá đã tồn tại!')</script>";
                    $this->view('Master', [
                        'page' => 'Danhgia_v',
                        'ma_danh_gia' => $ma_danh_gia,
                        'ma_user' => $ma_user,
                        'ma_san_pham' => $ma_san_pham,
                        'so_sao' => $so_sao,
                        'noi_dung' => $noi_dung,
                        'phan_hoi' => $phan_hoi
                    ]);
                } else {
                    $kq = $this->dg->danhgia_ins($ma_danh_gia, $ma_user, $ma_san_pham, $so_sao, $noi_dung, $phan_hoi);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!')</script>";
                        $this->danhsach();
                    } else {
                        echo "<script>alert('Thêm mới thất bại!')</script>";
                        $this->view('Master', [
                            'page' => 'Danhgia_v',
                            'ma_danh_gia' => $ma_danh_gia,
                            'ma_user' => $ma_user,
                            'ma_san_pham' => $ma_san_pham,
                            'so_sao' => $so_sao,
                            'noi_dung' => $noi_dung,
                            'phan_hoi' => $phan_hoi
                        ]);
                    }
                }
            }
        }
    }

    function Timkiem()
    {
        // Lấy các tham số tìm kiếm từ form
        $ma_danh_gia = $_POST['txtMadanhgia'] ?? '';
        $ten_khach_hang = $_POST['txtTenkhachhang'] ?? '';
        $ten_san_pham = $_POST['txtTensanpham'] ?? '';

        // LẤY DỮ LIỆU THEO MÃ ĐÁNH GIÁ + TÊN KHÁCH HÀNG + TÊN SẢN PHẨM
        $result = $this->dg->DanhGia_find($ma_danh_gia, $ten_khach_hang, $ten_san_pham);

        // ====== XUẤT EXCEL ======
        if (isset($_POST['btnXuatexcel'])) {
            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachDanhGia');

            // Header tương ứng với ảnh CSDL
            $sheet->setCellValue('A1', 'Mã Đánh Giá');
            $sheet->setCellValue('B1', 'Tên Khách Hàng');
            $sheet->setCellValue('C1', 'Tên Sản Phẩm');
            $sheet->setCellValue('D1', 'Số Sao');
            $sheet->setCellValue('E1', 'Nội Dung');
            $sheet->setCellValue('F1', 'Phản Hồi');
            $sheet->setCellValue('G1', 'Ngày Tạo');

            $rowCount = 2; // Bắt đầu từ hàng 2 vì hàng 1 là tiêu đề
            mysqli_data_seek($result, 0); // Đặt lại con trỏ kết quả về đầu
            while ($row = mysqli_fetch_assoc($result)) {
                // Ánh xạ trường theo bảng cơ sở dữ liệu
                $sheet->setCellValue('A' . $rowCount, $row['ma_danh_gia']);
                $sheet->setCellValue('B' . $rowCount, $row['full_name']);
                $sheet->setCellValue('C' . $rowCount, $row['ten_san_pham']);
                $sheet->setCellValue('D' . $rowCount, $row['so_sao']);
                $sheet->setCellValue('E' . $rowCount, $row['noi_dung']);
                $sheet->setCellValue('F' . $rowCount, $row['phan_hoi']);
                $sheet->setCellValue('G' . $rowCount, $row['ngay_tao']);
                $rowCount++;
            }

            foreach (range('A', 'G') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="Danhsachdanhgia.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        // ====== DISPLAY VIEW ======
        $this->view('Master', [
            'page' => 'Danhsachdanhgia_v',
            'ma_danh_gia' => $ma_danh_gia,
            'ten_khach_hang' => $ten_khach_hang,
            'ten_san_pham' => $ten_san_pham,
            'dulieu' => $result
        ]);
    }

    function sua($ma_danh_gia)
    {
        $result = $this->dg->DanhGia_getById($ma_danh_gia);
        $row = mysqli_fetch_array($result);
        $this->view('Master', [
            'page' => 'Danhgia_sua',
            'ma_danh_gia' => $row['ma_danh_gia'],
            'full_name' => $row['full_name'],
            'ten_san_pham' => $row['ten_san_pham'],
            'so_sao' => $row['so_sao'],
            'noi_dung' => $row['noi_dung'],
            'phan_hoi' => $row['phan_hoi']
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_danh_gia = $_POST['txtMadanhgia'];
            $so_sao = $_POST['txtSosao'];
            $noi_dung = $_POST['txtNoidung'];
            $phan_hoi = $_POST['txtPhanhoi'];

            $kq = $this->dg->DanhGia_update($ma_danh_gia, $so_sao, $noi_dung, $phan_hoi);
            if ($kq)
                echo "<script>alert('Cập nhật thành công!'); window.location='" . $this->url('Danhgia/danhsach') . "';</script>";
            else
                echo "<script>alert('Cập nhật thất bại!')</script>";
        }
    }

    function xoa($ma_danh_gia)
    {
        $kq = $this->dg->DanhGia_delete($ma_danh_gia);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Danhgia/danhsach') . "';</script>";
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Danhgia/danhsach') . "';</script>";
    }
}
