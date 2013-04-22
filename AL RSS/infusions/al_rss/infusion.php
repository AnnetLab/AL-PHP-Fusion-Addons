<?php defined("IN_FUSION") or die("DEnied");
if (file_exists(INFUSIONS."al_rss/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_rss/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_rss/locale/Russian.php";
}

$inf_title = $locale['alrss1'];
$inf_description = $locale['alrss2'];
$inf_version = "1.0";
$inf_developer = "Rush @ AnnetLab.ru";
$inf_email = "johny64@gmail.com";
$inf_weburl = "http://annetlab.ru";

$inf_folder = "al_rss";

?>