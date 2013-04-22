<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: ufrontend.php
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
require_once "../../../maincore.php";
header("Content-Type: text/html; charset=".$locale['charset']);
if (file_exists("../locale/".$settings['locale']."_frontend.php")) {
    require_once "../locale/".$settings['locale']."_frontend.php";
} else {
    require_once "../locale/English_frontend.php";
}

echo "<style>
body {color: #4b4b4b;font-family: Verdana,Arial; font-size: 0.8em;}
.uidentity {font-size: 24px; color: #1BAFE0;}
.uimg {margin-bottom: -4px;width:24px;height:24px;}
.udiv { width:350px;text-align:center;margin: 0 auto;}
.udiv2 {width:350px;text-align:center;float: left;}
.udiv2 dl { margin: 0 auto; width: 250px; }
.udiv2 dt { float: left; width:50px; text-align: left; }
.udiv2 dd { margin-left: 80px; text-align: left; }
.ua {color: #1BAFE0;}
.ubutton {olor: #555;background: #f8f8f8;border: 1px solid #ccc;padding: 5px 12px;margin-top: 2px;display: inline-block;cursor: pointer;font-weight: normal;border-radius: 2px;box-shadow: 0 0 1px #ddd;}
.ubutton:hover {background-color: #4D90FE;border-color: #3079ED;color: #fff;}
.utextbox {color: #a0a09e;font-size: 13px;background: #fff;border: 1px solid #CACACA;border-top-color: #aaa;margin: 0 0 2px;padding: 5px 2px 5px 5px;}
.utextbox:hover, .utextbox:focus {border-color: #4D90FE;box-shadow: inset 0px 1px 2px #aaa;-moz-box-shadow: inset 0px 1px 2px #aaa;-webkit-box-shadow: inset 0px 1px 2px #aaa;}
.ucap {font-size: 24px; color: #1BBF33;margin-top:0px;margin-bottom:-10px;}
</style>";

if (isset($_GET['res'])) {
    if ($_GET['res'] == "1") {
        $base = $_GET['base'];
        echo "<div class='udiv'>";
        echo $locale['ul5']."<br /><a href='".$_GET['identity']."' class='uidentity'><img src='".$base."infusions/ulogin/img/big/".$_GET['network'].".png' class='uimg' /> ".$_GET['full_name']."</a><br />".sprintf($locale['ul6'],$_GET['user_id'],$_GET['user_name'])."</div>";

    } else if ($_GET['res'] == "2") {
        $base = $_GET['base'];
        echo "<div class='udiv'>";
        echo $locale['ul1']." <br /><a href='".$_GET['identity']."' class='uidentity'><img src='".$base."infusions/ulogin/img/big/".$_GET['network'].".png' class='uimg' /> ".$_GET['full_name']."</a><br />".sprintf($locale['ul2'],$_GET['email'],$_GET['user_id'],$_GET['user_name']);
        echo "<br /><form action='".$base."infusions/ulogin/includes/ubackend.php' method='post' name='ulog'><input type='hidden' name='identity' value='".$_GET['identity']."' /><input type='hidden' name='network' value='".$_GET['network']."' /><input type='hidden' name='full_name' value='".$_GET['full_name']."' /><input type='hidden' name='user_name' value='".$_GET['user_name']."' /><input type='hidden' name='user_id' value='".$_GET['user_id']."' /><input type='hidden' name='url' value='".$_GET['url']."' /><input type='password' class='utextbox' name='user_pass' /><input type='submit' name='ex_user_save' class='ubutton' value='".$locale['ul3']."' /></form></div>";

    } else if ($_GET['res'] == "3") {
        $base = $_GET['base'];
        echo "<div class='udiv'>";
        echo $locale['ul1']."<br /><a href='".$_GET['identity']."' class='uidentity'><img src='".$base."infusions/ulogin/img/big/".$_GET['network'].".png' class='uimg' /> ".$_GET['full_name']."</a><br />";
        echo "</div>";
        echo "<form action='".$base."infusions/ulogin/includes/ubackend.php' method='post' name='ttf'>";
        echo "<div class='udiv2'>";
            echo "<h3 class='ucap'>".$locale['ul7']."</h3><br />".$locale['ul8']."<br /><input type='submit' class='ubutton' name='new_user' value='".$locale['ul11']."' />";
        echo "</div>";

        echo "<div class='udiv2'>";
            echo "<h3 class='ucap'>".$locale['ul9']."</h3><br />".$locale['ul10']."<br /><br />
            <dl>
                <dt>".$locale['ul13']."</dt><dd><input type='text' class='utextbox' name='user_name' style='width:150px;' /></dd>
                <dt>".$locale['u113a']."</dt><dd><input type='password' class='utextbox' style='width:150px;' name='user_pass' /></dd>
                <dt>&nbsp;</dt><dd><input type='submit' class='ubutton' name='ex_user_save' value='".$locale['ul12']."' /></dd>
            </dl>";
            echo "<input type='hidden' name='identity' value='".$_GET['identity']."' /><input type='hidden' name='network' value='".$_GET['network']."' /><input type='hidden' name='full_name' value='".$_GET['full_name']."' /> <input type='hidden' name='password' value='".$_GET['password']."' /><input type='hidden' name='nickname' value='".$_GET['nickname']."' /><input type='hidden' name='email' value='".$_GET['email']."' /><input type='hidden' name='url' value='".$_GET['url']."' />";
        echo "</div>";
        echo "</form>";
    }
}

if (isset($_GET['ulogin_error'])) {
    echo "<div style='width:300px;text-align:center;'>".$locale['ul4']."</div>";
}

if (isset($_GET['ulogin_error_2'])) {
    echo "<div style='width:300px;text-align:center;'>Account already exists...</div>";
}

?>
