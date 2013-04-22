<?php
if (!defined("IN_FUSION")) die("fu");

if (!defined("DB_SS_STREAMS")) {
define("DB_SS_STREAMS",DB_PREFIX."st_streams");
}
if (!defined("DB_SS_SETTINGS")) {
define("DB_SS_SETTINGS",DB_PREFIX."st_settings");
} 
if (!defined("DB_SS_CHAT_MESSAGES")) {
define("DB_SS_CHAT_MESSAGES",DB_PREFIX."st_chat_messages");
} 
if (!defined("DB_SS_CHAT_ONLINE")) {
define("DB_SS_CHAT_ONLINE",DB_PREFIX."st_chat_online");
} 
?>
