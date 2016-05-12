<?php
session_start();
function randomString($num = 5)
{
        $str = '';
        for($i = 0; $i < $num; $i++)
        {
                $str .= mt_rand(0,9);
        }
        return $str;
}
function image($str)
{
        $largeur = (strlen($str) * 10); 
        $height = 20; 
        $img = imagecreate($largeur,$height);

        $blanc = imagecolorallocate($img, 255, 255, 255); 
        $black = imagecolorallocate($img, 0, 0, 0);
        $red = imagecolorallocate($img, 223, 14, 2);

        $midheight = ($height / 2) - 8; 
        imagestring($img, 6, (strlen($str) / 2 ), $midheight, $str, $black); 

                $height1 = mt_rand(2,$height); 
                $height2 = mt_rand(2,$height); 

        ImageLine ($img, 2,$height1, $largeur - 2, $height2, $red); 
        ImageLine ($img, 2, $midheight + 8, $largeur - 2, $midheight + 8, $red); 
        imagepng($img); 
        imagedestroy($img);
        return $str;
}
$nbr = 6; 
$str = randomString($nbr); 
$_SESSION['WV_CAPTCHA'] = sha1($str); 
header("Content-type: image/png");
image($str); 
