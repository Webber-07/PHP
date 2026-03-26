<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();

if (!extension_loaded('gd')) {
    ob_end_clean();
    header('Content-Type: image/png');
    header('Cache-Control: no-store');
    echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
    exit;
}

$img = imagecreatetruecolor(300, 150);
$bg = imagecolorallocate($img, 235, 235, 240);
imagefill($img, 0, 0, $bg);

// Красный цвет и прямоугольник
$red = imagecolorallocate($img, 255, 0, 0);
imagefilledrectangle($img, 20, 20, 120, 100, $red);

// Синий цвет и эллипс
$blue = imagecolorallocate($img, 0, 0, 255);
imagefilledellipse($img, 220, 75, 100, 60, $blue);

// Чёрный цвет и текст
$black = imagecolorallocate($img, 0, 0, 0);
imagestring($img, 5, 130, 65, 'PHP', $black);

ob_end_clean();
header('Content-Type: image/png');
header('Cache-Control: no-store');
imagepng($img);
?>
