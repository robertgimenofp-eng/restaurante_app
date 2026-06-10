<?php
$dirs = [
    'c:/xampp/htdocs/restaurante_app/views',
    'c:/xampp/htdocs/restaurante_app/controllers',
    'c:/xampp/htdocs/restaurante_app/api',
    'c:/xampp/htdocs/restaurante_app/assets/js'
];

$replacements = [
    'Ã¡' => 'á', 'Ã©' => 'é', 'Ã³' => 'ó', 'Ã­' => 'í', 'Ãº' => 'ú',
    'Ã±' => 'ñ', 'Ã‘' => 'Ñ', 'Â¡' => '¡', 'Â¿' => '¿', 'Ã“' => 'Ó',
    'Ãš' => 'Ú', 'Ã' => 'Á', 'Ã‰' => 'É', 'Ã' => 'Í', 'Â' => '',
    'ðŸ ª' => '🍪', 'ðŸ ”' => '🍔', 'â‚¬' => '€', 'âœ…' => '✅',
    'LÃ“GICA' => 'LÓGICA', 'MENÃš' => 'MENÚ', 'LÃNEAS' => 'LÍNEAS',
    'REDRECCIÃ“N' => 'REDIRECCIÓN'
];

function recursiveReplace($dir, $replacements) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && in_array($file->getExtension(), ['php', 'js'])) {
            $content = file_get_contents($file->getPathname());
            $newContent = str_replace(array_keys($replacements), array_values($replacements), $content);
            if ($newContent !== $content) {
                file_put_contents($file->getPathname(), $newContent);
                echo "Fixed: " . $file->getPathname() . "\n";
            }
        }
    }
}

foreach($dirs as $dir) {
    recursiveReplace($dir, $replacements);
}
echo "Done!\n";
?>
