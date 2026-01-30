<?php
class Nhacungcap extends controller
{
    private $ncc;

    function __construct()
    {
        $this->ncc = $this->model("NhaCungCap_m");
    }

    function Get_data()
    {
        // Hàm mặc định - hiển thị danh sách nhà cung cấp
        $this->danhsach();
    }

    function danhsach()
    {
        $result = $this->ncc->NhaCungCap_getAll();

        $this->view('Master', [
            'page' => 'danhsachnhacungcap_v',
            'dulieu' => $result
        ]);
    }

    function themmoi()
    {
        $result = $this->ncc->NhaCungCap_getAll();

        $this->view('Master', [
            'page' => 'nhacungcap_v',
            'manhacungcap' => '',
            'tennhacungcap' => '',
            'diachi' => '',
            'sodienthoai' => '',
            'dulieu' => $result
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_nha_cung_cap = $_POST['txtMaNhaCungCap'];
            $ten_nha_cung_cap = $_POST['txtTenNhaCungCap'];
            $dia_chi = $_POST['txtDiaChi'];
            $dien_thoai = $_POST['txtDienThoai'];

            if ($ma_nha_cung_cap == '') {
                echo "<script>alert('Mã nhà cung cấp không được rỗng!')</script>";
                $this->themmoi();
            } else if ($ten_nha_cung_cap == '') {
                echo "<script>alert('Tên nhà cung cấp không được rỗng!')</script>";
                $this->themmoi();
            } else {
                $kq1 = $this->ncc->checktrungMaNCC($ma_nha_cung_cap);
                if ($kq1) {
                    echo "<script>alert('Mã nhà cung cấp đã tồn tại! Vui lòng nhập mã khác.')</script>";
                    $this->view('Master', [
                        'page' => 'nhacungcap_v',
                        'manhacungcap' => $ma_nha_cung_cap,
                        'tennhacungcap' => $ten_nha_cung_cap,
                        'diachi' => $dia_chi,
                        'sodienthoai' => $dien_thoai
                    ]);
                } else {
                    $kq = $this->ncc->nhacungcap_ins($ma_nha_cung_cap, $ten_nha_cung_cap, $dia_chi, $dien_thoai);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!')</script>";
                        $this->danhsach();
                    } else {
                        echo "<script>alert('Thêm mới thất bại!')</script>";
                        $this->view('Master', [
                            'page' => 'nhacungcap_v',
                            'manhacungcap' => $ma_nha_cung_cap,
                            'tennhacungcap' => $ten_nha_cung_cap,
                            'diachi' => $dia_chi,
                            'sodienthoai' => $dien_thoai
                        ]);
                    }
                }
            }
        }
    }

    function Timkiem()
    {
        // Lấy các tham số tìm kiếm từ biểu mẫu
        $ma_nha_cung_cap = $_POST['txtMaNhaCungCap'] ?? '';
        $ten_nha_cung_cap = $_POST['txtTenNhaCungCap'] ?? '';

        // LẤY DỮ LIỆU THEO MÃ NHÀ CUNG CẤP + TÊN NHÀ CUNG CẤP
        $result = $this->ncc->NhaCungCap_find($ma_nha_cung_cap, $ten_nha_cung_cap);

        // DISPLAY VIEW
        $this->view('Master', [
            'page' => 'danhsachnhacungcap_v',
            'manhacungcap' => $ma_nha_cung_cap,
            'tennhacungcap' => $ten_nha_cung_cap,
            'dulieu' => $result
        ]);
    }

    function sua($ma_nha_cung_cap)
    {
        $result = $this->ncc->NhaCungCap_getById($ma_nha_cung_cap);
        $row = mysqli_fetch_array($result);

        $this->view('Master', [
            'page' => 'nhacungcap_sua',
            'manhacungcap' => $row['ma_nha_cung_cap'],
            'tennhacungcap' => $row['ten_nha_cung_cap'],
            'diachi' => $row['dia_chi'],
            'sodienthoai' => $row['dien_thoai']
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_nha_cung_cap = $_POST['txtMaNhaCungCap'];
            $ten_nha_cung_cap = $_POST['txtTenNhaCungCap'];
            $dia_chi = $_POST['txtDiaChi'];
            $dien_thoai = $_POST['txtDienThoai'];

            $kq = $this->ncc->NhaCungCap_update($ma_nha_cung_cap, $ten_nha_cung_cap, $dia_chi, $dien_thoai);
            if ($kq)
                echo "<script>alert('Cập nhật thành công!')</script>";
            else
                echo "<script>alert('Cập nhật thất bại!')</script>";

            $this->Get_data();
        }
    }

    function xoa($ma_nha_cung_cap)
    {
        $kq = $this->ncc->NhaCungCap_delete($ma_nha_cung_cap);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Nhacungcap/danhsach') . "';</script>";
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Nhacungcap/danhsach') . "';</script>";
    }
}