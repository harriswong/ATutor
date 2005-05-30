<?php 
$_user_location	= 'public';
define('AT_INCLUDE_PATH', 'include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');

// Generate a Turing Test image and output it to the browser 
header('Content-Type: image/gif');

session_start();

$token = (string) $_SESSION['secret'];

$iFont = 8; // Font ID 
$iSpacing = 8; //rand(5,8); // Spacing between characters 
$iDisplacement = 0; // Vertical chracter displacement 

// Establish font metric and image size 
$iCharWidth = ImageFontWidth(5);
$iCharHeight = ImageFontHeight(5); 

$iWidth = strlen($token) * ($iCharWidth + $iSpacing)+2;
$iHeight = $iCharHeight + (2 * $iDisplacement)+ 2;


// Create the image 
$pic = ImageCreate($iWidth, $iHeight); 

// Allocate a background and foreground colour 

$col = ImageColorAllocate($pic, 200, 225, 255); 
$col2 = ImageColorAllocate($pic, 0, 0, 0); 
$col3 = ImageColorAllocate($pic, 0, 0, 0); 

for ($cnt=0; $cnt<11; $cnt++) {
	$text_color = ImageColorAllocate($pic, intval(rand(200,255)), intval(rand(200,255)), intval(rand(200,255)));
	ImageArc($pic,($cnt*8),10,intval(rand(15,30)),intval(rand(15,30)),0,360, $text_color); 
}
$iX=1; 

for ($i=0; $i < strlen ($token); $i++) { 
    ImageChar ($pic, $iFont, $iX, $iDisplacement - (rand (-$iDisplacement, $iDisplacement)), $token[$i], $col2); 
    $iX += $iCharWidth + $iSpacing +rand (-5,3); 
	$col2 = ImageColorAllocate ($pic, rand (0,90), rand (0,90), rand (0,110)); 
}

ImageGIF($pic);
?>