<?php
require_once "../../../maincore.php";
require_once INFUSIONS."al_genmem/infusion_db.php";
require_once INCLUDES."photo_functions_include.php";


function create_memdem_images($file) {

    global $genmem_settings;

    $image_ext = strtolower(strrchr($file,"."));
    $image_res = @getimagesize(INFUSIONS."al_genmem/asset/generators/originals/".$file);
    $image_name = substr($file, 0, strrpos($file, "."));
    $image_name_mem = filename_exists(INFUSIONS."al_genmem/asset/generators/mems/", $image_name.$image_ext);
    $image_name_dem = filename_exists(INFUSIONS."al_genmem/asset/generators/dems/", $image_name.".jpg");
    if ($image_ext == ".gif") { $filetype = 1;
    } elseif ($image_ext == ".jpg") { $filetype = 2;
    } elseif ($image_ext == ".png") { $filetype = 3;
    }

    if ($image_res[0] > $genmem_settings['mem_width'] || $image_res[1] > $genmem_settings['mem_height']) {
        createthumbnail($filetype, INFUSIONS."al_genmem/asset/generators/originals/".$file, INFUSIONS."al_genmem/asset/generators/mems/".$image_name_mem, $genmem_settings['mem_width'], $genmem_settings['mem_height']);
    } else {
        copy(INFUSIONS."al_genmem/asset/generators/originals/".$file, INFUSIONS."al_genmem/asset/generators/mems/".$image_name_mem);
    }

    $mem_res = @getimagesize(INFUSIONS."al_genmem/asset/generators/mems/".$image_name_mem);
    if ($mem_res[0] > ($genmem_settings['mem_width'] - 2*($genmem_settings['dem_padding_side']+$genmem_settings['dem_border']+$genmem_settings['dem_after_border'])) ||
        $mem_res[1] > ($genmem_settings['mem_height'] - $genmem_settings['dem_padding_bottom'] - $genmem_settings['dem_padding_top'] - 2*($genmem_settings['dem_border']+$genmem_settings['dem_after_border']))) {
        $dem_preimage_width = $genmem_settings['mem_width'] - 2*($genmem_settings['dem_padding_side']+$genmem_settings['dem_border']+$genmem_settings['dem_after_border']);
        $dem_preimage_height = $genmem_settings['mem_height'] - $genmem_settings['dem_after_bottom'] - $genmem_settings['dem_padding_top'] - 2*($genmem_settings['dem_border']+$genmem_settings['dem_after_border']);
    } else {
        $dem_preimage_width = $mem_res[0];
        $dem_preimage_height = $mem_res[1];
    }
    $dem_finalimage_width = $dem_preimage_width + 2*($genmem_settings['dem_padding_side']+$genmem_settings['dem_border']+$genmem_settings['dem_after_border']);
    $dem_finalimage_height = $dem_preimage_height + $genmem_settings['dem_padding_bottom'] + $genmem_settings['dem_padding_top'] + 2*($genmem_settings['dem_border']+$genmem_settings['dem_after_border']);
    $dem_file = imagecreatetruecolor($dem_finalimage_width, $dem_finalimage_height);
    imagefill($dem_file,0,0,imagecolorallocate($dem_file, 0, 0, 0));
    for ($i=1;$i<=$genmem_settings['dem_border'];$i++) {
        imagerectangle($dem_file,$genmem_settings['dem_padding_side']+$i,$genmem_settings['dem_padding_top']+$i,
        $genmem_settings['dem_padding_side']+2*($genmem_settings['dem_border']+$genmem_settings['dem_after_border'])+$dem_preimage_width-$i,
        $genmem_settings['dem_padding_top']+2*($genmem_settings['dem_border']+$genmem_settings['dem_after_border'])+$dem_preimage_height-$i,imagecolorallocate($dem_file, 255, 255, 255));
    }
    if ($filetype == 1) { $origimage = imagecreatefromgif(INFUSIONS."al_genmem/asset/generators/mems/".$image_name_mem); }
    elseif ($filetype == 2) { $origimage = imagecreatefromjpeg(INFUSIONS."al_genmem/asset/generators/mems/".$image_name_mem); }
    elseif ($filetype == 3) { $origimage = imagecreatefrompng(INFUSIONS."al_genmem/asset/generators/mems/".$image_name_mem); }
    imagecopyresized ($dem_file, $origimage, $genmem_settings['dem_padding_side']+$genmem_settings['dem_border']+$genmem_settings['dem_after_border']+1,
        $genmem_settings['dem_padding_top']+$genmem_settings['dem_border']+$genmem_settings['dem_after_border']+1,
        0, 0, $dem_preimage_width, $dem_preimage_height, $mem_res[0], $mem_res[1]);
    imagejpeg($dem_file,INFUSIONS."al_genmem/asset/generators/dems/".$image_name.".jpg",100);
    imagedestroy($dem_file);
    imagedestroy($origimage);
    return array("mem"=>$image_name_mem,"dem"=>$image_name_dem);
}

function CenterImageString($image, $gen_type, $image_width, $image_height, $string, $font_size=32, $color, $font, $text_type) {

    global $genmem_settings;

    $string = iconv("windows-1251","utf-8",$string);
    $fonts = array(1=>"impact",2=>"arial",3=>"tahoma",4=>"times",5=>"verdana");
    $fontfile = INFUSIONS."al_genmem/asset/fonts/".$fonts[$font].".ttf";
    $textbox_h = imagettfbbox($font_size, 0, $fontfile, "l");
    $textbox_w = imagettfbbox($font_size, 0, $fontfile, $string);
    $textwidth = $textbox_w[2]; $textheight = $textbox_h[5];
    $black = imagecolorallocate($image,0,0,0);
    $x = ($image_width - $textwidth)/2;
    if ($gen_type == 1) {

        if ($text_type == 1) {
            $y = -1.5*$textheight;
            //$y = 55;
        } else if ($text_type == 2) {
            $y = $image_height + $textheight;
            //$y = $image_height - 30;
        }

    } else if ($gen_type == 2) {
        if ($text_type == 1) {
            //$y = ($image_height-$genmem_settings['dem_padding_bottom']) - 1.4*$textheight;
            $y = ($image_height-$genmem_settings['dem_padding_bottom']) + 55;
        } else if ($text_type == 2) {
            //$y = ($image_height-$genmem_settings['dem_padding_bottom']) - 2.8*$textheight;
            $y = ($image_height-$genmem_settings['dem_padding_bottom']) + 110;
        }
    }
    imagettftext($image, $font_size, 0, $x+1, $y, $black, $fontfile, $string);
    imagettftext($image, $font_size, 0, $x-1, $y, $black, $fontfile, $string);
    imagettftext($image, $font_size, 0, $x, $y+1, $black, $fontfile, $string);
    imagettftext($image, $font_size, 0, $x, $y-1, $black, $fontfile, $string);
    imagettftext($image, $font_size, 0, $x, $y, $color, $fontfile, $string);


}

function html2rgb($color)
{
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
            $color[2].$color[3],
            $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);
}


//  Нарисовать вод. знак
function ImageWM($image, $width, $height, $string, $color) {
    $fonts = './LSN-R.ttf';
    $wmk=iconv("windows-1251", "UTF-8", $string);
    imagettftext($image, 10, 0, $width-90, $height-5, $color, $fonts, $wmk);
}

function imagettftextoutline(&$im,$size,$angle,$x,$y,$col,$outlinecol,$fontfile,$text,$width){
    for($xc=$x-abs($width);$xc<=$x+abs($width);$xc++){
        for($yc=$y-abs($width);$yc<=$y+abs($width);$yc++){
            $text1 = imagettftext($im,$size,$angle,$xc,$yc,-$outlinecol,$fontfile,$text);
        }
    }
    $text2 = imagettftext($im,$size,$angle,$x,$y,-$col,$fontfile,$text);
}

?>