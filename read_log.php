<?php
$logPath = 'd:/xampp/apache/logs/error.log';
if (file_exists($logPath)) {
    $lines = file($logPath);
    // Lấy 50 dòng cuối
    $lines = array_slice($lines, -150);
    foreach ($lines as $line) {
        if (strpos($line, 'PHP Fatal error') !== false || strpos($line, 'Parse error') !== false) {
            echo htmlspecialchars($line) . "<br>";
        }
    }
    echo "--- END CAUGHT FATALS ---<br>";
    // in 20 dòng cuối cùng trong mọi trường hợp
    foreach (array_slice($lines, -20) as $line) {
        echo htmlspecialchars($line) . "<br>";
    }
} else {
    echo "Log file not found at " . $logPath;
}
