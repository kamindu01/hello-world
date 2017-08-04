<?php

//resize_image('uploads/asd.png');

function resize_image($image){


$imagesize = getImageSize($image);
$originalwidth = $imagesize[0];
$originalheight = $imagesize[1];

$width = 200;
$heigth = round(($width * $originalheight) / $originalwidth);


$im = imagecreatetruecolor($width, $heigth);
//$img = imagecreatefromjpeg($image);
$img = check_img_type($image);

//echo $img;


imageCopyResampled($im, $img, 0, 0, 0, 0, $width, $heigth, $originalwidth, $originalheight);

imagejpeg($im, $image);


imagedestroy($im);

}

function check_img_type($file){
	
	$extension = strtolower(strrchr($file, '.'));
 
    switch($extension)
    {
        case '.jpg':
        case '.jpeg':
            $img = imagecreatefromjpeg($file);
            break;
        case '.gif':
            $img = imagecreatefromgif($file);
            break;
        case '.png':
            $img = imagecreatefrompng($file);
            break;
        default:
            $img = false;
            break;
    }
    return $img;
	
	
	}

?>