<?php
require_once "../../../maincore.php";

$dir = INFUSIONS."al_blog/asset/uploaded_images/";
$url_dir = $settings['siteurl'].'infusions/al_blog/asset/uploaded_images/';

if (isset($_FILES)) {
    require_once INCLUDES."infusions_include.php";
    $image = upload_image(
        "file", "", $dir, $settings['news_photo_max_w'], $settings['news_photo_max_h'],
        $settings['news_photo_max_b'], false, true, true,
        0, $dir, "_t1", $settings['news_thumb_w'], $settings['news_thumb_h'],
        0, $dir, "_t2", $settings['news_photo_w'], $settings['news_photo_h']
    );
//    var_dump($image);

    $json_data = file_get_contents($dir."images.json");
    $json = json_decode($json_data,true);
    $json[] = array(
        'thumb' => $url_dir.$image['thumb1_name'],
        'image' => $url_dir.$image['thumb2_name']
    );
    $json_data = json_encode($json);
    //var_dump($json_data);
    $fw = fopen($dir."images.json","w");
    fwrite($fw,$json_data);
    fclose($fw);

	$array = array(
		'filelink' => $url_dir.$image['thumb2_name'],
		'original' => $url_dir.$image['image_name'],
	);
	
	echo stripslashes(json_encode($array));


}
 
?>
