<?php
require_once "../../../maincore.php";
require_once INFUSIONS."al_genmem/infusion_db.php";
require_once INFUSIONS."al_genmem/includes/image_functions.php";
$gen_id = isset($_GET['image']) && isnum($_GET['image']) ? $_GET['image'] : die("FFUUU");
$result = dbquery("SELECT * FROM ".DB_GEM_GENERATORS." WHERE gen_id='".$_GET['image']."'");
if (dbrows($result)) {
    $meminfo = dbarray($result);
    $gen_type = isset($_GET['gen_type']) && isnum($_GET['gen_type']) && ($_GET['gen_type'] == 1 || $_GET['gen_type'] == 2) ? $_GET['gen_type'] : 1;
    $filepath = INFUSIONS."al_genmem/asset/generators/".($gen_type == 1 ? "mems" : "dems")."/".($gen_type == 1 ? $meminfo['gen_mem_image'] : $meminfo['gen_dem_image']);
    if (file_exists($filepath)) {
        $image_ext = strtolower(strrchr(($gen_type == 1 ? $meminfo['gen_mem_image'] : $meminfo['gen_dem_image']),"."));
        $image_res = @getimagesize($filepath);

        if ($image_ext == ".gif") { $filetype = 1;
        } elseif ($image_ext == ".jpg") { $filetype = 2;
        } elseif ($image_ext == ".png") { $filetype = 3;
        }
        if ($filetype == 1) { $im = imagecreatefromgif($filepath); }
        elseif ($filetype == 2) { $im = imagecreatefromjpeg($filepath); }
        elseif ($filetype == 3) { $im = imagecreatefrompng($filepath); }
    } else {
        die("FFUUUUUUU");
    }

    $text1 = isset($_GET['text1']) && $_GET['text1'] != '' ? trim(stripinput($_GET['text1'])) : '';
    $text2 = isset($_GET['text2']) && $_GET['text2'] != '' ? trim(stripinput($_GET['text2'])) : '';
    $size1 = isset($_GET['size1']) && isnum($_GET['size1']) && in_array(intval($_GET['size1']),array(12,16,24,32,48)) ? intval($_GET['size1']) : 32;
    $size2 = isset($_GET['size2']) && isnum($_GET['size2']) && in_array(intval($_GET['size2']),array(12,16,24,32,48)) ? intval($_GET['size2']) : 32;
    $font1 = isset($_GET['font1']) && isnum($_GET['font1']) && in_array(intval($_GET['font1']),array(1,2,3,4,5)) ? intval($_GET['font1']) : 1;
    $font2 = isset($_GET['font2']) && isnum($_GET['font2']) && in_array(intval($_GET['font2']),array(1,2,3,4,5)) ? intval($_GET['font2']) : 1;
    $color1_array = html2rgb($_GET['color1']);
    $color1 = imagecolorallocate($im, $color1_array[0],$color1_array[1],$color1_array[2]);
    $color2_array = html2rgb($_GET['color2']);
    $color2 = imagecolorallocate($im, $color2_array[0],$color2_array[1],$color2_array[2]);
    if ($text1 != '') {
        CenterImageString($im, $gen_type, $image_res[0], $image_res[1], $text1, $size1, $color1, $font1, 1);
    }
    if ($text2 != '') {
        CenterImageString($im, $gen_type, $image_res[0], $image_res[1], $text2, $size2, $color2, $font2, 2);
    }





    if (isset($_GET['action']) && $_GET['action'] == "save") {
        $get_id = dbquery("SELECT mem_id FROM ".DB_GEM_MEMS." ORDER BY mem_id DESC LIMIT 1");
        if (dbrows($get_id)) {
            $get_id = dbarray($get_id);
            $id = $get_id['mem_id']+1;
        } else {
            $id = 1;
        }
        imagejpeg($im,INFUSIONS."al_genmem/asset/images/".$id.".jpg",100);

        $result = dbquery("INSERT INTO ".DB_GEM_MEMS." (mem_type,mem_text1,mem_text2,mem_gen_id,mem_datestamp,mem_image,mem_rating,mem_voters,mem_views) VALUES
        ('".$gen_type."','".$text1."','".$text2."','".$_GET['image']."','".time()."','".$id.".jpg','0','','0')");

        print(json_encode(array('result'=>'success','id'=>mysql_insert_id())));


    } else {
        header('Content-Type: image/jpeg');
        imagejpeg($im,null,100);
    }
    imagedestroy($im);
} else {
    die("FFUUUUU");
}

?>