<?php defined("IN_FUSION") or die("Denied");

// DB table's defines
if (!defined("DB_AL_BLOG_SETTINGS")) {
    define("DB_AL_BLOG_SETTINGS",DB_PREFIX."al_blog_settings");
}
if (!defined("DB_AL_BLOG_POSTS")) {
    define("DB_AL_BLOG_POSTS",DB_PREFIX."al_blog_posts");
}

if (!defined("DB_AL_BLOG_CATEGORIES")) {
    define("DB_AL_BLOG_CATEGORIES",DB_PREFIX."al_blog_categories");
}

// Dirs
if (!defined("AL_BLOG_DIR")) {
    define("AL_BLOG_DIR",INFUSIONS."al_blog/");
}

if (!defined("AL_BLOG_ASSET_DIR")) {
    define("AL_BLOG_ASSET_DIR",AL_BLOG_DIR."asset/");
}

?>