<?php
$files = glob('controllers/*.php');
foreach($files as $file) {
    $content = file_get_contents($file);
    // Reemplaza require_once 'models/...', 'views/...', 'database/...'
    $content = preg_replace("/require_once\s+['\"](models|views|database)\//", "require_once __DIR__ . '/../$1/", $content);
    file_put_contents($file, $content);
}
echo "Paths fixed!\n";
?>
