<?php defined("IN_FUSION") or die("Denied");

if (!defined("DB_GEM_SETTINGS")) {
    define("DB_GEM_SETTINGS",DB_PREFIX."gem_settings");
}
if (!defined("DB_GEM_GENERATORS")) {
    define("DB_GEM_GENERATORS",DB_PREFIX."gem_generators");
}
if (!defined("DB_GEM_MEMS")) {
    define("DB_GEM_MEMS",DB_PREFIX."gem_mems");
}
$result = dbquery("SELECT * FROM ".DB_GEM_SETTINGS);
if ($result && dbrows($result)) {
    $genmem_settings = dbarray($result);
}

?>