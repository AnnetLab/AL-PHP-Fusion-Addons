<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| AL jQ gallery 1.7
| Filename: mg_gallery.php
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
require_once "maincore.php";
require_once THEMES."templates/header.php";
require_once INFUSIONS."mg/infusion_db.php";
require_once INFUSIONS."mg/functions.php";
if (!iMEMBER) {redirect(BASEDIR."index.php");}

 if (file_exists(INFUSIONS."mg/locale/".$settings['locale'].".php")) {
require_once INFUSIONS."mg/locale/".$settings['locale'].".php"; 
} else {
require_once INFUSIONS."mg/locale/English.php"; 
} 

if (isset($_GET['action']) && $_GET['action'] == "delete_album" && isset($_GET['id']) && isnum($_GET['id'])) {
    
    echo $locale['mg11'];
    $result = dbquery("SELECT * FROM ".DB_MG_PHOTOS." WHERE photo_album='".$_GET['id']."'");
    while($data=dbarray($result)) {
        if (file_exists(IMAGES."mg_photos/".$data['photo_file'])) {
unlink(IMAGES."mg_photos/".$data['photo_file']);
}
 if (file_exists(IMAGES."mg_photos/".$data['photo_t1'])) { 
        unlink(IMAGES."mg_photos/".$data['photo_t1']);
}
 if (file_exists(IMAGES."mg_photos/".$data['photo_t2'])) { 
        unlink(IMAGES."mg_photos/".$data['photo_t2']);
}
        $delete = dbquery("DELETE FROM ".DB_MG_PHOTOS." WHERE photo_id='".$data['photo_id']."'");
    }
    $delete_album = dbquery("DELETE FROM ".DB_MG_ALBUMS." WHERE album_id='".$_GET['id']."'");
    redirect(BASEDIR."gallery.php");
}

if (isset($_POST['save'])) {
    $title = trim(stripinput($_POST['title']));
    $desc = trim(stripinput($_POST['desc']));
    $result = dbquery("INSERT INTO ".DB_MG_ALBUMS." (album_title, album_desc, album_user, album_date) VALUES ('".$title."', '".$desc."', '".$userdata['user_id']."', '".time()."')");
    $id = mysql_insert_id();
    if ($result) {
        redirect(BASEDIR."mg_gallery.php?action=upload&id=".$id);
    } else {
        echo "<div>Error!</div>";
    }
    
}
if (isset($_POST['update'])) {
    $title = trim(stripinput($_POST['title']));
    $desc = trim(stripinput($_POST['desc']));
    $id = $_POST['album_id'];
    $result = dbquery("UPDATE ".DB_MG_ALBUMS." SET album_title='".$title."', album_desc='".$desc."' WHERE album_id='".$id."'");
    
    if ($result) {
        if (isset($_POST['edit_photos']) && $_POST['edit_photos'] != "") {
            redirect(BASEDIR."mg_gallery.php?action=edit_photos&id=".$id);
        } else {
            redirect(BASEDIR."gallery.php?action=album&id=".$id);
            //print_r($_POST);
        }
    }
    
}
if (isset($_POST['save_photos'])) {
    
    //print_r($_POST);
    $all_id = unserialize(stripslashes($_POST['all_id']));
    //print_r($all_id);    
    //print_r($all_id);
    foreach ($all_id as $id) {
        if (isset($_POST['delete_'.$id]) && $_POST['delete_'.$id] == $id) {
            $data = dbarray(dbquery("SELECT photo_file, photo_t1, photo_t2 FROM ".DB_MG_PHOTOS." WHERE photo_id='".$id."'"));
            $delete = dbquery("DELETE FROM ".DB_MG_PHOTOS." WHERE photo_id='".$id."'");
 if (file_exists(IMAGES."mg_photos/".$data['photo_file'])) { 
            unlink(IMAGES."mg_photos".$data['photo_file']);
}
 if (file_exists(IMAGES."mg_photos/".$data['photo_t1'])) { 
            unlink(IMAGES."mg_photos".$data['photo_t1']);
}
 if (file_exists(IMAGES."mg_photos/".$data['photo_t2'])) { 
            unlink(IMAGES."mg_photos".$data['photo_t2']);
}
        } else {
            $update = dbquery("UPDATE ".DB_MG_PHOTOS." SET photo_title='".$_POST['title_'.$id]."', photo_desc='".$_POST['desc_'.$id]."' WHERE photo_id='".$id."'");
        }
    }
    if (isset($_POST['cover'])) {
        $update = dbquery("UPDATE ".DB_MG_ALBUMS." SET album_cover='".$_POST['cover']."' WHERE album_id='".$_POST['album_id']."'");
    }
    redirect(BASEDIR."gallery.php?action=album&id=".$_POST['album_id']);
}

add_to_head("<style>
span.filename {display:none;}
span.action {display:none;}
</style>");

if (isset($_GET['action']) && $_GET['action'] == "create") {
    opentable($locale['mg12']);
    add_to_title($locale['mg13']);
    if ($mg_settings['user_albums'] == "1" || (iADMIN && checkrights("MG"))) {
            echo "<form action='".BASEDIR."mg_gallery.php' method='post'>
            <table width='90%'>
            <tr><td class='tbl2' width='200'><strong>".$locale['mg14']."</strong></td><td class='tbl2'><input type='text' class='textbox' style='width:305px;' name='title' /></td></tr>
            <tr><td class='tbl2' width='200'><strong>".$locale['mg15']."</strong></td><td class='tbl2'><textarea class='textbox' rows='5' cols='36' name='desc'></textarea></td></tr>
            <tr><td colspan='2' class='tbl2' align='center'><br /><input type='submit' class='button' name='save' value='".$locale['mg16']."' />
            </td></tr>
            </table>
            </form>";
            
    } else {
        echo "<div>".$locale['mg17']."</div>";
    }
    closetable();
}

if (isset($_GET['action']) && $_GET['action'] == "upload" && isset($_GET['id']) && isnum($_GET['id'])) {
    opentable($locale['mg18']);
    add_to_title($locale['mg19']);
    $album = dbarray(dbquery("SELECT * FROM ".DB_MG_ALBUMS." WHERE album_id='".$_GET['id']."'"));
    if ($mg_settings['user_albums'] == "1" || (iADMIN && checkrights("MG"))) {
        if ($userdata['user_id'] == $album['album_user']) {
         //�������� ��������� �������
            $maxsize = round($mg_settings['max_photo_size']/64, 2)."mb";
            add_to_head("<link href='".INFUSIONS."mg/plupload/css/plupload.queue.css' type='text/css' rel='stylesheet' />");
            add_to_head("<script type='text/javascript' src='".INFUSIONS."mg/plupload/plupload.full.min.js'></script>");
            add_to_head("<script type='text/javascript' src='".INFUSIONS."mg/plupload/jquery.plupload.queue.min.js'></script>");
            //add_to_head("<script type='text/javascript' src='".INFUSIONS."mg/plupload/ru.js'></script>");
if ($mg_settings['upload_original'] == 0) {
            add_to_head("<script type='text/javascript'>
            $(function() {
                var maxsize = '".$maxsize."';
                var w = '".$mg_settings['photo_width']."';
                var h = '".$mg_settings['photo_height']."';
                var basedir = '".BASEDIR."';
                var albumID = '".$_GET['id']."';
                $('#photo_upload').pluploadQueue({
		      		runtimes : 'flash',
                    url : 'infusions/mg/plupload/upload.php',
                    max_file_size : maxsize,
                    unique_names : true,
                    preinit: attachCallbacks,
                    filters : [
                        {title : 'Image files', extensions : 'jpg,gif,png,jpeg'}
                    ],
                    multipart_params : {album_id : albumID},
                    resize : {width : w, height : h, quality : 90},
                    flash_swf_url : 'infusions/mg/plupload/plupload.flash.swf'
                });
                function attachCallbacks(Uploader) {
                    Uploader.bind('FileUploaded', function(Up, File, Response) {
                    if((Uploader.total.uploaded + 1) == Uploader.files.length) {
                        window.location = basedir + 'mg_gallery.php?action=edit_photos&id=' + albumID;
                    }
                    });
                }
                
            });    
                
            </script>");
} else {
 add_to_head("<script type='text/javascript'>
            $(function() {
                var maxsize = '".$maxsize."';
                var w = '".$mg_settings['photo_width']."';
                var h = '".$mg_settings['photo_height']."';
                var basedir = '".BASEDIR."';
                var albumID = '".$_GET['id']."';
                $('#photo_upload').pluploadQueue({
		      		runtimes : 'flash',
                    url : 'infusions/mg/plupload/upload.php',
                    max_file_size : maxsize,
                    unique_names : true,
                    preinit: attachCallbacks,
                    filters : [
                        {title : 'Image files', extensions : 'jpg,gif,png,jpeg'}
                    ],
                    multipart_params : {album_id : albumID},
                    flash_swf_url : 'infusions/mg/plupload/plupload.flash.swf'
                });
                function attachCallbacks(Uploader) {
                    Uploader.bind('FileUploaded', function(Up, File, Response) {
                    if((Uploader.total.uploaded + 1) == Uploader.files.length) {
                        window.location = basedir + 'mg_gallery.php?action=edit_photos&id=' + albumID;
                    }
                    });
                }
                
            });    
                
            </script>"); 
}
            echo "<form action='".BASEDIR."mg_gallery.php' method='post'>
            <table width='90%'>
            <tr><td colspan='2' class='tbl2'><strong>".$locale['mg20']."</strong><br /><br />
            <div id='photo_upload' style='width: 500px; height: 330px;'>You browser doesn't have Flash installed.</div>
            </td></tr>
            </table>
            </form>";
        } else {
            echo "<div>".$locale['mg21']."</div>";
        }
    } else {
        echo "<div>".$locale['mg17']."</div>";
    }
    closetable();
}

if (isset($_GET['action']) && $_GET['action'] == "edit_photos" && isset($_GET['id']) && isnum($_GET['id'])) {
    opentable($locale['mg22']);
    add_to_title(" - ".$locale['mg22']);
    if ($mg_settings['user_albums'] == "1" || (iADMIN && checkrights("MG"))) {
         //�������� ��������� �������
            $album = dbarray(dbquery("SELECT * FROM ".DB_MG_ALBUMS." WHERE album_id='".$_GET['id']."'"));
            if ($userdata['user_id'] == $album['album_user'] || (iADMIN && checkrights("MG"))) {
            echo "<form action='".BASEDIR."mg_gallery.php' method='post'>
            <table width='90%'>";
            echo "<tr><td colspan='2' class='tbl2'><a href='".BASEDIR."mg_gallery.php?action=upload&id=".$_GET['id']."'>".$locale['mg23']."</a></td></tr>";
            $result = dbquery("SELECT * FROM ".DB_MG_PHOTOS." WHERE photo_album='".$_GET['id']."' ORDER BY photo_id ASC");
            $all_id = array();
            $album = dbarray(dbquery("SELECT album_cover FROM ".DB_MG_ALBUMS." WHERE album_id='".$_GET['id']."'"));
            while ($data=dbarray($result)) {
                echo "<tr><td class='tbl2' width='200' align='center'><label><img src='".IMAGES."mg_photos/".$data['photo_t2']."' border='0' /><br /><input type='radio' class='textbox' name='cover' value='".$data['photo_t2']."'".($album['album_cover'] == $data['photo_t2'] ? " checked='checked'" : "")." />".$locale['mg24']."</label><br /><label><input type='checkbox' class='textbox' name='delete_".$data['photo_id']."' value='".$data['photo_id']."' />".$locale['mg25']."</label><br /></td>
                <td class='tbl2'>
                ".$locale['mg26']."&nbsp;&nbsp;&nbsp;<input type='text' class='textbox' name='title_".$data['photo_id']."' value='".$data['photo_title']."' style='width:250px;' />
                ".$locale['mg27']."&nbsp;&nbsp;&nbsp;<textarea class='textbox' name='desc_".$data['photo_id']."' rows='4' cols='29'>".$data['photo_desc']."</textarea>
                </td></tr>";
                $all_id[] = $data['photo_id'];
            }
            echo "<tr><td colspan='2' class='tbl2'><input type='hidden' name='all_id' value='".serialize($all_id)."' /><input type='hidden' name='album_id' value='".$_GET['id']."' /><input type='submit' class='button' name='save_photos' value='".$locale['mg28']."' /></td></tr>";
            echo "</table>
            </form>";
         } else {
            echo "<div>Access deny</div>";
        }
    } else {
        echo "<div>".$locale['mg17']."</div>";
    }
    closetable();
}

if (isset($_GET['action']) && $_GET['action'] == "edit_album" && isset($_GET['id']) && isnum($_GET['id'])) {
    
    $data = dbarray(dbquery("SELECT * FROM ".DB_MG_ALBUMS." WHERE album_id='".$_GET['id']."'"));
    if ((iMEMBER && $data['album_user'] == $userdata['user_id']) || (iADMIN && checkrights("MG"))) {
        opentable($locale['mg29'].$data['album_title']);
        
            echo "<form action='".BASEDIR."mg_gallery.php' method='post'>
            <table width='90%'>
            <tr><td class='tbl2' width='200'><strong>".$locale['mg14']."</strong></td><td class='tbl2'><input type='text' class='textbox' style='width:305px;' name='title' value='".$data['album_title']."' /></td></tr>
            <tr><td class='tbl2' width='200'><strong>".$locale['mg15']."</strong></td><td class='tbl2'><textarea class='textbox' rows='5' cols='36' name='desc'>".$data['album_desc']."</textarea></td></tr>
            <tr><td colspan='2' class='tbl2' align='center'><br /><input type='hidden' name='album_id' value='".$_GET['id']."' /><label><input type='checkbox' name='edit_photos' class='textbox' />".$locale['mg30']."</label><br /><input type='submit' class='button' name='update' value='".$locale['mg31']."' />
            </td></tr>
            </table>
            </form>";
        
        closetable();
    } else {
        echo "error";
    }
}
 echo "<div style='text-align:center;width:100%;'>AL jQ gallery <a href='http://fusion.annetlab.tk'>Fusion @ AnnetLab</a> &copy; 2011-2012</div>"; 
require_once THEMES."templates/footer.php";
?>
