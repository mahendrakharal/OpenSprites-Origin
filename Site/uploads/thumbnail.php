<?php
function imagecreatefromfile( $filename ) {
    switch ( strtolower( pathinfo( $filename, PATHINFO_EXTENSION ))) {
        case 'jpeg':
        case 'jpg':
            return imagecreatefromjpeg($filename);
        break;

        case 'png':
            return imagecreatefrompng($filename);
        break;

        case 'gif':
            return imagecreatefromgif($filename);
        break;

        default:
            throw new InvalidArgumentException('File "'.$filename.'" is not valid jpg, png or gif image.');
        break;
    }
}

if(!isset($_GET['file'])) die("Param missing");
$file = $_GET['file'];
if(strpos($file, "..") !== FALSE
    || strpos($file, "/") !== FALSE
    || strpos($file, "\\") !== FALSE) die("Param missing"); // prevent hax
$file = "uploaded/" . $file;
if(!file_exists($file)) die("404");
$ending = strtolower( pathinfo( $file, PATHINFO_EXTENSION ));

if($ending == "wut" || $ending == "svg"){
  die(file_get_contents($file));
}
$source_image = imagecreatefromfile($file);
$width = imagesx($source_image);
$height = imagesy($source_image);
$desired_width = 200;
$desired_height = floor($height * ($desired_width / $width));
$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
header("Content-Type: image/jpeg");
imagejpeg($virtual_image, NULL, 95);
imagedestroy($virtual_image);
imagedestroy($source_image);
?>