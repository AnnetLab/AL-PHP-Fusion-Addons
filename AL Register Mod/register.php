<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
 require_once INFUSIONS."al_register_mod/infusion_db.php"; 
require_once INFUSIONS."al_register_mod/includes/functions.php";

 if (file_exists(INFUSIONS."al_register_mod/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_register_mod/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_register_mod/locale/English.php";
} 

if (isset($_POST['finish_register'])) {
//print_r($_POST);
require_once INCLUDES."captchas/recaptcha/captcha_check.php";
$app = unserialize($_POST['appp']);
//print_r($app);

if ($_CAPTCHA_IS_VALID) {
//$app = trim(nl2br($_POST['application']));
$time = time()+($settings['timeoffset']*3600);
$ffs = dbquery("SELECT * FROM ".DB_RM_FORM_FIELDS." WHERE ff_type<>'4' ORDER BY ff_order ASC");
$sql1 = ""; $sql2 = ""; $i=1;
while ($ff=dbarray($ffs)) {
    $f_name = "fa_".$ff['ff_name'];
    $f_value = $app[$ff['ff_name']];
    if ($i>1) {
        $sql1 .= ", ".$f_name;
        $sql2 .= ", '".$f_value."'";
    } else {
        $sql1 .= $f_name;
        $sql2 .= "'".$f_value."'";
    }
    $i++;
}
//echo $sql1.$sql2;

$ins_appp = dbquery("INSERT INTO ".DB_RM_FORM_APPS." (".$sql1.") VALUES (".$sql2.")");
$appp_id = mysql_insert_id();

if (iMEMBER && isset($app['member'])) {
$member = $app['member'];
$ins_app = dbquery("INSERT INTO ".DB_RM_APPS." (app_rm_user, app_user, app_form, app_voted, app_votes_yes, app_votes_no, app_date, app_status) VALUES ('0', '".$member."', '".$appp_id."', '', '0', '0', '".$time."', '0')");
// sendmail
require_once INCLUDES."sendmail_include.php";
$uinfo = dbarray(dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$member."'"));
$msg = $locale['eml2'];
sendemail($uinfo['user_name'], $uinfo['user_email'], $settings['siteusername'], $settings['siteemail'], $locale['eml1'], $msg,"html");

redirect(BASEDIR."register.php?msg=8");
} else {
$name = $app['username'];
$email = $app['useremail'];
$password = $app['password'];

 require_once CLASSES."PasswordAuth.class.php";
$passAuth = new PasswordAuth();
$passAuth->inputNewPassword = $password;
$passAuth->inputNewPassword2 = $password;
$passAuth->currentPassword = "";
echo $valid = $passAuth->isValidNewPassword();

if ($valid === 0) {
// New password is valid
$hash = $passAuth->getNewHash();
$algo = $passAuth->getNewAlgo();
$salt = $passAuth->getNewSalt();
} 
$code = md5($name.$email);
$ins_rm_user = dbquery("INSERT INTO ".DB_RM_USERS." (rmuser_username, rmuser_useremail, rmuser_password, rmuser_algo, rmuser_salt, rmuser_code, rmuser_verified, rmuser_approved) VALUES ('".$name."', '".$email."', '".$hash."', '".$algo."', '".$salt."', '".$code."', '0', '0')");
$rm_user_id = mysql_insert_id();
 $ins_app = dbquery("INSERT INTO ".DB_RM_APPS." (app_rm_user, app_user, app_form, app_voted, app_votes_yes, app_votes_no, app_date, app_status, app_username, app_useremail) VALUES ('".$rm_user_id."', '0', '".$appp_id."', '', '0', '0', '".$time."', '0', '".$name."', '".$email."')"); 
// sendmail, verify user

 require_once INCLUDES."sendmail_include.php";
$msg = sprintf($locale['eml3'],$code);
sendemail($name, $email, $settings['siteusername'], $settings['siteemail'], $locale['eml1'], $msg,"html"); 

redirect(BASEDIR."register.php?msg=8");
}

} else {
redirect(BASEDIR."register.php?msg=7");
}

}

if (isset($_GET['code']) && strlen($_GET['code']) == 32) {

$uinfo = dbquery("SELECT * FROM ".DB_RM_USERS." WHERE rmuser_code='".$_GET['code']."'");
if (dbrows($uinfo)) {
$uinfo = dbarray($uinfo);
if ($uinfo['rmuser_approved'] == "1") {
//ins user

 $result = dbquery("INSERT INTO ".DB_USERS." (user_name, user_password, user_admin_password, user_email, user_hide_email, user_avatar, user_posts, user_threads, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status, user_sig, user_salt, user_algo) VALUES('".$uinfo['rmuser_username']."', '".$uinfo['rmuser_password']."', '', '".$uinfo['rmuser_useremail']."', '1', '', '0', '0', '".time()."', '0', '".USER_IP."', '', '.2', '101', '0', '', '".$uinfo['rmuser_salt']."', '".$uinfo['rmuser_algo']."')"); 
$new_user = mysql_insert_id();
$upd_Pp = dbquery("UPDATE ".DB_RM_APPS." SET app_user='".$new_user."' WHERE app_rm_user='".$uinfo['rmuser_id']."'");
$delrm = dbquery("DELETE FROM ".DB_RM_USERS." WHERE rmuser_id='".$uinfo['rmuser_id']."'"); 
redirect(BASEDIR."register.php?msg=12");
} else {
// just set verified
$upd = dbquery("UPDATE ".DB_RM_USERS." SET rmuser_verified='1' WHERE rmuser_code='".$_GET['code']."'");
redirect(BASEDIR."register.php?msg=11");
}
} else {
redirect(START_PAGE);
}

} elseif (isset($_GET['msg']) && isnum($_GET['msg'])) {
echo "<div class='admin-message'>".$locale['msg'.$_GET['msg']]."".($_GET['msg'] != "8" ? "<br />".$locale['msg10']."" : "")."</div>";


} elseif (isset($_GET['step']) && isnum($_GET['step'])) {
if ($_GET['step'] == "1") {
 if (iMEMBER) {
if (check_group(2) || check_group(1)) {
redirect(BASEDIR."index.php");
} else {
redirect(BASEDIR."register.php?step=3");
}
} 
//show guild rules
opentable($locale['ar1']);
$r = dbarray(dbquery("SELECT * FROM ".DB_RM_RULES.""));
echo "<table width='100%'><tr><td class='tbl2'>";
echo trim(nl2br(parseubb($r['rules'])));
echo "</td></tr><tr><td class='tbl2' align='center'>";
echo "<form action='register.php?step=2' method='post'><input type='checkbox' name='acept' value='1' /> Accept rules <input type='submit' class='button' name='apply_rules' value='".$locale['ar2']."' /></form>";
echo "</td></tr></table>";
closetable();
} elseif ($_GET['step'] == "2") {
 if (iMEMBER) {
if (check_group(2) || check_group(1)) {
redirect(BASEDIR."index.php");
} else {
redirect(BASEDIR."register.php?step=3");
}
} 
if (!isset($_POST['acept']) && $_POST['acept'] != "1") { redirect(BASEDIR."register.php?step=1");
}
//register form
//print_r($_POST);

add_to_head("<script type='text/javascript'>
$(document).ready(function(){
$('#confirm').hide();
$('#inputform').keypress(function(event){
if (event.keyCode == 13) {
return false;
}
});
var url = 'infusions/al_register_mod/includes/backend.php';
var uname = false; var upass = false; var umail = false;
$('#username').change(function(){
var name = $('#username').val();
//alert(name);
$.post(url, { action: 'check_name', username: name }, function(data){
//alert(data.msg);
$('#username_msg').html(data.msg);
uname = data.result;
check_complete(); 
}, 'json');
});

$('#password1').change(function(){
var pass1 = $('#password1').val();
var pass2 = $('#password2').val(); 
if (pass1 == pass2 && pass1 != '') {
$.post(url, { action: 'check_password', password: pass1 }, function(data){
//alert(data.msg);
$('#password_msg').html(data.msg); 
upass = data.result;
check_complete(); 
}, 'json');
} else {
upass = false;
if (pass1 != pass2) {
if (pass1 == '') {
// msg - fill pass1
//alert('fill p1');
$('#password_msg').html('<span style=\'color:red;\'><img src=\'images/warn.png\' width=\'12\' /> Confirm password</span>');
} else if (pass2 == '') {
// msg - fill pass2
//alert('fill p2'); 
 $('#password_msg').html('<span style=\'color:red;\'><img src=\'images/warn.png\' width=\'12\' /> Confirm password</span>'); 
} else {
// msg - ne sovpadaut
//alert('raznye passy');
 $('#password_msg').html('<span style=\'color:red;\'><img src=\'images/no.png\' width=\'12\' /> Passwords do not match</span>'); 
}
}
}
});

 $('#password2').change(function(){
var pass1 = $('#password1').val();
var pass2 = $('#password2').val(); 
if (pass1 == pass2 && pass1 != '') {
$.post(url, { action: 'check_password', password: pass1 }, function(data){
//alert(data.msg);
$('#password_msg').html(data.msg); 
upass = data.result;
check_complete(); 
}, 'json');
} else {
upass = false;
if (pass1 != pass2) {
if (pass1 == '') {
// msg - fill pass1
//alert('fill p1');
 $('#password_msg').html('<span style=\'color:red;\'><img src=\'images/warn.png\' width=\'12\' /> Confirm password</span>'); 
} else if (pass2 == '') {
// msg - fill pass2
//alert('fill p2'); 
 $('#password_msg').html('<span style=\'color:red;\'><img src=\'images/warn.png\' width=\'12\' /> Confirm password</span>'); 
} else {
// msg - ne sovpadaut
//alert('raznye passy');
 $('#password_msg').html('<span style=\'color:red;\'><img src=\'images/no.png\' width=\'12\' /> Passwords do not match</span>'); 
}
}
}
}); 


 $('#useremail').change(function(){
var email = $('#useremail').val();
//alert(name);
$.post(url, { action: 'check_email', useremail: email }, function(data){
//alert(data.msg);
$('#useremail_msg').html(data.msg); 
umail = data.result;
check_complete();
}, 'json');
}); 

function check_complete() {
if (uname == true && upass == true && umail == true) {
$('#confirm').show();
} else {
$('#confirm').hide(); 
}
}

});

</script>");


opentable($locale['ar3']);
echo "<form action='register.php?step=3' name='inputform' method='post' id='inputform'>";
echo "<table width='100%'>";
echo "<tr><td width='250' class='tbl2'>".$locale['ar4']."</td><td class='tbl2'><input type='text' class='textbox' name='username' id='username' /></td><td class='tbl2' width='150'><div id='username_msg'><span style='color:red;'>* required</span></div></td></tr>";
 echo "<tr><td width='250' class='tbl2'>".$locale['ar5']."</td><td class='tbl2'><input type='password' class='textbox' name='password1' id='password1' /></td><td class='tbl2' width='150' rowspan='2'><div id='password_msg'><span style='color:red;'>* required</span></div></td></tr>";
 echo "<tr><td width='250' class='tbl2'>".$locale['ar6']."</td><td class='tbl2'><input type='password' class='textbox' name='password2' id='password2' /></td></tr>";
 echo "<tr><td width='250' class='tbl2'>".$locale['ar7']."</td><td class='tbl2'><input type='text' class='textbox' name='useremail' id='useremail' /></td><td class='tbl2' width='150'><div id='useremail_msg'><span style='color:red;'>* required</span></div></td></tr>"; 
echo "<tr><td class='tbl2' colspan='3' align='center'><input type='submit' class='button' name='new_user' value='".$locale['ar8']."' id='confirm' /></td></tr>";
echo "</table>";
echo "</form>";
closetable(); 

} elseif ($_GET['step'] == "3") {
// application

if (iMEMBER) {

} else {
if (isset($_POST['new_user'])) {
$email = stripinput(trim(preg_replace("/ +/i", " ", $_POST['useremail']))); 
$name = stripinput(trim(preg_replace("/ +/i", " ", $_POST['username'])));
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];

if (!empty($name)) {
if (!preg_check("/^[-0-9A-Z_@\s]+$/i", $name) || strlen($name) < 3 || strlen($name) > 25) {
redirect(BASEDIR."register.php?msg=1");
} else {
$check1 = dbquery("SELECT * FROM ".DB_USERS." WHERE user_name='".$name."'"); 
$check2 = dbquery("SELECT * FROM ".DB_RM_USERS." WHERE rmuser_username='".$name."'"); 
if (dbrows($check1) || dbrows($check2)) {
redirect(BASEDIR."register.php?msg=2"); 
}
}
} else {
redirect(BASEDIR."register.php?msg=1"); 
}


if (preg_check("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
$check1 = dbquery("SELECT * FROM ".DB_USERS." WHERE user_email='".$email."'");
$check2 = dbquery("SELECT * FROM ".DB_RM_USERS." WHERE rmuser_useremail='".$email."'"); 
if (dbrows($check1) || dbrows($check2)) {
redirect(BASEDIR."register.php?msg=3"); 
}
} else {
redirect(BASEDIR."register.php?msg=4"); 
}

require_once CLASSES."PasswordAuth.class.php";
$passAuth = new PasswordAuth();
$passAuth->inputNewPassword = $password1;
$passAuth->inputNewPassword2 = $password2;
$passAuth->currentPassword = "";
$valid = $passAuth->isValidNewPassword();

if ($valid === 0) { 
$password = $password1;
} else {
redirect(BASEDIR."register.php?msg=5"); 
}


} else {
redirect(BASEDIR."register.php");
}
}
require_once INCLUDES."bbcode_include.php";
// finish doublecheck - start app
opentable($locale['ar9']);
$form = dbquery("SELECT * FROM ".DB_RM_FORM_FIELDS." ORDER BY ff_order ASC");
echo "<form name='inputform' method='post' action='register.php?step=4'><table width='100%'>";
while ($ff=dbarray($form)) {
echo "<tr>".($ff['ff_type'] == "4" ? "<td class='tbl2' colspan='2'>" : "<td class='tbl2' width='250'>".$ff['ff_title']."</td><td class='tbl2' colspan='2'>");
if ($ff['ff_type'] == "1") {
    echo "<input type='text' class='textbox' name='".$ff['ff_name']."' style='width:250px;' />";
} elseif ($ff['ff_type'] == "2") {
    echo "<textarea name='".$ff['ff_name']."' rows='5' cols='45' class='textbox'></textarea>";
} elseif ($ff['ff_type'] == "3") {
    $values = explode(",",$ff['ff_value']);
    echo "<select name='".$ff['ff_name']."'>";
    foreach ($values as $value) {
        echo "<option value='".$value."'>".$value."</option>";
    }
    echo "</select>";
} elseif ($ff['ff_type'] == "4") {
    echo $ff['ff_infobox'];
}
echo "</td></tr>";
}
echo "<tr><td class='tbl2' colspan='2' align='center'>";
if (iMEMBER) {
echo "<input type='hidden' name='member' value='".$userdata['user_id']."' />";
} else {
echo "<input type='hidden' name='username' value='".$name."' /><input type='hidden' name='useremail' value='".$email."' /><input type='hidden' name='password' value='".$password."' />";
}
echo "<input type='submit' name='apply_form' class='button' value='".$locale['ar10']."' /></td></tr>"; 
echo "</table></form>";
closetable();
} elseif ($_GET['step'] == "4") {
// finish
if (isset($_POST['apply_form'])) {

opentable($locale['ar11']);

echo "<form action='register.php' name='ftdh' method='post'><table width='100%'>";
echo "<tr><td class='tbl2' align='center'>";
require_once INCLUDES."captchas/recaptcha/captcha_display.php";
echo "<input type='submit' class='button' name='finish_register' value='Finish registration' />";
/*echo "<input type='hidden' name='application' value='".$_POST['application']."' />";
if (iMEMBER && isset($_POST['member'])) {
echo "<input type='hidden' name='member' value='".$_POST['member']."' />";
} else {
echo "<input type='hidden' name='username' value='".$_POST['username']."' />";
echo "<input type='hidden' name='useremail' value='".$_POST['useremail']."' />";
echo "<input type='hidden' name='password' value='". $_POST['password']."' />"; 
}
*/
$appp = serialize($_POST);
echo "<input type='hidden' name='appp' value='".$appp."' />";
echo "</td></tr></table></form>";
closetable();
} else {
redirect(BASEDIR."register.php?step=1");
}

} else {
redirect(BASEDIR."register.php?step=1");
}
} else {
redirect(BASEDIR."register.php?step=1"); 
}

require_once THEMES."templates/footer.php"; 
?>
