<?php
class Danhmuc extends controller
{
    private $dm;

    function __construct()
    {
        $this->dm = $this->model("DanhMuc_m");
    }
    function Get_data()
    {
        $this->danhsach();
    }

    function danhsach()
    {
        $result = $this->dm->Danhmuc_find('', '');

        $this->view('Master', [
            'page' => 'Danhsachdanhmuc_v',
            'ma_danh_muc' => '',
            'ten_danh_muc' => '',
            'dulieu' => $result
        ]);
    }


    function themmoi()
    {
        $this->view('Master', [
            'page' => 'Danhmuc_v',
            'ma_danh_muc' => '',
            'ten_danh_muc' => ''
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_danh_muc = $_POST['txtMadanhmuc'];
            $ten_danh_muc = $_POST['txtTendanhmuc'];

            // Kiểm tra dữ liệu rỗng
            if ($ma_danh_muc == '') {
                echo "<script>alert('Mã danh mục không được rỗng!')</script>";
                $this->themmoi();
            } else {
                // Kiểm tra trùng mã danh mục
                $kq1 = $this->dm->checktrungMaDM($ma_danh_muc);
                if ($kq1) {
                    echo "<script>alert('Mã danh mục đã tồn tại! Vui lòng nhập mã khác.')</script>";
                    $this->view('Master', [
                        'page' => 'Danhmuc_v',
                        'ma_danh_muc' => $ma_danh_muc,
                        'ten_danh_muc' => $ten_danh_muc
                    ]);
                } else {
                    $kq = $this->dm->danhmuc_ins($ma_danh_muc, $ten_danh_muc);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!');</script>";
                        $this->danhsach(); // Quay về danh sách sau khi thêm thành công
                    } else {
                        // Show detailed error for debugging
                        $error = mysqli_error($this->dm->con);
                        echo "<script>alert('Thêm mới thất bại! Lỗi: " . addslashes($error) . "');</script>";
                        $this->themmoi();
                    }
                }
            }
        }
    }

    function Timkiem()
    {
        // Lấy các tham số tìm kiếm từ biểu mẫu
        $ma_danh_muc = $_POST['txtMadanhmuc'] ?? '';
        $ten_danh_muc = $_POST['txtTendanhmuc'] ?? '';

        // 👉 LẤY DỮ LIỆU THEO MÃ DANH MỤC + TÊN DANH MỤC
        $result = $this->dm->Danhmuc_find($ma_danh_muc, $ten_danh_muc);
        // ====== XUẤT EXCEL ======
        if (isset($_POST['btnXuatexcel'])) {

            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachDanhmuc');

            // Header tương ứng với ảnh CSDL
            $sheet->setCellValue('A1', 'Mã Danh Mục');
            $sheet->setCellValue('B1', 'Tên Danh Mục');
            $sheet->setCellValue('C1', 'Ngày Tạo');


            $rowCount = 2; // Starting from row 2 since row 1 is headers
            mysqli_data_seek($result, 0); // Đặt lại con trỏ kết quả về đầu
            while ($row = mysqli_fetch_assoc($result)) {
                // Mapping field according to database table
                $sheet->setCellValue('A' . $rowCount, $row['ma_danh_muc']);
                $sheet->setCellValue('B' . $rowCount, $row['ten_danh_muc']);
                $sheet->setCellValue('C' . $rowCount, $row['ngay_tao']);
                $rowCount++;
            }

            foreach (range('A', 'C') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="DanhSachDanhmuc.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }

        // ====== HIỂN THỊ GIAO DIỆN ======
        $this->view('Master', [
            'page' => 'Danhsachdanhmuc_v',
            'ma_danh_muc' => $ma_danh_muc, // Consistent with view variable name
            'ten_danh_muc' => $ten_danh_muc, // Consistent with view variable name
            'dulieu' => $result
        ]);
    }



    function sua($ma_danh_muc)
    {
        $result = $this->dm->Danhmuc_find($ma_danh_muc, '');
        $row = mysqli_fetch_array($result);

        $this->view('Master', [
            'page' => 'Danhmuc_sua',
            'ma_danh_muc' => $row['ma_danh_muc'],
            'ten_danh_muc' => $row['ten_danh_muc'],
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_danh_muc = $_POST['txtMadanhmuc'];
            $ten_danh_muc = $_POST['txtTendanhmuc'];

            $kq = $this->dm->DanhMuc_update($ma_danh_muc, $ten_danh_muc);
            if ($kq)
                echo "<script>alert('Cập nhật thành công!'); window.location='" . $this->url('Danhmuc/danhsach') . "';</script>";
            else {
                $error = mysqli_error($this->dm->con);
                echo "<script>alert('Cập nhật thất bại! Lỗi: " . addslashes($error) . "');</script>";
            }

            // Nếu cập nhật thất bại, gọi lại view sửa để người dùng thử lại
            if (!$kq) {
                $this->sua($ma_danh_muc);
            }
        }
    }

    function xoa($ma_danh_muc)
    {
        $kq = $this->dm->Danhmuc_delete($ma_danh_muc);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Danhmuc/danhsach') . "';</script>"; // Chuyển về trang danh sách
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Danhmuc/danhsach') . "';</script>"; // Quay lại trang danh sách
    }



    // Hiển thị form nhập Excel
    function import_form()
    {
        $this->view('Master', [
            'page' => 'Danhmuc_up_v'
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

            $ma_danh_muc  = trim($sheetData[$i]['A']);
            $ten_danh_muc = trim($sheetData[$i]['B']);

            if ($ma_danh_muc == '') continue;

            // ✅ CHECK TRÙNG MÃ DANH MỤC
            if ($this->dm->checktrungMaDM($ma_danh_muc)) {
                echo "<script>
                alert('Mã danh mục $ma_danh_muc đã tồn tại! Vui lòng kiểm tra lại file.');
                window.location.href='" . $this->url('Danhmuc/import_form') . "';
            </script>";
                return;
            }

            // Insert
            if (!$this->dm->danhmuc_ins($ma_danh_muc, $ten_danh_muc)) {
                $error = mysqli_error($this->dm->con);
                die("Lỗi khi thêm danh mục: " . $error);
            }
        }

        echo "<script>alert('Upload danh mục thành công!')</script>";
        $this->view('Master', ['page' => 'Danhmuc_up_v']);
    }
}