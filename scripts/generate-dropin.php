<?php

$root = dirname(__DIR__);
$header = file_get_contents("$root/partials/header.php");
$compiled = file_get_contents("$root/dist/compiled.php");
// Strip opening `<?php` tag from compiled file.
$compiled = substr($compiled, strlen('<?php'));
$output = $header . $compiled;

file_put_contents("$root/dist/db.php", $output);
