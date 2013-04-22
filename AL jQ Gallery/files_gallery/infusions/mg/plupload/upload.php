<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| AL jQ gallery 1.7
| Filename: upload.php
| Author: Rush
| http://fusion.annetlab.tk
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/ 

	// HTTP headers for no cache etc
	header('Content-type: text/plain; charset=UTF-8');
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

require_once "../../../maincore.php";
require_once INFUSIONS."mg/infusion_db.php";
require_once INFUSIONS."mg/functions.php";
require_once INCLUDES."photo_functions_include.php";
global $userdata;
	// 5 minutes execution time
	@set_time_limit(5 * 60);

	$photo_name = str_replace(" ", "_", strtolower(substr($_FILES['file']['name'], 0, strrpos($_FILES['file']['name'], "."))));
    $photo_ext = strtolower(strrchr($_FILES['file']['name'],"."));
    $targetPath = IMAGES."mg_photos/";
    $new_name = generateName();
    while (file_exists($targetPath.$new_name.$photo_ext)) {
        $new_name = generateName();
    }
    $targetName = $new_name.$photo_ext;
    $tempFile = $_FILES['file']['tmp_name'];
	$targetFile =  $targetPath.$targetName;
    
    move_uploaded_file($tempFile,$targetFile);
    $origin = $targetName;
    
    $size = @getimagesize($targetPath.$origin);

 if ($size[0] > $mg_settings['photo_width'] || $size[1] > $mg_settings['photo_height']) {
        $t1 = imageExists($targetPath, $new_name."_t1".$photo_ext);
        createthumbnail($size[2], $targetPath.$origin, $targetPath.$t1, $mg_settings['photo_width'], $mg_settings['photo_height']);
    } else {
        $t1 = $origin;
    } 

    if ($size[0] > $mg_settings['thumb_width'] || $size[1] > $mg_settings['thumb_height']) {
        $t2 = imageExists($targetPath, $new_name."_t2".$photo_ext);
        createthumbnail($size[2], $targetPath.$origin, $targetPath.$t2, $mg_settings['thumb_width'], $mg_settings['thumb_height']);
    } else {
        $t2 = $origin;
    }

if ($mg_settings['upload_original'] == 0) {
$origin = "";
}
				
    
    $result = dbquery("INSERT INTO ".DB_MG_PHOTOS." (photo_title, photo_desc, photo_date, photo_user, photo_album, photo_file, photo_t1, photo_t2) VALUES ('', '', '".time()."', '".$userdata['user_id']."', '".$_REQUEST['album_id']."', '".$origin."', '".$t1."', '".$t2."')");

	// Return JSON-RPC response
	die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
?>
