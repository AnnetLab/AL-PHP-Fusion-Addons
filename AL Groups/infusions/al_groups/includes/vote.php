<?php
require_once "../../../maincore.php";
require_once INFUSIONS."al_groups/infusion_db.php";
// check captcha

require_once INCLUDES."captchas/securimage2/captcha_check.php";

if (isset($_CAPTCHA_IS_VALID) && $_CAPTCHA_IS_VALID == true) {
$check = dbquery("SELECT * FROM ".DB_GS_VOTERS_GROUPS." WHERE voter_group='".$_POST['gid']."' AND voter_ip='".$_POST['ip']."'");
if (!dbrows($check)) {
$cur = dbarray(dbquery("SELECT group_stat FROM ".DB_GS_GROUPS." WHERE group_id='".$_POST['gid']."'"));
$stat = $cur['group_stat']+1;
$vote = dbquery("UPDATE ".DB_GS_GROUPS." SET group_stat='".$stat."' WHERE group_id='".$_POST['gid']."'");
// ins voter
$voter = dbquery("INSERT INTO ".DB_GS_VOTERS_GROUPS." (voter_ip, voter_group, voter_date) VALUES ('".$_POST['ip']."','".$_POST['gid']."','".time()."')");
}
}
redirect(BASEDIR."groups.php");
?>
