<?php
$dir = __DIR__ . '/MVC';
$it = new RecursiveDirectoryIterator($dir);
foreach (new RecursiveIteratorIterator($it) as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) == 'php') {
        $content = file_get_contents($file);
        if (substr($content, 0, 3) == pack('CCC', 0xef, 0xbb, 0xbf)) {
            echo "BOM found in: " . $file . "\n";
        }
    }
}
echo "Check complete.\n";
