<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
 if (file_exists(INFUSIONS."al_group/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_groups/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_groups/locale/English.php";
}
require_once INFUSIONS."/al_groups/infusion_db.php";
 require_once INFUSIONS."/al_groups/includes/functions.php"; 

 add_to_head("<script type='text/javascript' src='".INFUSIONS."al_groups/includes/fancybox/jquery.easing-1.3.pack.js'></script>");
 add_to_head("<script type='text/javascript' src='".INFUSIONS."al_groups/includes/fancybox/jquery.mousewheel-3.0.4.pack.js'></script>"); 
add_to_head("<script type='text/javascript' src='".INFUSIONS."al_groups/includes/fancybox/jquery.fancybox-1.3.4.js'></script>");
add_to_head("<link rel='stylesheet' href='".INFUSIONS."al_groups/includes/fancybox/jquery.fancybox-1.3.4.css' type='text/css' media='screen' />"); 

add_to_head("<script type='text/javascript'>

$(document).ready(function(){
$('.vote').fancybox({'type':'ajax'});
});

</script>"); 

 if (isset($_POST['vote_news'])) {
$vnew = dbarray(dbquery("SELECT * FROM ".DB_GS_VOTES_NEWS." WHERE vnews_id='".$_POST['vid']."'"));
if (!inLine($userdata['user_id'], $vnew['vnews_voted'])) {
if ($_POST['vote'] == "yes") {
if ($vnew['vnews_have']+1 ==  $vnew['vnews_need']) {
//accept
if ($vnew['vnews_type'] == "1") {
$insertuser = dbquery("UPDATE ".DB_GS_NEWS." SET news_published='1' WHERE news_id='".$vnew['vnews_news']."'");
} else {
// del
$delnews = dbquery("DELETE FROM ".DB_GS_NEWS." WHERE news_id='".$vnew['vnews_news']."'");
}
$delvote = dbquery("DELETE FROM ".DB_GS_VOTES_NEWS." WHERE vnews_id='".$_POST['vid']."'"); 
} else {
// just ++
$have = $vnew['vnews_have']+1;
$voted = $vnew['vnews_voted'] == "" ? $userdata['user_id'] : $vnew['vnews_voted'].".".$userdata['user_id'];
$upduser = dbquery("UPDATE ".DB_GS_VOTES_NEWS." SET vnews_have='".$have."', vnews_voted='".$voted."' WHERE vnews_id='".$_POST['vid']."'");
}
} else {
 if ($vnew['vnews_unhave']+1 ==  $vnew['vnews_need']) {
//decline
if ($vnew['vnews_type'] == "1") {
$delnews = dbquery("DELETE FROM ".DB_GS_NEWS." WHERE news_id='".$vnew['vnews_news']."'");
}
$delvote = dbquery("DELETE FROM ".DB_GS_VOTES_NEWS." WHERE vnews_id='".$_POST['vid']."'");
} else {
// just --
$have = $vnew['vnews_unhave']+1;
$voted = $vnew['vnews_voted'] == "" ? $userdata['user_id'] : $vnew['vnews_voted'].".".$userdata['user_id'];
$upduser = dbquery("UPDATE ".DB_GS_VOTES_NEWS." SET vnews_unhave='".$have."', vnews_voted='".$voted."' WHERE vnews_id='".$_POST['vid']."'");
} 
}
}
redirect(BASEDIR."group.php?view=".$vnew['vnews_group']);
} 

if (isset($_POST['vote_user'])) {
$vuser = dbarray(dbquery("SELECT * FROM ".DB_GS_VOTES_USERS." WHERE vuser_id='".$_POST['vid']."'"));
 if (!inLine($userdata['user_id'], $vuser['vuser_voted']) && inLine($userdata['user_id'], $vuser['vuser_canvote'])) { 
if ($_POST['vote'] == "yes") {
if ($vuser['vuser_have']+1 ==  $vuser['vuser_need']) {
//accept
if ($vuser['vuser_type'] == "1") {
$insertuser = dbquery("INSERT INTO ".DB_GS_GROUP_USERS." (guser_user, guser_group) VALUES ('".$vuser['vuser_user']."', '".$vuser['vuser_group']."')");
} else {
$deluser = dbquery("DELETE FROM ".DB_GS_GROUP_USERS." WHERE guser_user='".$vuser['vuser_user']."' AND guser_group='".$vuser['vuser_group']."'");
}
$delvote = dbquery("DELETE FROM ".DB_GS_VOTES_USERS." WHERE vuser_id='".$_POST['vid']."'");
} else {
// just ++
$have = $vuser['vuser_have']+1;
$voted = $vuser['vuser_voted'] == "" ? $userdata['user_id'] : $vuser['vuser_voted'].".".$userdata['user_id'];
$upduser = dbquery("UPDATE ".DB_GS_VOTES_USERS." SET vuser_have='".$have."', vuser_voted='".$voted."' WHERE vuser_id='".$_POST['vid']."'");
}
} else {
 if ($vuser['vuser_unhave']+1 ==  $vuser['vuser_need']) {
//decline
$delvote = dbquery("DELETE FROM ".DB_GS_VOTES_USERS." WHERE vuser_id='".$_POST['vid']."'");
} else {
// just --
$have = $vuser['vuser_unhave']+1;
$voted = $vuser['vuser_voted'] == "" ? $userdata['user_id'] : $vuser['vuser_voted'].".".$userdata['user_id'];
$upduser = dbquery("UPDATE ".DB_GS_VOTES_USERS." SET vuser_unhave='".$have."', vuser_voted='".$voted."' WHERE vuser_id='".$_POST['vid']."'");
} 
}
}
redirect(BASEDIR."group.php?view=".$vuser['vuser_group']);
}


if (isset($_GET['votedelete']) && isnum($_GET['votedelete'])) {
$gnews = dbarray(dbquery("SELECT * FROM ".DB_GS_NEWS." WHERE news_id='".$_GET['votedelete']."'"));
if (iMEMBER && inGroup($gnews['news_group']) && !checkDelete("n", $_GET['votedelete'])) {
 $members = dbcount("(guser_id)", DB_GS_GROUP_USERS, "guser_group='".$gnews['news_group']."'");
if ($members == 1 || $members == 2) {
$need = 1;
} else {
$need = ceil($members/2);
}
$members2 = dbquery("SELECT * FROM ".DB_GS_GROUP_USERS." WHERE guser_group='".$gnews['news_group']."'");
while ($member2=dbarray($members2)) {
if (!isset($can)) {
$can = $member2['guser_user'];
} else {
$can .= ".".$member2['guser_user'];
}
}

$voting = dbquery("INSERT INTO ".DB_GS_VOTES_NEWS." (vnews_news, vnews_group, vnews_type, vnews_voted, vnews_canvote, vnews_need, vnews_have, vnews_unhave) VALUES ('".$_GET['votedelete']."', '".$gnews['news_group']."', '2', '', '".$can."', '".$need."', '0', '0')");
 $cusers = dbquery("SELECT gu.*, us.user_email, gr.group_name FROM ".DB_GS_GROUP_USERS." gu LEFT JOIN  ".DB_USERS." us ON us.user_id=gu.guser_user LEFT JOIN ".DB_GS_GROUPS." gr ON gr.group_id=gu.guser_group WHERE guser_group='".$gnews['news_group']."'");
if (dbrows($cusers)) {
while ($cuser=dbarray($cusers)) {
require_once INCLUDES."sendmail_include.php";
$tit = $locale['gs66'].$settings['sitename'];
$msg = $locale['gs77']." <a href=\"".$settings['siteurl']."group.php?view=".$cuser['guser_group']."\">".$cuser['group_name']."</a> ".$locale['gs68']." <a href=\"".$settings['siteurl']."profile.php?lookup=".$userdata['user_id']."\">".$userdata['user_name']."</a>.".$locale['gs69'];
sendemail($cuser['user_name'], $cuser['user_email'], $settings['siteusername'], $settings['siteemail'], $tit, $msg,"html"); 
}
} 
}
redirect(BASEDIR."group.php?view=".$gnews['news_group']);
} elseif (isset($_GET['votekick']) && isnum($_GET['votekick'])) {
$guser = dbarray(dbquery("SELECT * FROM ".DB_GS_GROUP_USERS." WHERE guser_id='".$_GET['votekick']."'"));
if (iMEMBER && inGroup($guser['guser_group']) && !checkDelete("u", $guser['guser_user'], $guser['guser_group'])) {
 $members = dbcount("(guser_id)", DB_GS_GROUP_USERS, "guser_group='".$guser['guser_group']."'");
if ($members == 1 || $members == 2) {
$need = 1;
} else {
$need = ceil($members/2);
}
$members2 = dbquery("SELECT * FROM ".DB_GS_GROUP_USERS." WHERE guser_group='".$guser['guser_group']."'");
while ($member2=dbarray($members2)) {
if (!isset($can)) {
$can = $member2['guser_user'];
} else {
$can .= ".".$member2['guser_user'];
}
}

$kick = dbquery("INSERT INTO ".DB_GS_VOTES_USERS." (vuser_user, vuser_group, vuser_type, vuser_voted, vuser_canvote, vuser_need, vuser_have, vuser_unhave) VALUES ('".$guser['guser_user']."', '".$guser['guser_group']."', '2', '', '".$can."', '".$need."', '0', '0')");
$cusers = dbquery("SELECT gu.*, us.user_email, gr.group_name FROM ".DB_GS_GROUP_USERS." gu LEFT JOIN  ".DB_USERS." us ON us.user_id=gu.guser_user LEFT JOIN ".DB_GS_GROUPS." gr ON gr.group_id=gu.guser_group WHERE guser_group='".$guser['guser_group']."'");
if (dbrows($cusers)) {
while ($cuser=dbarray($cusers)) {
require_once INCLUDES."sendmail_include.php";
$tit = $locale['gs66'].$settings['sitename'];
$msg = $locale['gs76']." <a href=\"".$settings['siteurl']."group.php?view=".$cuser['guser_group']."\">".$cuser['group_name']."</a> ".$locale['gs68']." <a href=\"".$settings['siteurl']."profile.php?lookup=".$userdata['user_id']."\">".$userdata['user_name']."</a>.".$locale['gs69'];
sendemail($cuser['user_name'], $cuser['user_email'], $settings['siteusername'], $settings['siteemail'], $tit, $msg,"html");
} 
} 
}
redirect(BASEDIR."group.php?view=".$guser['guser_group']);
} elseif (isset($_GET['action']) && $_GET['action'] == "join" && isset($_GET['id']) && isnum($_GET['id'])) {
if (iMEMBER && !inGroup($_GET['id']) && !checkJoin($_GET['id'])) {
$members = dbcount("(guser_id)", DB_GS_GROUP_USERS, "guser_group='".$_GET['id']."'");

if ($members == 0) {
//
$insuser = dbquery("INSERT INTO ".DB_GS_GROUP_USERS." (guser_user, guser_group) VALUES ('".$userdata['user_id']."', '".$_GET['id']."')");
$upd = dbquery("UPDATE ".DB_GS_GROUPS." SET group_creator='".$userdata['user_id']."'");
} else {
if ($members == 1 || $members == 2) {
$need = 1;
} else {
$need = ceil($members/2);
}
$members2 = dbquery("SELECT * FROM ".DB_GS_GROUP_USERS." WHERE guser_group='".$_GET['id']."'");
while ($member2=dbarray($members2)) {
if (!isset($can)) {
$can = $member2['guser_user'];
} else {
$can .= ".".$member2['guser_user'];
}
}

$join = dbquery("INSERT INTO ".DB_GS_VOTES_USERS." (vuser_user, vuser_group, vuser_type, vuser_voted, vuser_canvote, vuser_need, vuser_have, vuser_unhave) VALUES ('".$userdata['user_id']."', '".$_GET['id']."', '1', '', '".$can."', '".$need."', '0', '0')");
$cusers = dbquery("SELECT gu.*, us.user_email, gr.group_name FROM ".DB_GS_GROUP_USERS." gu LEFT JOIN  ".DB_USERS." us ON us.user_id=gu.guser_user LEFT JOIN ".DB_GS_GROUPS." gr ON gr.group_id=gu.guser_group WHERE guser_group='".$_GET['id']."'");
if (dbrows($cusers)) {
while ($cuser=dbarray($cusers)) {
require_once INCLUDES."sendmail_include.php";
$tit = $locale['gs66'].$settings['sitename'];
$msg = $locale['gs67']." <a href=\"".$settings['siteurl']."group.php?view=".$cuser['guser_group']."\">".$cuser['group_name']."</a> ".$locale['gs68']." <a href=\"".$settings['siteurl']."profile.php?lookup=".$userdata['user_id']."\">".$userdata['user_name']."</a>.".$locale['gs69'];
sendemail($cuser['user_name'], $cuser['user_email'], $settings['siteusername'], $settings['siteemail'], $tit, $msg,"html");
} 
}
}
}
redirect(BASEDIR."group.php?view=".$_GET['id']);
} elseif (isset($_GET['action']) && $_GET['action'] == "leave" && isset($_GET['id']) && isnum($_GET['id'])) {
$leave = dbquery("SELECT * FROM ".DB_GS_GROUP_USERS." WHERE guser_user='".$userdata['user_id']."' AND guser_group='".$_GET['id']."'");
if (dbrows($leave)) {
$leave = dbarray($leave);
if ($userdata['user_id'] == $leave['guser_user']) {
//leave 
$deluse = dbquery("DELETE FROM ".DB_GS_GROUP_USERS." WHERE guser_id='".$leave['guser_id']."'");
if (checkDelete("u",$leave['guser_user'], $leave['guser_group'])) {
$delvot = dbquery("DELETE FROM ".DB_GS_VOTES_USERS." WHERE vuser_user='".$leave['guser_user']."' AND vuser_group='".$leave['guser_group']."'");
}
}
redirect(BASEDIR."group.php?view=".$leave['guser_group']); 
} else {
redirect(BASEDIR."groups.php");
}
} elseif (isset($_GET['action']) && $_GET['action'] == "kick" && isset($_GET['id']) && isnum($_GET['id'])) { 
$kick = dbquery("SELECT gu.*, gr.group_creator FROM ".DB_GS_GROUP_USERS." gu LEFT JOIN ".DB_GS_GROUPS." gr ON gr.group_id=gu.guser_group WHERE guser_id='".$_GET['id']."'");
if (dbrows($kick)) {
$kick = dbarray($kick);
if (iMEMBER && $userdata['user_id'] == $kick['group_creator'] && $kick['guser_user'] != $kick['group_creator']) {
$deluser = dbquery("DELETE FROM ".DB_GS_GROUP_USERS." WHERE guser_id='".$kick['guser_id']."'");
if (checkDelete("u", $kick['guser_user'], $kick['guser_group'])) {
$delvote = dbquery("DELETE FROM ".DB_GS_VOTES_USERS." WHERE vuser_user='".$kick['guser_user']."' AND vuser_group='".$kick['guser_group']."'");
}
}
 redirect(BASEDIR."group.php?view=".$kick['guser_group']); 
} else {
 redirect(BASEDIR."groups.php"); 
}
} elseif (isset($_GET['action']) && $_GET['action'] == "del" && isset($_GET['id']) && isnum($_GET['id'])) {
$delnew = dbquery("SELECT gn.*, gr.group_creator FROM ".DB_GS_NEWS." gn LEFT JOIN ".DB_GS_GROUPS." gr ON gr.group_id=gn.news_group WHERE news_id='".$_GET['id']."'");
if (dbrows($delnew)) {
 $delnew = dbarray($delnew); 
if (iMEMBER && $userdata['user_id'] == $delnew['group_creator']) {
$deln = dbquery("DELETE FROM ".DB_GS_NEWS." WHERE news_id='".$delnew['news_id']."'");
if (checkDelete("n", $delnew['news_id'])) {
$delv = dbquery("DELETE FROM ".DB_GS_VOTES_NEWS." WHERE vnews_news='".$delnew['news_id']."'");
}
}
 redirect(BASEDIR."group.php?view=".$delnew['news_group']); 
} else {
redirect(BASEDIR."groups.php"); 
}
} elseif (isset($_GET['view']) && isnum($_GET['view'])) {
$group = dbarray(dbquery("SELECT gr.*, gc.*, gv.voter_id FROM ".DB_GS_GROUPS." gr LEFT JOIN ".DB_GS_CATS." gc ON gc.cat_id=gr.group_cat LEFT JOIN ".DB_GS_VOTERS_GROUPS." gv ON gv.voter_ip='".USER_IP."' AND gv.voter_group=gr.group_id WHERE group_id='".$_GET['view']."'"));

if (isset($_GET['voteshare'])) {
$checkk = dbquery("SELECT * FROM ".DB_GS_VOTERS_GROUPS." WHERE voter_ip='".USER_IP."' AND voter_group='".$_GET['view']."'");
if (dbrows($checkk)) {
 add_to_head("<script type='text/javascript'>
$(document).ready(function(){
$.fancybox({'type':'ajax',href:'rate.php?id=already'});
});
</script>"); 
} else {
 add_to_head("<script type='text/javascript'>
$(document).ready(function(){
$.fancybox({'type':'ajax',href:'rate.php?id=".$_GET['view']."'});
});
</script>"); 
}
}

opentable($locale['gs30']);
echo "<table width='100%' style='min-width:500px;'><tr><td class='tbl1' width='60%'><img src='".INFUSIONS."al_groups/images/".($group['group_image'] != "0" ? $group['group_image'] : "no.jpg")."' border='0' style='float:left;padding:5px;'".($group['group_image'] == "0" ? " width='200'" : "")." /><strong>".$group['group_name']." ".($group['group_creator'] == $userdata['user_id'] || checkrights("GS") ? "<a href='".BASEDIR."group_admin.php?action=edit&id=".$_GET['view']."'><img src='".IMAGES."edit.png' width='10' /></a>" : "")."</strong><br />".$group['cat_name']."</td><td class='tbl1' align='center' valign='middle'>";

$lower = dbcount("(DISTINCT group_stat)",DB_GS_GROUPS,"group_stat>'".$group['group_stat']."'");
$place = $lower == 0 ? 1 : $lower+1;
echo "<strong style='font-size:1.8em'>".$place.$locale['gs56']."</strong><br />".$group['group_stat'].$locale['gs58']."<br />";
if (!$group['voter_id']) {
echo "<a href='".BASEDIR."rate.php?id=".$group['group_id']."' class='vote'>".$locale['gs57']."</a>";
} else {
 echo "<a href='rate.php?id=already' class='vote'>".$locale['gs59']."</a>"; 
}

echo "</td></tr></table>";
closetable();

$lnews = dbquery("SELECT gn.*, us.user_name FROM ".DB_GS_NEWS." gn LEFT JOIN ".DB_USERS." us ON us.user_id=gn.news_author WHERE news_published='1' AND news_group='".$_GET['view']."' ORDER BY news_date DESC LIMIT 1");

if (dbrows($lnews)) {
$lnews = dbarray($lnews);
opentable($locale['gs73']);
echo "<strong style='font-size:1.8em'>".$lnews['news_title']."</strong><br />";
echo $locale['gs49']."<a href='".BASEDIR."profile.php?lookup=".$lnews['news_author']."'>".$lnews['user_name']."</a> ".$locale['gs50']." ".showdate("longdate", $lnews['news_date'])."<br /><br />";
echo parseubb($lnews['news_pre']);
echo "<br /><a href='".BASEDIR."group_news.php?view=".$lnews['news_id']."'>".$locale['gs74']."</a>";
closetable();
}

openside($locale['gs31'], true, "on");
echo "<table width='100%'><tr valign='top'><td width='50%'>".$locale['gs34']."<br />";
//users
$users = dbquery("SELECT gu.*, us.user_name FROM ".DB_GS_GROUP_USERS." gu LEFT JOIN ".DB_USERS." us ON us.user_id=gu.guser_user WHERE guser_group='".$_GET['view']."'");
if (iMEMBER && !inGroup($_GET['view']) && !checkJoin($_GET['view']) ) {
// join
echo "<a href='".BASEDIR."group.php?action=join&id=".$_GET['view']."'>".$locale['gs33']."</a><br />";
}
if (iMEMBER && inGroup($_GET['view'])) {
// leave
 echo "<a href='".BASEDIR."group.php?action=leave&id=".$_GET['view']."'>".$locale['gs81']."</a><br />";
 
}
if (inGroup($_GET['view'])) {
$new_users = dbquery("SELECT vu.*, us.user_name FROM ".DB_GS_VOTES_USERS." vu LEFT JOIN ".DB_USERS." us ON us.user_id=vu.vuser_user WHERE vuser_group='".$_GET['view']."'");
if (dbrows($new_users)) {
//$tuser = dbcount("(guser_id)",DB_GS_GROUP_USERS, "guser_group='".$_GET['view']."'");
while ($new_user=dbarray($new_users)) {
echo ($new_user['vuser_type'] == "1" ? $locale['gs38'] : $locale['gs75'])."<a href='".BASEDIR."profile.php?lookup=".$new_user['vuser_user']."'>".$new_user['user_name']."</a> <strong style='color:green;'>".$new_user['vuser_have']." (".round(($new_user['vuser_have']/$new_user['vuser_need'])*100)."%)</strong> / <strong style='color:red;'>".$new_user['vuser_unhave']." (".round(($new_user['vuser_unhave']/$new_user['vuser_need'])*100)."%)</strong> ";

if (inLine($userdata['user_id'],$new_user['vuser_canvote']) && !inLine($userdata['user_id'],$new_user['vuser_voted'])) {
echo "<form name='request".$new_user['vuser_id']."yes' method='post'><input type='hidden' name='vid' value='".$new_user['vuser_id']."' /><input type='hidden' name='vote' value='yes' /><input type='submit' name='vote_user' class='button' value='".$locale['gs39']."' /></form> <form name='request".$new_user['vuser_id']."no' method='post'><input type='hidden' name='vid' value='".$new_user['vuser_id']."' /><input type='hidden' name='vote' value='no' /><input type='submit' name='vote_user' class='button' value='".$locale['gs40']."' /></form>";
}
echo "<br />";
}
}
}

if (dbrows($users)) {
$i = 1;
echo "<table width='95%'>";
while ($user=dbarray($users)) {
echo "<tr><td width='1%'>".$i."</td><td><a href='".BASEDIR."profile.php?lookup=".$user['guser_user']."'>".$user['user_name']."</a>";
/* ".(iMEMBER && $userdata['user_id'] == $group['group_creator'] && $user['guser_user'] != $group['group_creator'] ? "<a href='".BASEDIR."group.php?action=kick&id=".$user['guser_id']."'><img src='".IMAGES."no.png' width='10' alt='kick' /></a>" : "")."*/
echo " ".(iMEMBER && inGroup($_GET['view']) && $user['guser_user'] != $userdata['user_id'] && $user['guser_user'] != $group['group_creator'] && !checkDelete("u", $user['guser_user'], $user['guser_group']) ? "<a href='".BASEDIR."group.php?votekick=".$user['guser_id']."'><img src='".IMAGES."no.png' width='10' alt='votekick' /></a>" : "")."</td></tr>";
$i++;
}
echo "</table>";
} else {
echo $locale['gs32'];
}
echo "</td><td>".$locale['gs35']."<br />";
if (iMEMBER && inGroup($_GET['view'])) {
echo "<a href='".BASEDIR."news_admin.php?action=add&id=".$_GET['view']."'>".$locale['gs37']."</a><br />";
} 
//news
$news = dbquery("SELECT * FROM ".DB_GS_NEWS." WHERE news_group='".$_GET['view']."' AND news_published='1'");

 if (inGroup($_GET['view'])) {
$new_news = dbquery("SELECT vn.*, nn.news_title,nn.news_author,nn.news_id FROM ".DB_GS_VOTES_NEWS." vn LEFT JOIN ".DB_GS_NEWS." nn ON nn.news_id=vn.vnews_news WHERE vnews_group='".$_GET['view']."'");
if (dbrows($new_news)) {
//$tuser = dbcount("(guser_id)",DB_GS_GROUP_USERS, "guser_group='".$_GET['view']."'");
while ($new_new=dbarray($new_news)) {
echo ($new_new['vnews_type'] == "1" ? $locale['gs47'] : $locale['gs78'])."<a href='".BASEDIR."group_news.php?view=".$new_new['vnews_news']."'>".$new_new['news_title']."</a>".($new_new['news_author'] == $userdata['user_id'] ? " <a href='".BASEDIR."news_admin.php?action=edit&id=".$new_new['news_id']."'><img src='".IMAGES."edit.png' width='10' /></a> <a href='".BASEDIR."news_admin.php?action=delete&id=".$new_new['news_id']."'><img src='".IMAGES."no.png' width='10' /></a>" : "")." <strong style='color:green;'>".$new_new['vnews_have']." (".round(($new_new['vnews_have']/$new_new['vnews_need'])*100)."%)</strong> / <strong style='color:red;'>".$new_new['vnews_unhave']." (".round(($new_new['vnews_unhave']/$new_new['vnews_need'])*100)."%)</strong> ";

if (inLine($userdata['user_id'],$new_new['vnews_canvote']) && !inLine($userdata['user_id'],$new_new['vnews_voted'])) {
echo "<form name='request".$new_new['vnews_id']."yes' method='post'><input type='hidden' name='vid' value='".$new_new['vnews_id']."' /><input type='hidden' name='vote' value='yes' /><input type='submit' name='vote_news' class='button' value='".$locale['gs39']."' /></form> <form name='request".$new_new['vnews_id']."no' method='post'><input type='hidden' name='vid' value='".$new_new['vnews_id']."' /><input type='hidden' name='vote' value='no' /><input type='submit' name='vote_news' class='button' value='".$locale['gs40']."' /></form>";
}
echo "<br />";
}
}
} 

if (dbrows($news)) {
 $i = 1;
echo "<table width='95%'>";
while ($new=dbarray($news)) {
echo "<tr><td width='1%'>".$i."</td><td><a href='".BASEDIR."group_news.php?view=".$new['news_id']."'>".$new['news_title']."</a>";
/* ".(iMEMBER && $userdata['user_id'] == $group['group_creator'] ? "<a href='".BASEDIR."group.php?action=del&id=".$new['news_id']."'><img src='".IMAGES."no.png' width='10' alt='delete' /></a>" : "")."*/
echo " ".(iMEMBER && inGroup($_GET['view']) && !checkDelete("n", $new['news_id']) ? "<a href='".BASEDIR."group.php?votedelete=".$new['news_id']."'><img src='".IMAGES."no.png' width='10' alt='votedelete' /></a>" : "")."</td></tr>";
}
echo "</table>"; 
} else {
echo $locale['gs36'];
}

echo "</td></tr></table>";
closeside();

openside($locale['gs64'], true, "on");
echo "<table width='100%'><tr valign='middle'><td class='tbl1' width='40%' align='center'>".$locale['gs65']."<br /><img src='".INFUSIONS."al_groups/images/voteshare.gif' border='0' /></td><td class='tbl1' align='center'><textarea name='guyhubcg' class='textbox' rows='4' cols='30'><a href=\"".$settings['siteurl']."group.php?view=".$group['group_id']."&voteshare\" target=\"_blank\" alt=\"Vote!\"><img src=\"".$settings['siteurl']."al_groups/images/voteshare.gif\" border=\"0\" /></a></textarea></td></tr></table>";
closeside();


} else {
redirect(BASEDIR."groups.php");
}


require_once THEMES."templates/footer.php";
?>
