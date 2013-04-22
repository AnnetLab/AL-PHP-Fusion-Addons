<?php
if (!defined("IN_FUSION")) die("acceess denied");

if (!defined("DB_GS_NEWS")) {
define("DB_GS_NEWS",DB_PREFIX."gs_news");
}
if (!defined("DB_GS_CATS")) {
define("DB_GS_CATS",DB_PREFIX."gs_cats");
} 
 if (!defined("DB_GS_GROUPS")) {
define("DB_GS_GROUPS",DB_PREFIX."gs_groups");
}
 if (!defined("DB_GS_GROUP_USERS")) {
define("DB_GS_GROUP_USERS",DB_PREFIX."gs_group_users");
}
 if (!defined("DB_GS_VOTES_USERS")) {
define("DB_GS_VOTES_USERS",DB_PREFIX."gs_votes_users");
}
 if (!defined("DB_GS_VOTES_NEWS")) {
define("DB_GS_VOTES_NEWS",DB_PREFIX."gs_votes_news");
}
 if (!defined("DB_GS_STATS_GROUPS")) {
define("DB_GS_STATS_GROUPS",DB_PREFIX."gs_stats_groups");
}
 if (!defined("DB_GS_VOTERS_GROUPS")) {
define("DB_GS_VOTERS_GROUPS",DB_PREFIX."gs_voters_groups");
}
 if (!defined("DB_GS_VOTES_RESULTS")) {
define("DB_GS_VOTES_RESULTS",DB_PREFIX."gs_votes_results");
}
 if (!defined("DB_GS_RESULTS")) {
define("DB_GS_RESULTS",DB_PREFIX."gs_results");
} 

?>
