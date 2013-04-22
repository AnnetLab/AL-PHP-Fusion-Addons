<?php
require_once "../../../maincore.php";
require_once INFUSIONS."al_register_mod/infusion_db.php";


if (isset($_POST['action']) && $_POST['action'] == "check_email") {
 
$email = $_POST['useremail'];
$email = stripinput(trim(preg_replace("/ +/i", " ", $email))); 

if (preg_check("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
$check1 = dbquery("SELECT * FROM ".DB_USERS." WHERE user_email='".$email."'");
$check2 = dbquery("SELECT * FROM ".DB_RM_USERS." WHERE rmuser_useremail='".$email."'");
if (dbrows($check1) || dbrows($check2)) {
$result = false;
$msg = "<span style='color:red;'><img src='images/no.png' width='12' /> Email already exist</span>";
} else {
$result = true;
$msg = "<span style='color:green;'><img src='images/yes.png' width='12' /> OK</span>";
}

} else {
$result = false;
$msg = "<span style='color:red;'><img src='images/no.png' width='12' /> Incorrect email</span>";
}
$e = array("result"=>$result,"msg"=>$msg);
print json_encode($e); 
}

if (isset($_POST['action']) && $_POST['action'] == "check_password") {

$password = $_POST['password'];

 require_once CLASSES."PasswordAuth.class.php";
$passAuth = new PasswordAuth();
$passAuth->inputNewPassword = $password;
$passAuth->inputNewPassword2 = $password;
$passAuth->currentPassword = "";
$valid = $passAuth->isValidNewPassword();

if ($valid === 0) {
$result = true;
$msg = "<span style='color:green;'><img src='images/yes.png' width='12' /> OK</span>";
} else {
$result = false;
$msg = "<span style='color:red;'><img src='images/no.png' width='12' /> Incorrect password</span>"; 
}
$e = array("result"=>$result,"msg"=>$msg);
print json_encode($e); 
}


if (isset($_POST['action']) && $_POST['action'] == "check_name") {
$name = $_POST['username'];
$name = stripinput(trim(preg_replace("/ +/i", " ", $name)));


if (!empty($name)) {
if (!preg_check("/^[-0-9A-Z_@\s]+$/i", $name) || strlen($name) < 3 || strlen($name) > 25) {
$msg = "<span style='color:red;'><img src='images/no.png' width='12' /> Invalid symbols or incorrect lenght</span>";
$result = false;
} else {

$check1 = dbquery("SELECT * FROM ".DB_USERS." WHERE user_name='".$name."'"); 
$check2 = dbquery("SELECT * FROM ".DB_RM_USERS." WHERE rmuser_username='".$name."'"); 
if (dbrows($check1) || dbrows($check2)) {
$msg = "<span style='color:red;'><img src='images/no.png' width='12' /> Name already exist</span>";
$result = false;
} else {
$msg = "<span style='color:green;'><img src='images/yes.png' width='12' /> OK</span> ";
$result = true;
}
}
} else {
$result = false;
$msg = "<span style='color:red;'><img src='images/no.png' width='12' /> Name is empty</span>";
}


$e = array("result"=>$result,"name"=>$name,"msg"=>$msg);
print json_encode($e);
}

?>
