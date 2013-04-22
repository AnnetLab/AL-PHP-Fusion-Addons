<?php
if (!defined("IN_FUSION")) die("fu");
 require_once INFUSIONS."al_content_panel/infusion_db.php";
if (file_exists(INFUSIONS."al_content_panel/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_content_panel/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_content_panel/locale/Russian.php";
}

$csettings = dbarray(dbquery("SELECT * FROM ".DB_CO_SETTINGS.""));

global $lastvisited;

if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); } 


$title = sprintf($locale['co15'],$csettings['co_time']); 
openside($title);
//echo "<table width='100%'>";


$ctime = time() - 3600*$csettings['co_time'];

// news
if ($csettings['co_news'] == "1") {
$result = dbquery("SELECT news_id,news_subject, news_datestamp FROM ".DB_NEWS." WHERE news_datestamp>'".$ctime."' ORDER BY news_id DESC");
if (dbrows($result)) {
echo "<div class='side-label small'><strong>".$locale['co16']."</strong></div><div class='small' style='margin-bottom:3px'> ";
while ($data=dbarray($result)) {
echo "<a href='".BASEDIR."news.php?readmore=".$data['news_id']."'>".(strlen($data['news_subject']) > $csettings['co_len'] ? trimlink($data['news_subject'],$csettings['co_len']) : $data['news_subject'])."</a><span style='float:right;'>".($data['news_datestamp'] > $lastvisited ? $locale['co14'] : "")."</span><br />";
}
echo "</div>";
}
}

// articles
if ($csettings['co_articles'] == "1") {
$result = dbquery("SELECT article_id,article_subject, article_datestamp FROM ".DB_ARTICLES." WHERE article_datestamp>'".$ctime."' ORDER BY article_id DESC");
if (dbrows($result)) {
echo "<div class='side-label small'><strong>".$locale['co24']."</strong></div><div class='small' style='margin-bottom:3px'>"; 
while ($data=dbarray($result)) {
echo "<a href='".BASEDIR."articles.php?article_id=".$data['article_id']."'>".(strlen($data['article_subject']) > $csettings['co_len'] ? trimlink($data['article_subject'],$csettings['co_len']) : $data['article_subject'])."</a><span style='float:right;'>".($data['article_datestamp'] > $lastvisited ? $locale['co14'] : "")."</span><br />"; 
}
 echo "</div>"; 
}
} 


// weblinks 
 if ($csettings['co_weblinks'] == "1") {
$result = dbquery("SELECT weblink_id,weblink_name, weblink_datestamp,weblink_cat FROM ".DB_WEBLINKS." WHERE weblink_datestamp>'".$ctime."' ORDER BY weblink_id DESC");
if (dbrows($result)) {
echo "<div class='side-label small'><strong>".$locale['co17']."</strong></div><div class='small' style='margin-bottom:3px'>"; 
while ($data=dbarray($result)) {
echo "<a href='".BASEDIR."weblinks.php?cat_id=".$data['weblink_cat']."&weblink_id=".$data['weblink_id']."'>".(strlen($data['weblink_name']) > $csettings['co_len'] ? trimlink($data['weblink_name'],$csettings['co_len']) : $data['weblink_name'])."</a><span style='float:right;'>".($data['weblink_datestamp'] > $lastvisited ? $locale['co14'] : "")."</span><br />"; 
}
 echo "</div>"; 
}
} 


// downloads
 if ($csettings['co_downloads'] == "1") {
$result = dbquery("SELECT download_id,download_title, download_datestamp,download_cat FROM ".DB_DOWNLOADS." WHERE download_datestamp>'".$ctime."' ORDER BY download_id DESC");
if (dbrows($result)) {
echo "<div class='side-label small'><strong>".$locale['co18']."</strong></div><div class='small' style='margin-bottom:3px'>"; 
while ($data=dbarray($result)) {
echo "<a href='".BASEDIR."downloads.php?cat_id=".$data['download_cat']."&download_id=".$data['download_id']."'>".(strlen($data['download_title']) > $csettings['co_len'] ? trimlink($data['download_title'],$csettings['co_len']) : $data['download_title'])."</a><span style='float:right;'>".($data['download_datestamp'] > $lastvisited ? $locale['co14'] : "")."</span><br />"; 
}
 echo "</div>"; 
}
} 

// comments
if ($csettings['co_comments'] == "1") {
 $com_data = array(
"N"=>array("table"=>DB_NEWS." ne", "title_field"=>"ne.news_subject", "type"=>"news","id_field"=>"ne.news_id","link1"=>BASEDIR."news.php","link2"=>BASEDIR."news.php?readmore="),
"A"=>array("table"=>DB_ARTICLES." bs", "title_field"=>"bs.article_subject", "type"=>"article", "id_field"=>"bs.article_id", "link1"=>BASEDIR."articles.php","link2"=>BASEDIR."articles.php?article_id="),
"D"=>array("table"=>DB_DOWNLOADS." mo", "title_field"=>"mo.download_title", "type"=>"downl","id_field"=>"mo.download_id", "link1"=>BASEDIR."downloads.php","link2"=>BASEDIR."downloads.php?download_id="),
"P"=>array("table"=>DB_PHOTOS." dv", "title_field"=>"dv.photo_title", "type"=>"photo","id_field"=>"dv.photo_id","link1"=>BASEDIR."photogallery.php","link2"=>BASEDIR."photogallery.php?photo_id=") 
);

$result = dbquery("SELECT * FROM ".DB_COMMENTS." WHERE comment_datestamp>'".$ctime."' ORDER BY comment_id DESC");
if (dbrows($result)) {
 echo "<div class='side-label small'><strong>".$locale['co19']."</strong></div><div class='small' style='margin-bottom:3px;'>"; 
while ($data=dbarray($result)) {

$cdata = $com_data[$data['comment_type']];
$data2 = dbarray(dbquery("SELECT us.user_name, ".$cdata['title_field']." AS item_title FROM ".DB_USERS." us LEFT JOIN ".$cdata['table']." ON ".$cdata['id_field']."='".$data['comment_item_id']."' WHERE user_id='".$data['comment_name']."'"));
echo (strlen($data['comment_message']) > $csettings['co_len'] ? trimlink($data['comment_message'], $csettings['co_len']) : $data['comment_message']).$locale['co22']."<a href='".BASEDIR."profile.php?lookup=".$data['comment_name']."'>".$data2['user_name']."</a>".$locale['co23']."<a href='".$cdata['link2'].$data['comment_item_id']."#comments'>".$data2['item_title']."</a><span style='float:right;'>".($data['comment_datestamp'] > $lastvisited ? $locale['co14'] : "")."</span><br />";
}
echo "</div>";
}
}

// photos
 if ($csettings['co_photos'] == "1") {
$result = dbquery("SELECT photo_id,photo_title, photo_datestamp,photo_thumb1, album_id FROM ".DB_PHOTOS." WHERE photo_datestamp>'".$ctime."' ORDER BY photo_id DESC");
if (dbrows($result)) {
echo "<div class='side-label small'><strong>".$locale['co20']."</strong></div><div class='small' style='margin-bottom:3px'>"; 
while ($data=dbarray($result)) {
echo "<a href='".BASEDIR."photogallery.php?photo_id=".$data['photo_id']."'><img src='".IMAGES."photoalbum/album_".$data['album_id']."/".$data['photo_thumb1']."' /> ".(strlen($data['photo_title']) > $csettings['co_len'] ? trimlink($data['photo_title'],$csettings['co_len']) : $data['photo_title'])."</a><span style='float:right;'>".($data['photo_datestamp'] > $lastvisited ? $locale['co14'] : "")."</span><br />"; 
}
 echo "</div>"; 
}
} 

// forums
if ($csettings['co_forums'] == "1") {
$result = dbquery("SELECT tt.thread_id, tt.thread_subject, tt.thread_lastpost, tf.forum_id, tf.forum_access, tt.thread_lastpostid, tt.thread_lastuser, tt.thread_postcount FROM ".DB_THREADS." tt INNER JOIN ".DB_FORUMS." tf ON tt.forum_id=tf.forum_id WHERE ".groupaccess('tf.forum_access')." AND tt.thread_lastpost >= ".$ctime." AND tt.thread_hidden='0' ORDER BY tt.thread_lastpost DESC");

if (dbrows($result)) {
 echo "<div class='side-label small'><strong>".$locale['co21']."</strong></div><div class='small' style='margin-bottom:3px'>"; 
while ($data=dbarray($result)) {
if ($data['thread_lastpost'] > $lastvisited) {
$thread_match = $data['thread_id']."\|".$data['thread_lastpost']."\|".$data['forum_id'];
if (iMEMBER && ($data['thread_lastuser'] == $userdata['user_id'] || preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads']))) {
$new = "";
} else {
$new = $locale['co14'];
}
} else {
$new = "";
}

echo "<a href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."&amp;pid=".$data['thread_lastpostid']."#post_".$data['thread_lastpostid']."' title='".$data['thread_subject']."'>".trimlink($data['thread_subject'], $csettings['co_len'])."</a><span style='float:right;'>".$new."</span><br />"; 

}
 echo "</div>"; 
}
}

//echo "</table>";
closeside(); 


?>
