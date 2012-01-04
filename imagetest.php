<?php

$im = imagecreate(100, 100);

$string = 'ä[800523|SSSAKL]';

$bg = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);

// prints a black "P" in the top left corner
imagechar($im, 50, 0, 0, $string, $black);

header('Content-type: image/png');
imagepng($im);

?>