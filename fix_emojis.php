<?php
$c = file_get_contents('views/main.php');
$c = str_replace(['ðŸ ª', 'ðŸ ”'], ['🍪', '🍔'], $c);
file_put_contents('views/main.php', $c);
echo "Emojis fixed.\n";
?>
