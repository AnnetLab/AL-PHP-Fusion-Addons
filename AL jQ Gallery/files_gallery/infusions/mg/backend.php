<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| AL jQ gallery 1.7
| Filename: backend.php
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
require_once "../../maincore.php";
require_once INFUSIONS."mg/infusion_db.php";

if (isset($_POST['action']) && $_POST['action'] == "get_photo") {
    
    $data = dbarray(dbquery("SELECT photo_title, photo_desc, photo_t1, photo_file FROM ".DB_MG_PHOTOS." WHERE photo_id='".$_POST['id']."'"));
    $photo = array('title'=>iconv("windows-1251","UTF-8",$data['photo_title']), 'desc'=>iconv("windows-1251","UTF-8",$data['photo_desc']), 't1'=>$data['photo_t1'], 'original'=>$data['photo_file']);
    $photos = array();
    $xz = dbquery("SELECT photo_id FROM ".DB_MG_PHOTOS." WHERE photo_album='".$_POST['album']."' ORDER BY photo_id ASC");
    while ($xz2=dbarray($xz)) {
        $photos[] = $xz2['photo_id'];
    }
    $count = count($photos);
    for ($i=0;$i<=$count-1;$i++) {
        if ($photos[$i] == $_POST['id']) {
            $num = $count;
            $cur = $i+1;
            $next = $i==$count-1 ? $photos['0'] : $photos[$i+1];
            $prev = $i==0 ? $photos[$num-1] : $photos[$i-1];
        }
    }
    $buttons = array('next'=>$next ,'prev'=>$prev, 'num'=>$num, 'cur'=>$cur);
    
$result = array('type'=>'success', 'photo'=>$photo, 'buttons'=>$buttons);
print json_encode($result);
}










?>
