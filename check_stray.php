<?php
$dir = __DIR__ . '/MVC';
$it = new RecursiveDirectoryIterator($dir);
foreach (new RecursiveIteratorIterator($it) as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) == 'php') {
        $content = file_get_contents($file);
        if (substr(trim($content), 0, 5) != '<?php' && substr(trim($content), 0, 2) != '<?') {
            // This might just be a file with only HTML, but we're looking for stray chars in PHP files
            continue;
        }
        
        // Find the first occurrence of <?php
        $pos = strpos($content, '<?php');
        if ($pos === false) $pos = strpos($content, '<?');
        
        if ($pos > 0) {
            $stray = substr($content, 0, $pos);
            echo "Stray characters found before PHP tag in: " . $file . " (Hex: " . bin2hex($stray) . ")\n";
        }
    }
}
echo "Check complete.\n";
