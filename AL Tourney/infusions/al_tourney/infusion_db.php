<?php
if (!defined("IN_FUSION")) die("Access Denied");

if (!defined("DB_T_TOURS")) {
define("DB_T_TOURS", DB_PREFIX."t_tours");
}
if (!defined("DB_T_MATCHES")) {
define("DB_T_MATCHES", DB_PREFIX."t_matches");
} 
if (!defined("DB_T_PLAYERS")) {
define("DB_T_PLAYERS", DB_PREFIX."t_players");
} 
if (!defined("DB_T_NOTIFICATIONS")) {
define("DB_T_NOTIFICATIONS", DB_PREFIX."t_notifications");
}

?>
