<?php
function single2image($id, $text) {
    if (!file_exists("data/img/".$id.".jpg")) {
        //$img = imagecreatefromjpeg('balloon.jpg');
        $img_h = 630;
        $img_w = 1200;
        $img = imagecreate($img_w, $img_h);
        $white = imagecolorallocate($img, 255, 255, 255);
        $grey = imagecolorallocate($img, 51, 51, 51);
        $green = imagecolorallocate($img, 0, 128, 0);
        $logo = "variety42.com";
        $font = "data/font/Arial.ttf";
        $font_size = ($img_h-30) / 7.7;

        $text = wordwrap($text, 26, "\n");
        $lines = explode("\n", $text);
        $text = $lines[0]."\n".$lines[1]."\n".$lines[2]."\n".$lines[3]."\n".$lines[4];

        $offset = 15+$font_size;
        if (count($lines)<5)
            $offset += $font_size * (5-count($lines))/2;
        imagettftext($img, $font_size, 0, 15, $offset, $grey, $font, $text);
        imagettftext($img, 0.5*$font_size, 0, $img_w - 340, ($img_h - 20), $green, $font, $logo);

        // OUTPUT IMAGE
        imagejpeg($img, "data/img/".$id.".jpg", 100);
    }
}
?>
