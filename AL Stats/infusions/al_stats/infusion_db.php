<?php
if (!defined("IN_FUSION")) { die("Access Denied"); }

if (!defined("DB_ST_STATS")) {
    define("DB_ST_STATS", DB_PREFIX."st_stats");
}
if (!defined("DB_ST_TEAMS")) {
    define("DB_ST_TEAMS", DB_PREFIX."st_teams");
}
if (!defined("DB_ST_GAMES")) {
    define("DB_ST_GAMES", DB_PREFIX."st_games");
}


?>