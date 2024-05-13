<?php
session_start();

$_SESSION['captcha'] = mt_rand(1000, 9999);
$img = imagecreate(100, 30);
$font = 'fonts/Roboto-Medium.ttf';

$bg = imagecolorallocate($img, 255, 255, 255);
$textcolor = imagecolorallocate($img, 0, 0, 0);