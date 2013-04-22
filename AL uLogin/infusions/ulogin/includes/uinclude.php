<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: uinclude.php
| Author: Rush
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/ 
if (!defined("IN_FUSION")) die("fu");


if (!isset($ulogin_d)) {

    add_to_head("<script type='text/javascript' src='".INFUSIONS."ulogin/includes/fancybox/jquery.easing-1.3.pack.js'></script>");
    add_to_head("<script type='text/javascript' src='".INFUSIONS."ulogin/includes/fancybox/jquery.mousewheel-3.0.4.pack.js'></script>");
    add_to_head("<script type='text/javascript' src='".INFUSIONS."ulogin/includes/fancybox/jquery.fancybox-1.3.4.js'></script>");
    add_to_head("<link rel='stylesheet' href='".INFUSIONS."ulogin/includes/fancybox/jquery.fancybox-1.3.4.css' type='text/css' media='screen' />");
    add_to_head("<script type='text/javascript'>
        var ubase = '".BASEDIR."';


        function uCallback(token) {
            $.post(ubase+'infusions/ulogin/includes/ubackend.php', {
                action: 'gettoken',
                token: token
            }, function(udata){
                if (udata.res == 1) {
                    $.fancybox({
                        type: 'ajax',
                        href: ubase+'infusions/ulogin/includes/ufrontend.php?res='+udata.res+'&identity='+udata.identity+'&network='+udata.network+'&full_name='+udata.full_name+'&user_name='+udata.user_name+'&user_id='+udata.user_id+'&base='+ubase
                    });
                    setTimeout(function(){
                    location.href = location.href.replace(location.hash,'');
                    }, 3000);

                } else if (udata.res == 2) {
                    $.fancybox({
                        type: 'ajax',
                        href: ubase+'infusions/ulogin/includes/ufrontend.php?res='+udata.res+'&identity='+udata.identity+'&network='+udata.network+'&full_name='+udata.full_name+'&user_name='+udata.user_name+'&user_id='+udata.user_id+'&email='+udata.email+'&url='+window.location.href+'&base='+ubase
                    });
                } else if (udata.res == 3) {
                    $.fancybox({
                        type: 'ajax',
                        href: ubase+'infusions/ulogin/includes/ufrontend.php?res='+udata.res+'&identity='+udata.identity+'&network='+udata.network+'&full_name='+udata.full_name+'&nickname='+udata.nickname+'&email='+udata.email+'&password='+udata.password+'&url='+window.location.href+'&base='+ubase
                    });
                }
            }, 'json');
        }

    </script>");

    require_once INFUSIONS."ulogin/infusion_db.php";
    $usettings = dbarray(dbquery("SELECT * FROM ".DB_ULOGIN_SETTINGS));
    echo "<script src='http://ulogin.ru/js/ulogin.js'></script>";
    $networks = array(1=>"vkontakte","facebook","google","twitter","odnoklassniki","mailru","yandex","livejournal","openid","lastfm","linkedin","liveid","soundcloud","steam","flickr","vimeo","youtube","webmoney");

    $hid_providers = ""; $vis_providers = ""; $all_providers = "";
    for ($i=1;$i<=18;$i++) {
        if ($usettings['u_'.$networks[$i]] == "1") {
            $vis_providers = $vis_providers != "" ? $vis_providers.",".$networks[$i] : $networks[$i];
            $all_providers = $all_providers != "" ? $all_providers.",".$networks[$i] : $networks[$i];
        } elseif ($usettings['u_'.$networks[$i]] == "2") {
            $hid_providers = $hid_providers != "" ? $hid_providers.",".$networks[$i] : $networks[$i];
            $all_providers = $all_providers != "" ? $all_providers.",".$networks[$i] : $networks[$i];
        }
    }

    $ulogin_d = true;
}

function showUloginWidget($style="small") {
    global $vis_providers, $hid_providers;
    echo "<div id='uLogin-holder-panel-".mt_rand(10,100)."' x-ulogin-params='display=".$style.";fields=email;optional=first_name,last_name,nickname;providers=".$vis_providers.";hidden=".$hid_providers.";redirect_uri=;callback=uCallback'></div>";
}

?>
