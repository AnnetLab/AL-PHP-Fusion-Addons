<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| AL jQ gallery 1.7
| Filename: gallery.php
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
require_once INFUSIONS."mg/infusion_db.php";
require_once INFUSIONS."mg/functions.php";
require_once THEMES."templates/header.php";

if (file_exists(INFUSIONS."mg/locale/".$settings['locale'].".php")) {
require_once INFUSIONS."mg/locale/".$settings['locale'].".php"; 
} else {
require_once INFUSIONS."mg/locale/English.php"; 
}



if (isset($_GET['action']) && $_GET['action'] == "album" && isset($_GET['id'])) {
    
    $album = dbarray(dbquery("SELECT * FROM ".DB_MG_ALBUMS." WHERE album_id='".$_GET['id']."'"));
    $avtor = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$album['album_user']."'"));
    add_to_title(" -  ".$album['album_title']);
    opentable($album['album_title']);
    echo "<table width='100%' border='0'>";
    echo "<tr><td colspan='4' class='tbl2'><a href='".BASEDIR."gallery.php'>".$locale['mg01']."</a>&nbsp;&rarr;&nbsp;<a href='".BASEDIR."gallery.php?action=user&id=".$album['album_user']."'>".$locale['mg2'].$avtor['user_name']."</a>&nbsp;&rarr;&nbsp;".$album['album_title']."</td></tr>";
    echo "<tr><td colspan='4' class='tbl2'>".$album['album_desc']."<span style='float:right;'><a href='".BASEDIR."mg_gallery.php?action=create'>".$locale['mg12']."</a>&nbsp".($album['album_user'] == $userdata['user_id'] ? "<a href='".BASEDIR."mg_gallery.php?action=upload&id=".$album['album_id']."'>".$locale['mg23']."</a>" : "")."</span></td></tr><tr>";
    $i = 0;
    $photos = dbcount("(photo_id)", DB_MG_PHOTOS, "photo_album='".$_GET['id']."'");
    if (!isset($_GET['p']) || !isnum($_GET['p'])) { $_GET['p'] = 0; }
    $result = dbquery("SELECT * FROM ".DB_MG_PHOTOS." WHERE photo_album='".$_GET['id']."' ORDER BY photo_id ASC LIMIT ".$_GET['p'].",20");
        while ($data = dbarray($result)) {
            if ($i % 4 == 0 && $i != 0) {echo "</tr><tr>";}
            echo "<td width='25%' class='tbl2' align='center' valign='middle'><a href='".BASEDIR."gallery.php?action=photo&id=".$_GET['id']."#".$data['photo_id']."'><img src='".IMAGES."mg_photos/".$data['photo_t2']."' border='0' style='padding:5px;' /></a></td>";
            $i++;
        }
    echo "</tr>";
    if ($photos > 20) { echo "<tr><td colspan='4'><div align='center' style='margin-top:5px;'>".makepagenav_($_GET['p'], 20, $photos, 3, BASEDIR."gallery.php?action=album&id=".$_GET['id']."&")."</div></td>"; }
    
    echo "</tr></table>";
    closetable();
    
} elseif (isset($_GET['action']) && $_GET['action'] == "user" && isset($_GET['id'])) {
    
    
    $avtor = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$_GET['id']."'"));
    add_to_title($locale['mg3'].$avtor['user_name']);
    opentable($locale['mg2'].$avtor['user_name']);
    $albums = dbcount("(album_id)", DB_MG_ALBUMS, "album_user='".$_GET['id']."'");
    if (!isset($_GET['p']) || !isnum($_GET['p'])) { $_GET['p'] = 0; }
    $result = dbquery("SELECT * FROM ".DB_MG_ALBUMS." WHERE album_user='".$_GET['id']."' ORDER BY album_id DESC LIMIT ".$_GET['p'].",10");
    echo "<table width='100%' border='0'>";
    echo "<tr><td colspan='2' class='tbl2'><a href='".BASEDIR."gallery.php'>".$locale['mg01']."</a>&nbsp;&rarr;&nbsp;".$locale['mg2']."<a href='".BASEDIR."profile.php?lookup=".$_GET['id']."'>".$avtor['user_name']."</a></td></tr>";
    echo "<tr><td colspan='2' class='tbl2'>".$locale['mg4'].$albums." <span style='float:right;'><a href='".BASEDIR."mg_gallery.php?action=create'>".$locale['mg12']."</a></span> </td></tr><tr>";
    $i=0;
    while($data=dbarray($result)) {
        if ($i % 2 == 0 && $i != 0) {echo "</tr><tr>";}
        $num_photos = dbcount("(photo_id)",DB_MG_PHOTOS,"photo_album='".$data['album_id']."'");
        echo "<td class='tbl2' width=50%' valign='top'><a href='".BASEDIR."gallery.php?action=album&id=".$data['album_id']."'><img src='".IMAGES."mg_photos/".$data['album_cover']."' border='0' style='margin:0 10px 10px 0;float:left;' /></a><a href='".BASEDIR."gallery.php?action=album&id=".$data['album_id']."'><strong>".$data['album_title']."</strong></a><br />".$num_photos.$locale['mg5']."<br />".$locale['mg6']."<a href='".BASEDIR."profile.php?lookup=".$data['album_user']."'>".$avtor['user_name']."</a><br />".((iMEMBER && $data['album_user'] == $userdata['user_id']) || (iADMIN && checkrights("MG")) ? "<a href='".BASEDIR."mg_gallery.php?action=edit_album&id=".$data['album_id']."'>edit</a> / <a href='".BASEDIR."mg_gallery.php?action=delete_album&id=".$data['album_id']."' onclick='return confirm(\"Confirm delete\");'>delete</a>" : "")."</td>";
        $i++;
    }
    
    
    echo "</tr><tr><td colspan=2' align='center' class='tbl2'>";
    if ($albums > 10) { echo "<div align='center' style='margin-top:5px;'>".makepagenav($_GET['p'], 10, $albums, 3, BASEDIR."gallery.php?action=user&id=".$_GET['id']."&")."</div>"; }
    echo "</td></tr>";
    echo "<tr><td colspan='2' style='height:20px;'>&nbsp;</td></tr>";
    echo "<tr><td colspan='2' class='tbl2'>".$locale['mg7'];
    $result = dbquery("SELECT album_user, album_id FROM ".DB_MG_ALBUMS." ORDER BY RAND()");
    $rand_user = array();
    $rand_name = array();
    $j = 1;
    //while ($j != 20 || $j != $albums) {
        while ($data = dbarray($result)) {
        if (!in_array($data['album_user'],$rand_user) && $j != 20) {
            $rand_user[$j] = $data['album_user'];
            $name = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$data['album_user']."'"));
            $rand_name[$j] = $name['user_name'];
            $j++;
        }
        }
    //}
    for ($i=1;$i<=count($rand_user);$i++) {
        echo " <a href='".BASEDIR."gallery.php?action=user&id=".$rand_user[$i]."'> ".$rand_name[$i]."</a>".($i<count($rand_user) ? "," : "");
    }
    
    echo "</td></tr>";
    echo "</table>";
    closetable();
    
    
} elseif (isset($_GET['action']) && $_GET['action'] == "photo" && isset($_GET['id'])) {
    
    
    $album = dbarray(dbquery("SELECT * FROM ".DB_MG_ALBUMS." WHERE album_id='".$_GET['id']."'"));
    $avtor = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$album['album_user']."'"));
    add_to_title(" -  ".$album['album_title']);
    opentable($album['album_title']);
    echo "<table width='100%' border='0'>";
    echo "<tr><td class='tbl2'><a href='".BASEDIR."gallery.php'>".$locale['mg01']."</a>&nbsp;&rarr;&nbsp;<a href='".BASEDIR."gallery.php?action=user&id=".$album['album_user']."'>".$locale['mg2'].$avtor['user_name']."</a>&nbsp;&rarr;&nbsp;<a href='".BASEDIR."gallery.php?action=album&id=".$_GET['id']."'>".$album['album_title']."</a></td></tr>";
    echo "<tr><td class='tbl2'><div id='mg_count' style='height:20px;float:left;margin-left:30px;'></div><div id='mg_load' style='float:right;margin-right:30px;'><img src='".INFUSIONS."mg/images/load.gif' border='0' /></div></td></tr><tr>";
    
    add_to_head("<style>
    #mg_photo_wrapper {
        width: 660px;
        margin: 10px auto;
        position: relative;
    }
    #mg_photo {
        width: ".$mg_settings['photo_width']."px;
        text-align: center;
        margin: 0 auto;
    }
    #mg_prev, #mg_next {
        display:block;
        position:absolute;
        left:0px;
        top:0px;
        z-index:1000;
        width:19px;
        height:15px;
    }
    #mg_prev a, #mg_next a{
        display:block;
        position:relative;
        width:19px;
        height:15px;
        background:url(".INFUSIONS."mg/images/roktabs1.png) no-repeat 0px -12px;
    }
    #mg_next a {background:url(".INFUSIONS."mg/images/roktabs1.png) no-repeat -23px -12px;}
    #mg_next {left:640px;}
    #mg_prev, #mg_next{margin:0;padding:0;display:block;overflow:hidden;text-indent:-8000px;}
    #mg_load {
        opcity: 0;
    }
    </style>");
    
    add_to_head("<script type='text/javascript' src='".INFUSIONS."mg/gallery.js'></script>");
    add_to_head("<script type='text/javascript'>
            var hashBuffer = document.location.hash;
            var cur_hash = hashBuffer.substring(1);
            var basedir = '".BASEDIR."';
            var album = '".$_GET['id']."';
            var cidas = cur_hash;
			var locale1 = '".$locale['mg44']."';
			var locale2 = '".$locale['mg45']."';
    </script>");
    
    echo "<tr><td class='tbl2'>
        <div id='mg_photo_wrapper'>
            <div id='mg_photo'></div>
            <span id='mg_prev'><a href=''>prev</a></span>
            <span id='mg_next'><a href=''>next</a></span>
            <br /><div id='mg_original' style='margin-left:30px;'><a id='mg_zoom' href=''><img src='".INFUSIONS."mg/images/zoom.png' border='0' />".$locale['mg8']."</a></div>
            <div id='mg_info' style='min-height:50px;margin-left:30px;'></div>
        <span><a href='".BASEDIR."mg_gallery.php?action=create'>".$locale['mg12']."</a>&nbsp;".($album['album_user'] == $userdata['user_id'] ? "<a href='".BASEDIR."mg_gallery.php?action=upload&id=".$_GET['id']."'>".$locale['mg23']."</a>&nbsp;<a href='".BASEDIR."mg_gallery.php?action=edit_album&id=".$_GET['id']."'>".$locale['mg29']."</a>&nbsp;<a href='".BASEDIR."mg_gallery.php?action=edit_photos&id=".$_GET['id']."'>".$locale['mg22']."</a>" : "")."</span> 
</div>
    </td></tr>";
    
    add_to_head("<script type='text/javascript' src='".INFUSIONS."mg/fancybox/jquery.fancybox-1.3.4.pack.js'></script>");
    add_to_head("<link rel='stylesheet' href='".INFUSIONS."mg/fancybox/jquery.fancybox-1.3.4.css' type='text/css' media='screen' />");
    add_to_head("<script type='text/javascript'>
            $(document).ready(function(){
                $('a#mg_zoom').fancybox({
                    padding:0,
                    centerOnScroll:true,
                    hideOnContentClick:true,
                    overlayOpacity:0.7,
                    overlayColor:'#000'
                });
            });
    </script>");
    
    
    echo "</table>";
    closetable();
    echo "<br /><br />";
    require_once INCLUDES."comments_include.php";
    showcomments("MG", DB_MG_PHOTOS, "photo_id", "0");
    
    
} else {
    
    add_to_title($locale['mg9']);
    opentable($locale['mg01']);
    $albums = dbcount("(album_id)", DB_MG_ALBUMS, "");
    if (!isset($_GET['p']) || !isnum($_GET['p'])) { $_GET['p'] = 0; }
    $result = dbquery("SELECT * FROM ".DB_MG_ALBUMS." ORDER BY album_id DESC LIMIT ".$_GET['p'].",10");
    echo "<table width='100%' border='0'><tr>";
    echo "<td colspan='2' class='tbl2'>".$locale['mg4'].$albums."<span style='float:right;'><a href='".BASEDIR."mg_gallery.php?action=create'>".$locale['mg12']."</a></span></td></tr><tr>";
    $i=0;
    if (dbrows($result)) {
    while($data=dbarray($result)) {
        $avtor = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$data['album_user']."'"));
        if ($i % 2 == 0) {echo "</tr><tr>";}
        $num_photos = dbcount("(photo_id)",DB_MG_PHOTOS,"photo_album='".$data['album_id']."'");
        echo "<td class='tbl2' width=50%' valign='top'><a href='".BASEDIR."gallery.php?action=album&id=".$data['album_id']."'><img src='".IMAGES."mg_photos/".$data['album_cover']."' border='0' style='margin:0 10px 10px 0;float:left;' /></a><a href='".BASEDIR."gallery.php?action=album&id=".$data['album_id']."'><strong>".$data['album_title']."</strong></a><br />".$num_photos.$locale['mg5']."<br />".$locale['mg6']."<a href='".BASEDIR."profile.php?lookup=".$data['album_user']."'>".$avtor['user_name']."</a><br />".((iMEMBER && $data['album_user'] == $userdata['user_id']) || (iADMIN && checkrights("MG")) ? "<a href='".BASEDIR."mg_gallery.php?action=edit_album&id=".$data['album_id']."' title='edit'><img src='".IMAGES."edit.png' border='0' /></a> / <a href='".BASEDIR."mg_gallery.php?action=delete_album&id=".$data['album_id']."' title='delete' onclick='return confirm(\"Confirm delete\");'><img src='".IMAGES."no.png' border='0' /></a> / <a href='".BASEDIR."mg_gallery.php?action=upload&id=".$data['album_id']."' title='add photos'><img src='".IMAGES."php_save.png' border='0' /></a>" : "")."</td>";
        $i++;
    }
    } else {
        echo "<td colspan='2' class='tbl2'>".$locale['mg10']."</td>";
    }
    
    
    echo "</tr><tr><td colspan=2' align='center' class='tbl2'>";
    if ($albums > 10) { echo "<div align='center' style='margin-top:5px;'>".makepagenav($_GET['p'], 10, $albums, 3, BASEDIR."gallery.php?")."</div>"; }
    echo "</td></tr>";
    echo "<tr><td colspan='2' style='height:20px;'>&nbsp;</td></tr>";
    echo "<tr><td colspan='2' class='tbl2'>".$locale['mg7'];
    $result = dbquery("SELECT album_user, album_id FROM ".DB_MG_ALBUMS." ORDER BY RAND()");
    $rand_user = array();
    $rand_name = array();
    $j = 1;
    //while ($j != 20 || $j != $albums) {
        while ($data = dbarray($result)) {
        if (!in_array($data['album_user'],$rand_user) && $j != 20) {
            $rand_user[$j] = $data['album_user'];
            $name = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$data['album_user']."'"));
            $rand_name[$j] = $name['user_name'];
            $j++;
        }
        }
    //}
    if (count($rand_user) > 0) {
    for ($i=1;$i<=count($rand_user);$i++) {
        echo " <a href='".BASEDIR."gallery.php?action=user&id=".$rand_user[$i]."'> ".$rand_name[$i]."</a>".($i<count($rand_user) ? "," : "");
    }
    } else {
        echo $locale['mg10'];
    }
    
    echo "</td></tr>";
    echo "</table>";
    closetable();
    
}


 //echo "<div style='text-align:center;width:100%;'>AL jQ gallery <a href='http://fusion.annetlab.tk'>Fusion @ AnnetLab</a> &copy; 2011-2012</div>"; 



require_once THEMES."templates/footer.php";
?>
