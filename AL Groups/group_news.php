<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
 if (file_exists(INFUSIONS."al_group/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_groups/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_groups/locale/English.php";
}
require_once INFUSIONS."al_groups/infusion_db.php";
require_once INFUSIONS."al_groups/includes/functions.php"; 
require_once INCLUDES."bbcode_include.php";

 if (isset($_POST['vote_news'])) {
$vnew = dbarray(dbquery("SELECT * FROM ".DB_GS_VOTES_NEWS." WHERE vnews_id='".$_POST['vid']."'"));
if (!inLine($userdata['user_id'], $vnew['vnews_voted'])) {
if ($_POST['vote'] == "yes") {
if ($vnew['vnews_have']+1 ==  $vnew['vnews_need']) {
//accept
if ($vnew['vnews_type'] == "1") {
$insertuser = dbquery("UPDATE ".DB_GS_NEWS." SET news_published='1' WHERE news_id='".$vnew['vnews_news']."'");
} else {
$delnews = dbquery("DELETE FROM ".DB_GS_NEWS." WHERE news_id='".$vnew['vnews_news']."'");
}
$delvote = dbquery("DELETE FROM ".DB_GS_VOTES_NEWS." WHERE vnews_id='".$_POST['vid']."'");
} else {
// just ++
$have = $vnew['vnews_have']+1;
$voted = $vnew['vnews_voted'] == "" ? $userdata['user_id'] : $vnew['vnews_voted'].".".$userdata['user_id'];
$upduser = dbquery("UPDATE ".DB_GS_VOTES_NEWS." SET vnews_have='".$have."', vnews_voted='".$voted."'");
}
} else {
 if ($vnew['vnews_unhave']+1 ==  $vnew['vnews_need']) {
//decline
$delvote = dbquery("DELETE FROM ".DB_GS_VOTES_NEWS." WHERE vnews_id='".$_POST['vid']."'");
if ($vnew['vnews_type'] == "1") {
$delnews = dbquery("DELETE FROM ".DB_GS_NEWS." WHERE news_id='".$vnew['vnews_news']."'"); 
}
} else {
// just --
$have = $vnew['vnews_unhave']+1;
$voted = $vnew['vnews_voted'] == "" ? $userdata['user_id'] : $vnew['vnews_voted'].".".$userdata['user_id'];
$upduser = dbquery("UPDATE ".DB_GS_VOTES_NEWS." SET vnews_unhave='".$have."', vnews_voted='".$voted."'");
} 
}
}
if ($_POST['vote'] == "yes") {
redirect(BASEDIR."group_news.php?view=".$vnew['vnews_news']);
} else {
 redirect(BASEDIR."group.php?view=".$vnew['vnews_group']); 
}
} 

if (isset($_GET['view']) && isnum($_GET['view'])) {
$news = dbquery("SELECT gn.*, gr.group_name, us.user_name FROM ".DB_GS_NEWS." gn LEFT JOIN ".DB_GS_GROUPS." gr ON gr.group_id=gn.news_group LEFT JOIN ".DB_USERS." us ON us.user_id=gn.news_author WHERE news_id='".$_GET['view']."'");

if (dbrows($news)) {
$news = dbarray($news);
if ($news['news_published'] == "1" || inGroup($news['news_group'])) {

opentable($locale['gs48']);
echo "<table width='100%'><tr><td>".$news['news_title']."<br />".$locale['gs49']."<a href='".BASEDIR."profile.php?lookup=".$news['news_author']."'>".$news['user_name']."</a> @ <a href='".BASEDIR."group.php?view=".$news['news_group']."'>".$news['group_name']."</a> ".$locale['gs50']." ".showdate("longdate", $news['news_date'])."</td></tr><tr><td heigth='20'>&nbsp;</td></tr><tr><td>".parseubb($news['news_pre'])."</td></tr><tr><td heigth='20'>&nbsp;</td></tr><tr><td>".parseubb($news['news_news'])."</td></tr></table>";
closetable();

if ($news['news_published'] == "0" && inGroup($news['news_group'])) {
opentable($locale['gs51']);
$new_news = dbquery("SELECT * FROM ".DB_GS_VOTES_NEWS." WHERE vnews_news='".$_GET['view']."'");
if (dbrows($new_news)) {
//$tuser = dbcount("(guser_id)",DB_GS_GROUP_USERS, "guser_group='".$news['news_group']."'"); $new_new=dbarray($new_news);
echo $locale['gs52']." <strong style='color:green;'>".$new_new['vnews_have']." (".round(($new_new['vnews_have']/$new_new['vnews_need'])*100)."%)</strong> / <strong style='color:red;'>".$new_new['vnews_unhave']." (".round(($new_new['vnews_unhave']/$new_new['vnews_need'])*100)."%)</strong> ";

if (inLine($userdata['user_id'],$new_new['vnews_canvote']) && !inLine($userdata['user_id'],$new_new['vnews_voted'])) {
echo "<form name='request".$new_new['vnews_id']."yes' method='post'><input type='hidden' name='vid' value='".$new_new['vnews_id']."' /><input type='hidden' name='vote' value='yes' /><input type='submit' name='vote_news' class='button' value='".$locale['gs39']."' /></form> <form name='request".$new_new['vnews_id']."no' method='post'><input type='hidden' name='vid' value='".$new_new['vnews_id']."' /><input type='hidden' name='vote' value='no' /><input type='submit' name='vote_news' class='button' value='".$locale['gs40']."' /></form>";
}
}

closetable();
}


require_once INCLUDES."comments_include.php";
if ($news['news_published'] == "0") {
showcomments("GU", DB_GS_NEWS, "news_id", $_GET['view'], BASEDIR."group_news.php?view=".$_GET['view']); 
} else {
 showcomments("GP", DB_GS_NEWS, "news_id", $_GET['view'], BASEDIR."group_news.php?view=".$_GET['view']); 
}


} else {
echo "no access!";
}

} // dbrows

}

require_once THEMES."templates/footer.php";
?>
