<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: ubackend.php
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
require_once INFUSIONS."ulogin/infusion_db.php";
header("Content-Type: text/html; charset=".$locale['charset']);

if (isset($_GET['act']) && $_GET['act'] == "del" && isnum($_GET['id']) && iMEMBER) {
    $data = dbarray(dbquery("SELECT * FROM ".DB_ULOGIN." WHERE ulogin_id='".$_GET['id']."'"));
    if ($data['ulogin_user'] == $userdata['user_id']) {
        $z = dbquery("DELETE FROM ".DB_ULOGIN." WHERE ulogin_id='".$data['ulogin_id']."'");
        redirect(BASEDIR."edit_profile.php");
    }
}

if (isset($_GET['add_identity']) && iMEMBER && isset($_POST['token']) && !empty($_POST['token'])) {
    require_once INFUSIONS."ulogin/lib/uloginAPI2.class.php";
    $uprofile = new uloginAPI2($_POST['token']);
    $check = dbquery("SELECT * FROM ".DB_ULOGIN." WHERE ulogin_identity='".$uprofile->genUserIdentity()."'");
    if (dbrows($check)) {
        redirect(BASEDIR."login.php?ulogin_error_2");
    } else {
        $add = dbquery("INSERT INTO ".DB_ULOGIN." (ulogin_user, ulogin_identity, ulogin_network, ulogin_fullname) VALUES ('".$userdata['user_id']."', '".$uprofile->genUserIdentity()."', '".$uprofile->genUserNetwork()."', '".iconv($locale['charset'],"UTF-8",$uprofile->genDisplayName())."')");
        redirect(BASEDIR."edit_profile.php");
    } //check

}


if (isset($_POST['new_user'])) {
    if (file_exists("../locale/".$settings['locale']."_frontend.php")) {
        require_once "../locale/".$settings['locale']."_frontend.php";
    } else {
        require_once "../locale/Russian_frontend.php";
    }
    $nick = trim(stripinput($_POST['nickname']));
    $email = trim(stripinput($_POST['email']));
    $pass = trim(stripinput($_POST['password']));
    require_once CLASSES."PasswordAuth.class.php";
    $passAuth = new PasswordAuth();
    $passAuth->inputNewPassword = $pass;
    $passAuth->inputNewPassword2 = $pass;
    $passAuth->currentPassword = "";
    echo $valid = $passAuth->isValidNewPassword();

    if ($valid === 0) {
        // New password is valid
        $hash = $passAuth->getNewHash();
        $algo = $passAuth->getNewAlgo();
        $salt = $passAuth->getNewSalt();
    }
    $identity = $_POST['identity'];
    $acc = $_POST['network'];
    $fn = iconv($locale['charset'],"UTF-8",$_POST['full_name']);
    $result = dbquery("INSERT INTO ".DB_USERS." (user_name, user_password, user_admin_password, user_email, user_hide_email, user_avatar, user_posts, user_threads, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status, user_sig, user_salt, user_algo) VALUES('".$nick."', '".$hash."', '', '".$email."', '1', '', '0', '0', '".time()."', '0', '".USER_IP."', '', '', '101', '0', '', '".$salt."', '".$algo."')");
    $user_id = mysql_insert_id();
    Authenticate::setUserCookie($user_id, $passAuth->getNewSalt(),	$passAuth->getNewAlgo(), false);

    $result2 = dbquery("INSERT INTO ".DB_ULOGIN." (ulogin_identity, ulogin_network, ulogin_user, ulogin_fullname) VALUES ('".$identity."', '".$acc."', '".$user_id."', '".$fn."')");

    require_once INCLUDES."sendmail_include.php";

    $text = sprintf($locale['ul14'], $_POST['identity'], $_POST['nickname'], $_POST['password']);
    sendemail($nick,$email,$settings['siteusername'], $settings['siteemail'], $locale['ul15'], $text);
    if ($result && $result2) {
        $auth = new Authenticate($nick, $pass, true);
        $userdata = $auth->getUserData();
        unset($auth);
        redirect($_POST['url']);
    } else {
        redirect(BASEDIR."login.php?ulogin_error");
    }
}

if (isset($_POST['ex_user_save'])) {
    $result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_name='".$_POST['user_name']."'");
    if (dbrows($result)) {
        $user = dbarray($result);

        require_once CLASSES."PasswordAuth.class.php";

        // Initialize password auth
        $passAuth = new PasswordAuth();
        $passAuth->currentAlgo = $user['user_algo'];
        $passAuth->currentSalt = $user['user_salt'];
        $passAuth->currentPasswordHash = $user['user_password'];
        $passAuth->inputPassword = $_POST['user_pass'];

        if ($passAuth->isValidCurrentPassword(false)) {

            $result = dbquery("INSERT INTO ".DB_ULOGIN." (ulogin_user, ulogin_identity, ulogin_network, ulogin_fullname) VALUES ('".$user['user_id']."','".$_POST['identity']."','".$_POST['network']."', '".iconv($locale['charset'],"UTF-8",$_POST['full_name'])."')");
            $auth = new Authenticate($_POST['user_name'], $_POST['user_pass'], true);
            unset($auth);
            if ($result) {
                redirect($_POST['url']);
            }
        } else {
            redirect(BASEDIR."login.php?ulogin_error");
        }
    } else {
        redirect(BASEDIR."login.php?ulogin_error");
    }

}

if (isset($_POST['action']) && $_POST['action'] == "gettoken") {

    require_once INFUSIONS."ulogin/lib/uloginAPI2.class.php";
    $uprofile = new uloginAPI2($_POST['token']);

    $check = dbquery("SELECT ul.*, us.user_name, us.user_id FROM ".DB_ULOGIN." ul LEFT JOIN ".DB_USERS." us ON us.user_id=ul.ulogin_user WHERE ulogin_identity='".$uprofile->genUserIdentity()."'");
    if (dbrows($check)) {
        //если есть такой идент - авторизуем
        require_once INFUSIONS."ulogin/lib/Force.Authenticate.class.php";
        $user = dbarray($check);
        $fauth = new ForceAuth($user['ulogin_user']);
        unset($fauth);
        $response = array("res"=>1, "identity"=>$uprofile->genUserIdentity(),"network"=>$uprofile->genUserNetwork(),"full_name"=>$uprofile->genDisplayName(),"user_name"=>$user['user_name'],"user_id"=>$user['user_id']);
    } else {
        //check email
        if ($uprofile->checkExist("user_email",$uprofile->genEmail())) {
            //мэил есть
            //форма входа с логином
            $data = dbarray(dbquery("SELECT * FROM ".DB_USERS." WHERE user_email='".$uprofile->genEmail()."'"));
            //hidden - ident, network, fullname
            $response = array("res"=>2,"identity"=>$uprofile->genUserIdentity(),"network"=>$uprofile->genUserNetwork(),"full_name"=>$uprofile->genDisplayName(),"user_name"=>$data['user_name'],"user_id"=>$data['user_id'],"email"=>$uprofile->genEmail());
        } else {
            //новый-существующий пользователь
            $response = array("res"=>3,"identity"=>$uprofile->genUserIdentity(),"network"=>$uprofile->genUserNetwork(),"full_name"=>$uprofile->genDisplayName(),"nickname"=>$uprofile->genNickname(),"email"=>$uprofile->genEmail(),"password"=>$uprofile->genRandomPassword());
        }
    }
    print json_encode($response);
}

?>
