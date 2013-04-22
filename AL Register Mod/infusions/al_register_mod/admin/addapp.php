<?php


if (isset($_POST['new_app'])) {
    $app = $_POST;
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

    
    
    redirect(INFUSIONS."al_register_mod/admin/index.php?p=apps");
}



add_to_head("<script type='text/javascript'>
$(document).ready(function(){
$('#confirm').hide();
$('#inputform').keypress(function(event){
if (event.keyCode == 13) {
return false;
}
});
var url = '../includes/backend.php';
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
$('#password_msg').html('<span style=\'color:red;\'><img src=\'../../../images/warn.png\' width=\'12\' /> Confirm password</span>');
} else if (pass2 == '') {
// msg - fill pass2
//alert('fill p2'); 
 $('#password_msg').html('<span style=\'color:red;\'><img src=\'../../../images/warn.png\' width=\'12\' /> Confirm password</span>'); 
} else {
// msg - ne sovpadaut
//alert('raznye passy');
 $('#password_msg').html('<span style=\'color:red;\'><img src=\'../../../images/no.png\' width=\'12\' /> Passwords do not match</span>'); 
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
 $('#password_msg').html('<span style=\'color:red;\'><img src=\'../../../images/warn.png\' width=\'12\' /> Confirm password</span>'); 
} else if (pass2 == '') {
// msg - fill pass2
//alert('fill p2'); 
 $('#password_msg').html('<span style=\'color:red;\'><img src=\'../../../images/warn.png\' width=\'12\' /> Confirm password</span>'); 
} else {
// msg - ne sovpadaut
//alert('raznye passy');
 $('#password_msg').html('<span style=\'color:red;\'><img src=\'../../../images/no.png\' width=\'12\' /> Passwords do not match</span>'); 
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


opentable($locale['ar64']);
echo "<form name='inputform' method='post' id='inputform'>";
echo "<table width='100%'>";





echo "<tr><td width='250' class='tbl2'>".$locale['ar4']."</td><td class='tbl2'><input type='text' class='textbox' name='username' id='username' /></td><td class='tbl2' width='150'><div id='username_msg'><span style='color:red;'>* required</span></div></td></tr>";
 echo "<tr><td width='250' class='tbl2'>".$locale['ar5']."</td><td class='tbl2'><input type='password' class='textbox' name='password1' id='password1' /></td><td class='tbl2' width='150' rowspan='2'><div id='password_msg'><span style='color:red;'>* required</span></div></td></tr>";
 echo "<tr><td width='250' class='tbl2'>".$locale['ar6']."</td><td class='tbl2'><input type='password' class='textbox' name='password2' id='password2' /></td></tr>";
 echo "<tr><td width='250' class='tbl2'>".$locale['ar7']."</td><td class='tbl2'><input type='text' class='textbox' name='useremail' id='useremail' /></td><td class='tbl2' width='150'><div id='useremail_msg'><span style='color:red;'>* required</span></div></td></tr>"; 


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
echo "<tr><td class='tbl2' colspan='3' align='center'><input type='submit' class='button' name='new_app' value='".$locale['ar64']."' id='confirm' /></td></tr>";

echo "</table>";
echo "</form>";
closetable();




?>