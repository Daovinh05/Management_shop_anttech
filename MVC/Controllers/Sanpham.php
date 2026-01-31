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
            'page' => 'Danhsachsanpham_v',
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
            'ma_san_pham' => '',
            'ten_san_pham' => '',
            'img_hinh_anh' => '',
            'ma_danh_muc' => '',
            'ma_thuong_hieu' => '',
            'ma_nha_cung_cap' => '',
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
            $ma_danh_muc = isset($_POST['ddlDanhmuc']) ? $_POST['ddlDanhmuc'] : '';
            $ma_thuong_hieu = isset($_POST['ddlThuonghieu']) ? $_POST['ddlThuonghieu'] : '';
            $ma_nha_cung_cap = isset($_POST['ddlNhacungcap']) ? $_POST['ddlNhacungcap'] : '';

            $dsdm = $this->dm->DanhMuc_getAll();
            $dsth = $this->th->ThuongHieu_getAll();
            $dsncc = $this->ncc->NhaCungCap_getAll();

            // Xử lý upload hình ảnh
            $img_hinh_anh = '';
            if (isset($_FILES['txtImage']) && $_FILES['txtImage']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $filename = $_FILES['txtImage']['name'];
                $filetmp = $_FILES['txtImage']['tmp_name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (in_array($ext, $allowed)) {
                    // Làm sạch tên tệp gốc ( xoá kí tự đặc biệt)
                    $original_name = pathinfo($filename, PATHINFO_FILENAME);
                    $original_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $original_name);
                    $original_name = str_replace('-', '_', $original_name);
                    $new_filename = $original_name . '.' . $ext;

                    // Kiểm tra nếu tên tệp đã tồn tại, thêm hậu tố cho đến khi không trùng
                    $counter = 1;
                    $final_filename = $new_filename;
                    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/Banhang/Public/Pictures/sanpham/';

                    while (file_exists($upload_dir . $final_filename)) {
                        $final_filename = $original_name . '_' . $counter . '.' . $ext;
                        $counter++;
                    }

                    // Sử dụng đường dẫn tuyệt đối đến thư mục Public/Pictures/sanpham
                    $upload_path = $upload_dir . $final_filename;

                    // Tạo thư mục nếu chưa tồn tại
                    if (!is_dir($upload_dir)) {
                        // Tạo thư mục với quyền cao hơn và đảm bảo thư mục cha tồn tại
                        mkdir($upload_dir, 0777, true);
                    }

                    if (move_uploaded_file($filetmp, $upload_path)) {
                        $img_hinh_anh = $final_filename; // Chỉ lưu tên tệp vào DB
                    } else {
                        echo "<script>alert('Upload hình ảnh thất bại!');</script>";
                        $this->view('Master', [
                            'page' => 'sanpham_v',
                            'ma_san_pham' => $ma_san_pham,
                            'ten_san_pham' => $ten_san_pham,
                            'img_hinh_anh' => $img_hinh_anh,
                            'dsdm' => $dsdm,
                            'dsth' => $dsth,
                            'dsncc' => $dsncc
                        ]);
                        return;
                    }
                } else {
                    echo "<script>alert('Định dạng hình ảnh không hợp lệ!');</script>";
                    $this->view('Master', [
                        'page' => 'sanpham_v',
                        'ma_san_pham' => $ma_san_pham,
                        'ten_san_pham' => $ten_san_pham,
                        'img_hinh_anh' => $img_hinh_anh,
                        'ma_danh_muc' => $ma_danh_muc,
                        'ma_thuong_hieu' => $ma_thuong_hieu,
                        'ma_nha_cung_cap' => $ma_nha_cung_cap,
                        'dsdm' => $dsdm,
                        'dsth' => $dsth,
                        'dsncc' => $dsncc
                    ]);
                    return;
                }
            } else {
                // Nếu không có file upload mới, sử dụng giá trị từ form (trường text)
                $img_hinh_anh = isset($_POST['txtImage']) ? $_POST['txtImage'] : '';
            }

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
                        'ma_san_pham' => $ma_san_pham,
                        'ten_san_pham' => $ten_san_pham,
                        'img_hinh_anh' => $img_hinh_anh,
                        'ma_danh_muc' => $ma_danh_muc,
                        'ma_thuong_hieu' => $ma_thuong_hieu,
                        'ma_nha_cung_cap' => $ma_nha_cung_cap,
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
                            'ma_san_pham' => $ma_san_pham,
                            'ten_san_pham' => $ten_san_pham,
                            'img_hinh_anh' => $img_hinh_anh,
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
        // Lấy các tham số tìm kiếm từ biểu mẫu
        $ma_san_pham = $_POST['txtMasanpham'] ?? '';
        $ten_san_pham = $_POST['txtTensanpham'] ?? '';

        // LẤY DỮ LIỆU THEO MÃ SẢN PHẨM + TÊN SẢN PHẨM
        $result = $this->sp->SanPham_find($ma_san_pham, $ten_san_pham);

        if (isset($_POST['btnXuatexcel'])) {

            $objExcel = new PHPExcel();
            $objExcel->setActiveSheetIndex(0);
            $sheet = $objExcel->getActiveSheet()->setTitle('DanhSachSanPham');

            $sheet->setCellValue('A1', 'Mã sản phẩm');
            $sheet->setCellValue('B1', 'Tên sản phẩm');
            $sheet->setCellValue('C1', 'Hình ảnh');
            $sheet->setCellValue('D1', 'Giá');
            $sheet->setCellValue('E1', 'Số lượng');
            $sheet->setCellValue('F1', 'Tên danh mục');
            $sheet->setCellValue('G1', 'Tên thương hiệu');
            $sheet->setCellValue('H1', 'Tên nhà cung cấp');




            $rowCount = 2; // Starting from row 2 since row 1 is headers
            mysqli_data_seek($result, 0); // Reset result pointer to beginning
            while ($row = mysqli_fetch_assoc($result)) {
                $sheet->setCellValue('A' . $rowCount, $row['ma_san_pham']);
                $sheet->setCellValue('B' . $rowCount, $row['ten_san_pham']);
                $sheet->setCellValue('C' . $rowCount, $row['img_hinh_anh']);
                $sheet->setCellValue('D' . $rowCount, $row['gia']);
                $sheet->setCellValue('E' . $rowCount, $row['so_luong']);
                $sheet->setCellValue('F' . $rowCount, $row['ten_danh_muc']);
                $sheet->setCellValue('G' . $rowCount, $row['ten_thuong_hieu']);
                $sheet->setCellValue('H' . $rowCount, $row['ten_nha_cung_cap']);

                $rowCount++;
            }

            foreach (range('A', 'H') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            if (ob_get_length()) ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="DanhSachSanPham.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $writer->save('php://output');
            exit;
        }
        // DISPLAY VIEW
        $this->view('Master', [
            'page' => 'Danhsachsanpham_v',
            'ma_san_pham' => $ma_san_pham,
            'ten_san_pham' => $ten_san_pham,
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
            'page' => 'Sanpham_sua',
            'ma_san_pham' => $row['ma_san_pham'],
            'ten_san_pham' => $row['ten_san_pham'],
            'img_hinh_anh' => $row['img_hinh_anh'],

            'ma_danh_muc' => $row['ma_danh_muc'],
            'ma_thuong_hieu' => $row['ma_thuong_hieu'],
            'ma_nha_cung_cap' => $row['ma_nha_cung_cap'],
            'dsdm' => $dsdm,
            'dsth' => $dsth,
            'dsncc' => $dsncc
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

            // Lấy hình ảnh hiện tại từ database trước
            $current_record = $this->sp->SanPham_getById($ma_san_pham);
            $current_row = mysqli_fetch_array($current_record);
            $img_san_pham = $current_row['img_hinh_anh']; // Giữ hình ảnh hiện tại mặc định

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
                    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/Banhang/Public/Pictures/sanpham/';

                    while (file_exists($upload_dir . $final_filename)) {
                        $final_filename = $original_name . '_' . $counter . '.' . $ext;
                        $counter++;
                    }

                    // Nếu tên tệp mới khác với tên tệp gốc, có nghĩa là đã có tệp trùng
                    if ($final_filename !== $new_filename) {
                        // Tạo tên tệp mới với timestamp để đảm bảo duy nhất
                        $final_filename = $original_name . '_' . time() . '.' . $ext;
                    }

                    // Sử dụng đường dẫn tuyệt đối đến thư mục Public/Pictures/sanpham
                    $upload_path = $upload_dir . $final_filename;

                    // Tạo thư mục nếu chưa tồn tại
                    if (!is_dir($upload_dir)) {
                        // Tạo thư mục với quyền cao hơn
                        mkdir($upload_dir, 0777, true);
                    }

                    if (move_uploaded_file($filetmp, $upload_path)) {
                        // Xóa hình ảnh cũ nếu tồn tại
                        $old_image_path = $_SERVER['DOCUMENT_ROOT'] . '/Banhang/Public/Pictures/sanpham/' . $current_row['img_hinh_anh'];
                        if (!empty($current_row['img_hinh_anh']) && file_exists($old_image_path) && strpos($old_image_path, '/Public/Pictures/sanpham/') !== false) {
                            unlink($old_image_path);
                        }

                        $img_san_pham = $final_filename; // Chỉ lưu tên tệp vào DB
                    } else {
                        echo "<script>alert('Upload hình ảnh thất bại!');</script>";
                        $this->sua($ma_san_pham);
                        return;
                    }
                } else {
                    echo "<script>alert('Định dạng hình ảnh không hợp lệ!');</script>";
                    $this->sua($ma_san_pham);
                    return;
                }
            }
            // Nếu không có file upload mới, giữ nguyên hình ảnh hiện tại (đã được lấy ở trên)

            $kq = $this->sp->SanPham_update($ma_san_pham, $ten_san_pham, $img_san_pham, $ma_danh_muc, $ma_thuong_hieu, $ma_nha_cung_cap);
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
