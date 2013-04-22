<?php
if (!isset($_GET['id']) && !isnum($_GET['id']) && !iMEMBER) {
redirect(BASEDIR);
}
require_once THEMES."templates/header.php";
$check = dbquery("SELECT * FROM ".DB_T_NOTIFICATIONS." WHERE noti_id='".$_GET['id']."'");
if (dbrows($check)) {
$info = dbarray($check);
if ($info['noti_user'] == $userdata['user_id']) {
$del = dbquery("DELETE FROM ".DB_T_NOTIFICATIONS." WHERE noti_id='".$_GET['id']."'");
 redirect(BASEDIR."index.php"); 
}
}
redirect(BASEDIR."index.php");
 require_once THEMES."templates/footer.php"; 
?>
