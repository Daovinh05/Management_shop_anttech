<?php
require_once __DIR__ . '/../tcpdf/tcpdf.php';
require_once __DIR__ . '/../TimezoneHelper.php';

class InvoicePDF extends TCPDF
{
    private $order_data;
    private $order_details;
    private $order_total;
    private $discount_amount;

    public function __construct($order_data, $order_details, $order_total, $discount_amount = 0)
    {
        parent::__construct('P', 'mm', 'A4', true, 'UTF-8', false);
        $this->order_data = $order_data;
        $this->order_details = $order_details;
        $this->order_total = $order_total;
        $this->discount_amount = $discount_amount;

        // Thiết lập thông tin tài liệu
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('Banhang System');
        $this->SetTitle('Hóa đơn #' . $order_data['ma_don_hang']);
        $this->SetSubject('Hóa đơn bán hàng');

        // Loại bỏ header/footer mặc định
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);

        // Thiết lập lề
        $this->SetMargins(15, 15, 15);
        $this->SetAutoPageBreak(TRUE, 15);

        // Thiết lập phông chữ - sử dụng phông chữ TCPDF mặc định hỗ trợ UTF-8
        $this->SetFont('dejavusans', '', 12);
    }

    // Header trang
    // public function Header()
    // {
    //     // Logo
    //     $image_file = K_PATH_IMAGES.'logo_example.jpg';
    //     if (file_exists($image_file)) {
    //         $this->Image($image_file, 15, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    //     }

    //     // Title
    //     $this->SetFont('dejavusans', 'B', 18);
    //     $this->Cell(0, 10, 'HÓA ĐƠN BÁN HÀNG', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    //     $this->Ln(10);

    //     // Add line separator
    //     $this->Line(15, $this->GetY(), 195, $this->GetY());
    //     $this->Ln(5);
    // }

    public function Header()
    {
        // === 1. ẢNH QUÁN ===
        $image_file = K_PATH_IMAGES . 'quan.jpg';
        if (file_exists($image_file)) {
            // căn giữa ảnh
            $this->Image($image_file, 85, 10, 40);
        }

        $this->Ln(40);

        // === 2. TÊN QUÁN ===
        $this->SetFont('dejavusans', 'B', 16);
        $this->Cell(0, 8, 'QUÁN ABC COFFEE', 0, 1, 'C');

        // === 3. ĐỊA CHỈ ===
        $this->SetFont('dejavusans', '', 11);
        $this->Cell(0, 6, 'Địa chỉ: 54 Triều Khúc, Thanh Xuân Nma, Hà Nội', 0, 1, 'C');

        // === 4. HOTLINE ===
        $this->Cell(0, 6, 'Hotline: 0389783619', 0, 1, 'C');

        $this->Ln(5);

        // === 5. GẠCH NGANG ===
        $this->Line(15, $this->GetY(), 195, $this->GetY());
        $this->Ln(6);

        // === 6. TIÊU ĐỀ HÓA ĐƠN ===
        $this->SetFont('dejavusans', 'B', 18);
        $this->Cell(0, 10, 'HÓA ĐƠN BÁN HÀNG', 0, 1, 'C');

        $this->Ln(5);
    }

    // Footer trang
    public function Footer()
    {
        // Đặt vị trí cách đáy 15 mm
        $this->SetY(-15);
        $this->SetFont('dejavusans', '', 8);
        $this->Cell(0, 10, 'Trang ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    public function generateInvoice()
    {
        $this->AddPage();

        // Header
        $this->Header();

        // Thông tin đơn hàng
        $this->SetFont('dejavusans', '', 12);
        $this->Cell(0, 6, 'Mã đơn hàng: ' . ($this->order_data['ma_don_hang'] ?? 'N/A'), 0, 1);
        $this->Cell(0, 6, 'Ngày đặt: ' . (isset($this->order_data['ngay_tao']) ? TimezoneHelper::formatForDisplay($this->order_data['ngay_tao'], 'H:i:s d/m/Y') : 'N/A'), 0, 1);

        // Hiển thị ghi chú đơn hàng nếu có
        if (!empty($this->order_data['ghi_chu'])) {
            $this->Cell(0, 6, 'Ghi chú đơn hàng: ' . $this->order_data['ghi_chu'], 0, 1);
        }

        $this->Ln(8);

        // Tiêu đề bảng
        $this->SetFont('dejavusans', 'B', 12);
        $header = array('Món ăn', 'Số lượng', 'Đơn giá', 'Thành tiền');
        $w = array(90, 20, 30, 30);

        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 0);
        $this->Ln();

        // Dữ liệu
        $this->SetFont('dejavusans', '', 12);
        foreach ($this->order_details as $detail) {
            $this->Cell($w[0], 6, $detail['ten_mon'], 'LRB', 0, 'L');
            $this->Cell($w[1], 6, $detail['so_luong'], 'RB', 0, 'C');
            $this->Cell($w[2], 6, number_format($detail['gia_tai_thoi_diem_dat'], 0, '.', '') . 'đ', 'RB', 0, 'R');
            $this->Cell($w[3], 6, number_format($detail['gia_tai_thoi_diem_dat'] * $detail['so_luong'], 0, '.', '') . 'đ', 'RB', 0, 'R');
            $this->Ln();
        }

        // Tổng cộng
        $this->SetFont('dejavusans', 'B', 12);
        $this->Cell(array_sum($w) - 30, 6, 'Tổng cộng:', 'T', 0, 'R');
        $this->Cell(30, 6, number_format($this->order_total, 0, '.', '') . 'đ', 'TB', 0, 'R');
        $this->Ln(6);

        // Giảm giá
        if ($this->discount_amount > 0) {
            $this->SetFont('dejavusans', '', 12);
            $this->Cell(array_sum($w) - 30, 6, 'Giảm giá:', 0, 0, 'R');
            $this->Cell(30, 6, '-' . number_format($this->discount_amount, 0, '.', '') . 'đ', 0, 0, 'R');
            $this->Ln(6);
        }

        // Số tiền cần thanh toán
        $amount_to_pay = $this->order_total - $this->discount_amount;
        $this->SetFont('dejavusans', 'B', 12);
        $this->Cell(array_sum($w) - 30, 6, 'Số tiền cần thanh toán:', 0, 0, 'R');
        $this->Cell(30, 6, number_format($amount_to_pay, 0, '.', '') . 'đ', 0, 0, 'R');
        $this->Ln(12);

        // Văn bản chân trang
        $this->SetFont('dejavusans', '', 12);
        $this->Cell(0, 6, 'Cảm ơn quý khách đã sử dụng dịch vụ!', 0, 1, 'C');
        $this->Ln(5);

        // Chữ ký
        $this->SetFont('dejavusans', '', 12);
        $this->Cell(90, 10, 'Khách hàng', 0, 0, 'C');
        $this->Cell(90, 10, 'Nhân viên bán hàng', 0, 1, 'C');
        $this->Ln(15);
        $this->Cell(90, 10, '(Ký và ghi rõ họ tên)', 0, 0, 'C');

        $text = "(Ký và ghi rõ họ tên)\nNgười tạo: " . ($this->order_data['ten_user'] ?? 'N/A');
        $this->MultiCell(90, 6, $text, 0, 'C');
    }
}
