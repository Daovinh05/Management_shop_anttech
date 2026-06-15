<?php
class Sanpham extends controller
{
    private $sp;
    private $dm;
    private $th;
    private $ncc;

    function __construct()
    {
        $this->sp = $this->model("SanPham_m");
        $this->dm = $this->model("DanhMuc_m");
        $this->th = $this->model("ThuongHieu_m");
        $this->ncc = $this->model("NhaCungCap_m");
    }

    function Get_data()
    {
        // Hàm mặc định - hiển thị danh sách sản phẩm
        $this->danhsach();
    }

    function danhsach()
    {
        $this->view('Master', [
            'page' => 'Danhsachsanpham_v'
        ]);
    }

    function themmoi()
    {
        // Lấy danh sách danh mục, thương hiệu, nhà cung cấp cho dropdown
        $dsdm = $this->dm->DanhMuc_getAll();
        $dsth = $this->th->ThuongHieu_getAll();
        $dsncc = $this->ncc->NhaCungCap_getAll();

        $this->view('Master', [
            'page' => 'Sanpham_v',
            'ma_san_pham' => '',
            'ten_san_pham' => '',
            'ma_danh_muc' => '',
            'ma_thuong_hieu' => '',
            'ma_nha_cung_cap' => '',
            'dsdm' => $dsdm,
            'dsth' => $dsth,
            'dsncc' => $dsncc
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_san_pham = $_POST['txtMaSanPham'];
            $ten_san_pham = $_POST['txtTenSanPham'];
            $ma_danh_muc = isset($_POST['ddlDanhmuc']) ? $_POST['ddlDanhmuc'] : '';
            $ma_thuong_hieu = isset($_POST['ddlThuonghieu']) ? $_POST['ddlThuonghieu'] : '';
            $ma_nha_cung_cap = isset($_POST['ddlNhacungcap']) ? $_POST['ddlNhacungcap'] : '';

            $dsdm = $this->dm->DanhMuc_getAll();
            $dsth = $this->th->ThuongHieu_getAll();
            $dsncc = $this->ncc->NhaCungCap_getAll();

            if ($ma_san_pham == '') {
                echo "<script>alert('Mã sản phẩm không được rỗng!')</script>";
                $this->themmoi();
            } else if ($ten_san_pham == '') {
                echo "<script>alert('Tên sản phẩm không được rỗng!')</script>";
                $this->themmoi();
            } else {
                $kq1 = $this->sp->checktrungMaSP($ma_san_pham);
                if ($kq1) {
                    echo "<script>alert('Mã sản phẩm đã tồn tại! Vui lòng nhập mã khác.')</script>";
                    $this->view('Master', [
                        'page' => 'Sanpham_v',
                        'ma_san_pham' => $ma_san_pham,
                        'ten_san_pham' => $ten_san_pham,
                        'ma_danh_muc' => $ma_danh_muc,
                        'ma_thuong_hieu' => $ma_thuong_hieu,
                        'ma_nha_cung_cap' => $ma_nha_cung_cap,
                        'dsdm' => $dsdm,
                        'dsth' => $dsth,
                        'dsncc' => $dsncc
                    ]);
                } else {
                    $kq = $this->sp->sanpham_ins($ma_san_pham, $ten_san_pham, $ma_danh_muc, $ma_thuong_hieu, $ma_nha_cung_cap);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!')</script>";
                        $this->danhsach();
                    } else {
                        echo "<script>alert('Thêm mới thất bại!')</script>";
                        $this->view('Master', [
                            'page' => 'Sanpham_v',
                            'ma_san_pham' => $ma_san_pham,
                            'ten_san_pham' => $ten_san_pham,
                            'ma_danh_muc' => $ma_danh_muc,
                            'ma_thuong_hieu' => $ma_thuong_hieu,
                            'ma_nha_cung_cap' => $ma_nha_cung_cap,
                            'dsdm' => $dsdm,
                            'dsth' => $dsth,
                            'dsncc' => $dsncc
                        ]);
                    }
                }
            }
        }
    }

    function Timkiem()
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(410);
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint đã ngừng hỗ trợ. Vui lòng sử dụng GET /Api/Products'
        ]);
        return;
    }

    function sua($ma_san_pham)
    {
        $this->view('Master', [
            'page' => 'Sanpham_sua'
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_san_pham = $_POST['txtMasanpham'];
            $ten_san_pham = $_POST['txtTensanpham'];
            $ma_danh_muc = $_POST['ddlDanhmuc'];
            $ma_thuong_hieu = $_POST['ddlThuonghieu'];
            $ma_nha_cung_cap = $_POST['ddlNhacungcap'];

            $kq = $this->sp->SanPham_update($ma_san_pham, $ten_san_pham, $ma_danh_muc, $ma_thuong_hieu, $ma_nha_cung_cap);
            if ($kq)
                echo "<script>alert('Cập nhật thành công!')</script>";
            else
                echo "<script>alert('Cập nhật thất bại!')</script>";

            $this->Get_data();
        }
    }


    function xoa($ma_san_pham)
    {
        if ($this->sp->SanPham_hasOrderDetails($ma_san_pham)) {
            echo "<script>alert(''Không thể xóa vì sản phẩm ' . $ma_san_pham . ' đã có trong chi tiết đơn hàng.'); window.location='" . $this->url('Sanpham/danhsach') . "';</script>";
            return;
        }

        $kq = $this->sp->SanPham_delete($ma_san_pham);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Sanpham/danhsach') . "';</script>";
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Sanpham/danhsach') . "';</script>";
    }

    // Hiển thị form nhập Excel
    function import_form()
    {
        $this->view('Master', [
            'page' => 'Sanpham_up_v'
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

            $ma_san_pham = trim($sheetData[$i]['A']);
            $ten_san_pham = trim($sheetData[$i]['B']);
            $ma_danh_muc = trim($sheetData[$i]['C']);
            $ma_thuong_hieu = trim($sheetData[$i]['D']);
            $ma_nha_cung_cap = trim($sheetData[$i]['E']);

            if ($ma_san_pham == '') continue;

            // ✅ CHECK TRÙNG MÃ SẢN PHẨM
            if ($this->sp->checktrungMaSP($ma_san_pham)) {
                echo "<script>
                alert('Mã sản phẩm $ma_san_pham đã tồn tại! Vui lòng kiểm tra lại file.');
                window.location.href='" . $this->url('Sanpham/import_form') . "';
            </script>";
                return;
            }

            // Insert
            if (!$this->sp->sanpham_ins($ma_san_pham, $ten_san_pham, $ma_danh_muc, $ma_thuong_hieu, $ma_nha_cung_cap)) {
                $error = mysqli_error($this->sp->con);
                die("Lỗi khi thêm sản phẩm: " . $error);
            }
        }

        echo "<script>alert('Upload sản phẩm thành công!')</script>";
        $this->view('Master', ['page' => 'Sanpham_up_v']);
    }
}
