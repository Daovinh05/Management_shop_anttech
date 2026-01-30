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
        $this->SetTitle('Đơn hàng #' . $order_data['ma_don_hang'] . ' - Dành cho pha chế');
        $this->SetSubject('Đơn hàng cho pha chế');

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
    public function Header()
    {
        // Tiêu đề
        $this->SetFont('dejavusans', 'B', 18);
        $this->Cell(0, 10, 'ĐƠN HÀNG CHO PHA CHẾ', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(8);

        // Thông tin đơn hàng
        $this->SetFont('dejavusans', '', 12);
        $this->Cell(0, 6, 'Mã đơn hàng: ' . ($this->order_data['ma_don_hang'] ?? 'N/A'), 0, 1);
        $this->Cell(0, 6, 'Ngày tạo: ' . date('H:i:s d/m/Y'), 0, 1);

        // Thêm dòng phân cách
        $this->Ln(5);
        $this->Line(15, $this->GetY(), 195, $this->GetY());
        $this->Ln(8);
    }

    // Footer trang
    public function Footer()
    {
        // Đặt vị trí cách đáy 15 mm
        $this->SetY(-15);
        $this->SetFont('dejavusans', '', 8);
        $this->Cell(0, 10, 'Trang '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    public function generateInvoice()
{
    $this->AddPage(); // Header tự chạy

    $this ->Header();
    // Tiêu đề bảng
    $this->SetFont('dejavusans', 'B', 12);
    $header = array('STT', 'Tên món', 'Số lượng');
    $w = array(25, 115, 30);

    foreach ($header as $i => $col) {
        $this->Cell($w[$i], 7, $col, 1, 0, 'C');
    }
    $this->Ln();

    // Dữ liệu
    $this->SetFont('dejavusans', '', 12);
    foreach ($this->order_details as $index => $detail) {
        $this->Cell($w[0], 6, $index + 1, 1, 0, 'C');
        $this->Cell($w[1], 6, $detail['ten_mon'], 1, 0, 'L');
        $this->Cell($w[2], 6, $detail['so_luong'], 1, 1, 'C');
    }

    // Ghi chú
    $this->Ln(5);
    $this->SetFont('dejavusans', 'B', 12);
    $this->Cell(0, 6, 'Ghi chú đơn hàng:', 0, 1);

    $this->SetFont('dejavusans', '', 12);
    $this->MultiCell(0, 6,
        $this->order_data['ghi_chu'] ?? 'Không có',
        0, 'L'
    );

    // Văn bản chân trang
    $this->Ln(10);
    $this->SetFont('dejavusans', 'I', 10);
    $this->Cell(0, 6, 'Dành cho nhân viên pha chế - Quán ABC Coffee', 0, 1, 'C');
}

}
?>