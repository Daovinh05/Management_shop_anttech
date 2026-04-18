<?php
/**
 * Cron Job: Tu dong huy don qua han va hoan kho
 *
 * Cach chay thu cong:
 *   php cron_cancel_orders.php
 *
 * Goi y lich chay:
 * - Linux cron: moi 5 phut chay 1 lan (vi du: star-slash-5 ...)
 * - Windows Task Scheduler: chay moi 5 phut voi lenh php.exe cron_cancel_orders.php
 */

// Dam bao Config.php khong bi notice khi chay CLI.
if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = 'localhost';
}
if (!isset($_SERVER['SCRIPT_NAME'])) {
    $_SERVER['SCRIPT_NAME'] = '/index.php';
}
if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = '/';
}

require_once __DIR__ . '/MVC/Core/Config.php';

$logFile = __DIR__ . '/cron_cancel_orders.log';

function writeCronLog($logFile, $level, $message)
{
    $line = '[' . date('Y-m-d H:i:s') . '] [' . $level . '] ' . $message . PHP_EOL;
    file_put_contents($logFile, $line, FILE_APPEND);
}

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) {
    writeCronLog($logFile, 'ERROR', 'Ket noi DB that bai: ' . $mysqli->connect_error);
    fwrite(STDERR, "[ERROR] Ket noi DB that bai: " . $mysqli->connect_error . PHP_EOL);
    exit(1);
}

$mysqli->set_charset('utf8mb4');
$mysqli->query("SET time_zone = '+07:00'");

// 1) Lay gia tri timeout tu bang settings.
// Uu tien key theo phut; fallback key theo gio de tuong thich du lieu cu.
$timeoutMinutes = 1440; // mac dinh 24 gio

$settingSqlMinutes = "SELECT config_value FROM settings WHERE config_key = 'order_timeout_minutes' LIMIT 1";
$settingResultMinutes = $mysqli->query($settingSqlMinutes);
if ($settingResultMinutes && $settingResultMinutes->num_rows > 0) {
    $rowMinutes = $settingResultMinutes->fetch_assoc();
    $candidateMinutes = (int)($rowMinutes['config_value'] ?? 0);
    if ($candidateMinutes > 0) {
        $timeoutMinutes = $candidateMinutes;
    }
} else {
    $settingSqlHours = "SELECT config_value FROM settings WHERE config_key = 'order_timeout_hours' LIMIT 1";
    $settingResultHours = $mysqli->query($settingSqlHours);
    if ($settingResultHours && $settingResultHours->num_rows > 0) {
        $rowHours = $settingResultHours->fetch_assoc();
        $candidateHours = (int)($rowHours['config_value'] ?? 0);
        if ($candidateHours > 0) {
            $timeoutMinutes = $candidateHours * 60;
        }
    }
}

try {
    $mysqli->begin_transaction();

    // 2) Xac dinh danh sach don qua han dang cho_duyet (khoa FOR UPDATE de tranh race condition).
    $selectSql = "SELECT ma_don_hang
                  FROM don_hang
                  WHERE trang_thai_don_hang = 'cho_duyet'
                                        AND ngay_tao < DATE_SUB(NOW(), INTERVAL ? MINUTE)
                  FOR UPDATE";

    $stmtSelect = $mysqli->prepare($selectSql);
    if (!$stmtSelect) {
        throw new Exception('Prepare SELECT that bai: ' . $mysqli->error);
    }

    $stmtSelect->bind_param('i', $timeoutMinutes);
    if (!$stmtSelect->execute()) {
        throw new Exception('Execute SELECT that bai: ' . $stmtSelect->error);
    }

    $result = $stmtSelect->get_result();
    $orderIds = [];
    while ($row = $result->fetch_assoc()) {
        if (!empty($row['ma_don_hang'])) {
            $orderIds[] = $row['ma_don_hang'];
        }
    }
    $stmtSelect->close();

    if (empty($orderIds)) {
        $mysqli->commit();
        writeCronLog($logFile, 'INFO', 'Khong co don qua han de huy. Timeout=' . $timeoutMinutes . 'm');
        echo "[OK] Khong co don qua han de huy. Timeout={$timeoutMinutes}m" . PHP_EOL;
        $mysqli->close();
        exit(0);
    }

    // 3) Hoan kho cho cac bien the trong nhung don se bi huy.
    //    Gom nhom theo ma_bien_the de update kho mot lan cho moi bien the.
    $escapedIds = array_map([$mysqli, 'real_escape_string'], $orderIds);
    $inList = "'" . implode("','", $escapedIds) . "'";

    $restockSql = "UPDATE bien_the bt
                   INNER JOIN (
                       SELECT ctdh.ma_bien_the, SUM(ctdh.so_luong) AS total_qty
                       FROM chi_tiet_don_hang ctdh
                       WHERE ctdh.ma_don_hang IN ($inList)
                       GROUP BY ctdh.ma_bien_the
                   ) x ON x.ma_bien_the = bt.ma_bien_the
                   SET bt.so_luong_kho = bt.so_luong_kho + x.total_qty";

    if (!$mysqli->query($restockSql)) {
        throw new Exception('Hoan kho that bai: ' . $mysqli->error);
    }

    // 4) Cau query UPDATE DUY NHAT de chuyen trang thai don qua han sang da_huy.
    //    (Dung danh sach orderIds vua khoa o tren de dam bao dong bo voi hoan kho)
    $cancelSql = "UPDATE don_hang
                  SET trang_thai_don_hang = 'da_huy'
                  WHERE ma_don_hang IN ($inList)
                    AND trang_thai_don_hang = 'cho_duyet'";

    if (!$mysqli->query($cancelSql)) {
        throw new Exception('Cap nhat huy don that bai: ' . $mysqli->error);
    }

    $cancelledCount = $mysqli->affected_rows;

    $mysqli->commit();
    writeCronLog(
        $logFile,
        'INFO',
        'Da huy ' . $cancelledCount . ' don qua han. Timeout=' . $timeoutMinutes . 'm. Orders=[' . implode(',', $orderIds) . ']'
    );
    echo "[OK] Da huy {$cancelledCount} don qua han. Timeout={$timeoutMinutes}m" . PHP_EOL;
} catch (Exception $e) {
    $mysqli->rollback();
    writeCronLog($logFile, 'ERROR', 'Cron cancel orders that bai: ' . $e->getMessage());
    fwrite(STDERR, "[ERROR] Cron cancel orders that bai: " . $e->getMessage() . PHP_EOL);
    $mysqli->close();
    exit(1);
}

$mysqli->close();
exit(0);
