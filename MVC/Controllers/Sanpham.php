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
        $result = $this->sp->SanPham_getAll();

        $this->view('Master', [
            'page' => 'danhsachsanpham_v',
            'dulieu' => $result
        ]);
    }

    function themmoi()
    {
        // Lấy danh sách danh mục, thương hiệu, nhà cung cấp cho dropdown
        $dsdm = $this->dm->DanhMuc_getAll();
        $dsth = $this->th->ThuongHieu_getAll();
        $dsncc = $this->ncc->NhaCungCap_getAll();
        $result = $this->sp->SanPham_getAll();

        $this->view('Master', [
            'page' => 'sanpham_v',
            'masanpham' => '',
            'tensanpham' => '',
            'hinhanh' => '',
            'madanhmuc' => '',
            'mathuonghieu' => '',
            'manhacungcap' => '',
            'dsdm' => $dsdm,
            'dsth' => $dsth,
            'dsncc' => $dsncc,
            'dulieu' => $result
        ]);
    }

    function ins()
    {
        if (isset($_POST['btnLuu'])) {
            $ma_san_pham = $_POST['txtMaSanPham'];
            $ten_san_pham = $_POST['txtTenSanPham'];
            $img_hinh_anh = $_POST['txtHinhAnh']; // Có thể xử lý upload hình ảnh sau
            $ma_danh_muc = $_POST['ddlDanhMuc'];
            $ma_thuong_hieu = $_POST['ddlThuongHieu'];
            $ma_nha_cung_cap = $_POST['ddlNhaCungCap'];
            
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
                        'page' => 'sanpham_v',
                        'masanpham' => $ma_san_pham,
                        'tensanpham' => $ten_san_pham,
                        'hinhanh' => $img_hinh_anh,
                        'madanhmuc' => $ma_danh_muc,
                        'mathuonghieu' => $ma_thuong_hieu,
                        'manhacungcap' => $ma_nha_cung_cap,
                        'dsdm' => $dsdm,
                        'dsth' => $dsth,
                        'dsncc' => $dsncc
                    ]);
                } else {
                    $kq = $this->sp->sanpham_ins($ma_san_pham, $ten_san_pham, $img_hinh_anh, $ma_danh_muc, $ma_thuong_hieu, $ma_nha_cung_cap);
                    if ($kq) {
                        echo "<script>alert('Thêm mới thành công!')</script>";
                        $this->danhsach();
                    } else {
                        echo "<script>alert('Thêm mới thất bại!')</script>";
                        $this->view('Master', [
                            'page' => 'sanpham_v',
                            'masanpham' => $ma_san_pham,
                            'tensanpham' => $ten_san_pham,
                            'hinhanh' => $img_hinh_anh,
                            'madanhmuc' => $ma_danh_muc,
                            'mathuonghieu' => $ma_thuong_hieu,
                            'manhacungcap' => $ma_nha_cung_cap,
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
        // Lấy các tham số tìm kiếm từ biểu mẫu
        $ma_san_pham = $_POST['txtMaSanPham'] ?? '';
        $ten_san_pham = $_POST['txtTenSanPham'] ?? '';

        // LẤY DỮ LIỆU THEO MÃ SẢN PHẨM + TÊN SẢN PHẨM
        $result = $this->sp->SanPham_find($ma_san_pham, $ten_san_pham);

        // DISPLAY VIEW
        $this->view('Master', [
            'page' => 'danhsachsanpham_v',
            'masanpham' => $ma_san_pham,
            'tensanpham' => $ten_san_pham,
            'dulieu' => $result
        ]);
    }

    function sua($ma_san_pham)
    {
        $result = $this->sp->SanPham_getById($ma_san_pham);
        $row = mysqli_fetch_array($result);
        
        // Lấy danh sách danh mục, thương hiệu, nhà cung cấp cho dropdown
        $dsdm = $this->dm->DanhMuc_getAll();
        $dsth = $this->th->ThuongHieu_getAll();
        $dsncc = $this->ncc->NhaCungCap_getAll();

        $this->view('Master', [
            'page' => 'sanpham_sua',
            'masanpham' => $row['ma_san_pham'],
            'tensanpham' => $row['ten_san_pham'],
            'hinhanh' => $row['img_hinh_anh'],
            'madanhmuc' => $row['ma_danh_muc'],
            'mathuonghieu' => $row['ma_thuong_hieu'],
            'manhacungcap' => $row['ma_nha_cung_cap'],
            'dsdm' => $dsdm,
            'dsth' => $dsth,
            'dsncc' => $dsncc
        ]);
    }

    function update()
    {
        if (isset($_POST['btnCapnhat'])) {
            $ma_san_pham = $_POST['txtMaSanPham'];
            $ten_san_pham = $_POST['txtTenSanPham'];
            $img_hinh_anh = $_POST['txtHinhAnh'];
            $ma_danh_muc = $_POST['ddlDanhMuc'];
            $ma_thuong_hieu = $_POST['ddlThuongHieu'];
            $ma_nha_cung_cap = $_POST['ddlNhaCungCap'];

            $kq = $this->sp->SanPham_update($ma_san_pham, $ten_san_pham, $img_hinh_anh, $ma_danh_muc, $ma_thuong_hieu, $ma_nha_cung_cap);
            if ($kq)
                echo "<script>alert('Cập nhật thành công!')</script>";
            else
                echo "<script>alert('Cập nhật thất bại!')</script>";

            $this->Get_data();
        }
    }

    function xoa($ma_san_pham)
    {
        $kq = $this->sp->SanPham_delete($ma_san_pham);
        if ($kq)
            echo "<script>alert('Xóa thành công!'); window.location='" . $this->url('Sanpham/danhsach') . "';</script>";
        else
            echo "<script>alert('Xóa thất bại!'); window.location='" . $this->url('Sanpham/danhsach') . "';</script>";
    }
}