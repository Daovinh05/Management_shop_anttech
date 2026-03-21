<?php
class Thuonghieu extends controller
{
    private $th;

    function __construct()
    {
        $this->th = $this->model("ThuongHieu_m");
    }

    function Get_data()
    {
        // Hàm mặc định - hiển thị danh sách thương hiệu
        $this->danhsach();
    }

    function danhsach()
    {
        $result = $this->th->ThuongHieu_getAll();

        $this->view('Master', [
            'page' => 'Danhsachthuonghieu_v',
            'dulieu' => $result
        ]);
    }

    function themmoi()
    {
        $result = $this->th->ThuongHieu_getAll();

        $this->view('Master', [
            'page' => 'Thuonghieu_v',
            'ma_thuong_hieu' => '',
            'ten_thuong_hieu' => '',
            'dulieu' => $result
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_thuong_hieu = $_POST['txtMathuonghieu'];
            $ten_thuong_hieu = $_POST['txtTenthuonghieu'];

            if ($ma_thuong_hieu == '') {
                echo "<script>alert('Mã thương hiệu không được rỗng!')</script>";
                $this->themmoi();
            } else if ($ten_thuong_hieu == '') {
                echo "<script>alert('Tên thương hiệu không được rỗng!')</script>";
                $this->themmoi();
            } else {
                $kq1 = $this->th->checktrungMaTH($ma_thuong_hieu);
                if ($kq1) {
                    echo "<script>alert('Mã thương hiệu đã tồn tại! Vui lòng nhập mã khác.')</script>";
                    $this->view('Master', [
                        'page' => 'Thuonghieu_v',
                        'ma_thuong_hieu' => $ma_thuong_hieu,
                        'ten_thuong_hieu' => $ten_thuong_hieu
                    ]);
                } else {
                    $kq = $this->th->thuonghieu_ins($ma_thuong_hieu, $ten_thuong_hieu);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!')</script>";
                        $this->danhsach();
                    } else {
                        $error = mysqli_error($this->th->con);
                        echo "<script>alert('Thêm mới thất bại! Lỗi: " . addslashes($error) . "')</script>";
                        $this->view('Master', [
                            'page' => 'Thuonghieu_v',
                            'ma_thuong_hieu' => $ma_thuong_hieu,
                            'ten_thuong_hieu' => $ten_thuong_hieu
                        ]);
                    }
                }
            }
        }
    }

    function Timkiem()
    {
        $ma_thuong_hieu = $_POST['txtMathuonghieu'] ?? '';
        $ten_thuong_hieu = $_POST['txtTenthuonghieu'] ?? '';

        $result = $this->th->ThuongHieu_find($ma_thuong_hieu, $ten_thuong_hieu);

        // ====== XUẤT EXCEL ======
        if (isset($_POST['btnXuatexcel'])) {

            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachThuonghieu');

            // Header tương ứng với ảnh CSDL
            $sheet->setCellValue('A1', 'Mã Thuơng Hiệu');
            $sheet->setCellValue('B1', 'Tên Thương Hiệu');
            $sheet->setCellValue('C1', 'Ngày Tạo');


            $rowCount = 2; // Starting from row 2 since row 1 is headers
            mysqli_data_seek($result, 0); // Đặt lại con trỏ kết quả về đầu
            while ($row = mysqli_fetch_assoc($result)) {
                // Mapping field according to database table
                $sheet->setCellValue('A' . $rowCount, $row['ma_thuong_hieu']);
                $sheet->setCellValue('B' . $rowCount, $row['ten_thuong_hieu']);
                $sheet->setCellValue('C' . $rowCount, $row['ngay_tao']);
                $rowCount++;
            }

            foreach (range('A', 'C') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="DanhSachThuongHieu.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }
        // DISPLAY VIEW
        $this->view('Master', [
            'page' => 'Danhsachthuonghieu_v',
            'ma_thuong_hieu' => $ma_thuong_hieu,
            'ten_thuong_hieu' => $ten_thuong_hieu,
            'dulieu' => $result
        ]);
    }

    function sua($ma_thuong_hieu)
    {
        $result = $this->th->ThuongHieu_getById($ma_thuong_hieu);
        $row = mysqli_fetch_array($result);

        $this->view('Master', [
            'page' => 'Thuonghieu_sua',
            'ma_thuong_hieu' => $row['ma_thuong_hieu'],
            'ten_thuong_hieu' => $row['ten_thuong_hieu']
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_thuong_hieu = $_POST['txtMathuonghieu'];
            $ten_thuong_hieu = $_POST['txtTenthuonghieu'];

            $kq = $this->th->ThuongHieu_update($ma_thuong_hieu, $ten_thuong_hieu);
            if ($kq)
                echo "<script>alert('Cập nhật thành công!')</script>";
            else {
                $error = mysqli_error($this->th->con);
                echo "<script>alert('Cập nhật thất bại! Lỗi: " . addslashes($error) . "')</script>";
            }

            $this->Get_data();
        }
    }

    function xoa($ma_thuong_hieu)
    {
        $kq = $this->th->ThuongHieu_delete($ma_thuong_hieu);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Thuonghieu/danhsach') . "';</script>";
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Thuonghieu/danhsach') . "';</script>";
    }

    function import_form()
    {
        $this->view('Master', [
            'page' => 'Thuonghieu_up_v'
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

            $ma_thuong_hieu  = trim($sheetData[$i]['A']);
            $ten_thuong_hieu = trim($sheetData[$i]['B']);

            if ($ma_thuong_hieu == '') continue;

            // ✅ CHECK TRÙNG MÃ THƯƠNG HIỆU
            if ($this->th->checktrungMaTH($ma_thuong_hieu)) {
                echo "<script>
                alert('Mã thương hiệu $ma_thuong_hieu đã tồn tại! Vui lòng kiểm tra lại file.');
                window.location.href='" . $this->url('Thuonghieu/import_form') . "';
            </script>";
                return;
            }

            // Insert
            if (!$this->th->thuonghieu_ins($ma_thuong_hieu, $ten_thuong_hieu)) {
                $error = mysqli_error($this->th->con);
                die("Lỗi khi thêm thương hiệu: " . $error);
            }
        }

        echo "<script>alert('Upload thương hiệu thành công!')</script>";
        $this->view('Master', ['page' => 'Thuonghieu_up_v']);
    }
}
