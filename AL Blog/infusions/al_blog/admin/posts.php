<?php defined("IN_FUSION") or die();

if (isset($_GET['approve']) && isnum($_GET['approve'])) {

    if(dbrows(dbquery("SELECT * FROM ".DB_AL_BLOG_POSTS." WHERE alb_post_id='".$_GET['approve']."'"))) {
        dbquery("UPDATE ".DB_AL_BLOG_POSTS." SET alb_post_status='1' WHERE alb_post_id='".$_GET['approve']."'");
    }
    redirect(FUSION_SELF.$aidlink."&p=posts");

}

if (isset($_GET['unapprove']) && isnum($_GET['unapprove'])) {

    if(dbrows(dbquery("SELECT * FROM ".DB_AL_BLOG_POSTS." WHERE alb_post_id='".$_GET['unapprove']."'"))) {
        dbquery("UPDATE ".DB_AL_BLOG_POSTS." SET alb_post_status='0' WHERE alb_post_id='".$_GET['unapprove']."'");
    }
    redirect(FUSION_SELF.$aidlink."&p=posts");

}

if (isset($_GET['delete']) && isnum($_GET['delete'])) {

    if(dbrows(dbquery("SELECT * FROM ".DB_AL_BLOG_POSTS." WHERE alb_post_id='".$_GET['delete']."'"))) {
        dbquery("DELETE FROM ".DB_AL_BLOG_POSTS." WHERE alb_post_id='".$_GET['delete']."'");
    }
    redirect(FUSION_SELF.$aidlink."&p=posts");

}


if (!isset($_GET['rowstart1']) || !isnum($_GET['rowstart1'])) $_GET['rowstart1'] = 0;
if (!isset($_GET['rowstart2']) || !isnum($_GET['rowstart2'])) $_GET['rowstart2'] = 0;

opentable($locale['alb36']);
$total_un = dbcount("(alb_post_id)",DB_AL_BLOG_POSTS,"alb_post_status='0'");
$result = dbquery("SELECT p.*,pc.*,u.user_name FROM ".DB_AL_BLOG_POSTS." p LEFT JOIN ".DB_AL_BLOG_CATEGORIES." pc ON pc.alb_cat_id=p.alb_post_cat LEFT JOIN ".DB_USERS." u ON u.user_id=p.alb_post_user WHERE alb_post_status='0' ORDER BY alb_post_datestamp DESC LIMIT ".$_GET['rowstart1'].",30");
if (dbrows($result)) {
    echo "<table width='100%'>";
        echo "<tr>";
            echo "<td class='tbl2'><strong>#</strong></td>";
            echo "<td class='tbl2'><strong>".$locale['alb39']."</strong></td>";
            echo "<td class='tbl2'><strong>".$locale['alb40']."</strong></td>";
            echo "<td class='tbl2'><strong>".$locale['alb41']."</strong></td>";
            echo "<td class='tbl2'><strong>".$locale['alb42']."</strong></td>";
        echo "</tr>";
    while ($data = dbarray($result)) {
        echo "<tr>";
            echo "<td class='tbl'>".$data['alb_post_id']."</td>";
            echo "<td class='tbl'><a href='".BASEDIR."blog.php?p=view_post&id=".$data['alb_post_id']."'>".$data['alb_post_title']."</a></td>";
            echo "<td class='tbl'><a href='".BASEDIR."blog.php?p=view_category&id=".$data['alb_post_cat']."'>".$data['alb_cat_title']."</a></td>";
            echo "<td class='tbl'><a href='".BASEDIR."profile.php?lookup=".$data['alb_post_user']."'>".$data['user_name']."</a></td>";
            echo "<td class='tbl'><a href='".BASEDIR."blog.php?p=manage_post&id=".$data['alb_post_id']."'><img src='".AL_BLOG_DIR."asset/images/edit.png' width='16' /></a> <a href='".FUSION_SELF.$aidlink."&p=posts&delete=".$data['alb_post_id']."'><img src='".AL_BLOG_DIR."asset/images/delete.png' width='16' /></a> <a href='".FUSION_SELF.$aidlink."&p=posts&approve=".$data['alb_post_id']."'><img src='".AL_BLOG_DIR."asset/images/start.png' width='16' /></a></td>";
        echo "</tr>";
    }
    echo "</table>";
    if ($total_un > 30) {
        echo "<div align='center' style=';margin-top:5px;'>\n".makepagenav($_GET['rowstart1'],30,$total_un,3,FUSION_SELF.$aidlink."&p=posts&rowstart2=".$_GET['rowstart2']."&","rowstart1")."</div>";
    }
} else {
    echo $locale['alb38'];
}
closetable();

opentable($locale['alb37']);
$total_ap = dbcount("(alb_post_id)",DB_AL_BLOG_POSTS,"alb_post_status='1'");
$result = dbquery("SELECT p.*,pc.*,u.user_name FROM ".DB_AL_BLOG_POSTS." p LEFT JOIN ".DB_AL_BLOG_CATEGORIES." pc ON pc.alb_cat_id=p.alb_post_cat LEFT JOIN ".DB_USERS." u ON u.user_id=p.alb_post_user WHERE alb_post_status='1' ORDER BY alb_post_datestamp DESC LIMIT ".$_GET['rowstart2'].",30");
if (dbrows($result)) {
    echo "<table width='100%'>";
    echo "<tr>";
    echo "<td class='tbl2'><strong>#</strong></td>";
    echo "<td class='tbl2'><strong>".$locale['alb39']."</strong></td>";
    echo "<td class='tbl2'><strong>".$locale['alb40']."</strong></td>";
    echo "<td class='tbl2'><strong>".$locale['alb41']."</strong></td>";
    echo "<td class='tbl2'><strong>".$locale['alb42']."</strong></td>";
    echo "</tr>";
    while ($data = dbarray($result)) {
        echo "<tr>";
        echo "<td class='tbl'>".$data['alb_post_id']."</td>";
        echo "<td class='tbl'><a href='".BASEDIR."blog.php?p=view_post&id=".$data['alb_post_id']."'>".$data['alb_post_title']."</a></td>";
        echo "<td class='tbl'><a href='".BASEDIR."blog.php?p=view_category&id=".$data['alb_post_cat']."'>".$data['alb_cat_title']."</a></td>";
        echo "<td class='tbl'><a href='".BASEDIR."profile.php?lookup=".$data['alb_post_user']."'>".$data['user_name']."</a></td>";
        echo "<td class='tbl'><a href='".BASEDIR."blog.php?p=manage_post&id=".$data['alb_post_id']."'><img src='".AL_BLOG_DIR."asset/images/edit.png' width='16' /></a> <a href='".FUSION_SELF.$aidlink."&p=posts&delete=".$data['alb_post_id']."'><img src='".AL_BLOG_DIR."asset/images/delete.png' width='16' /></a> <a href='".FUSION_SELF.$aidlink."&p=posts&unapprove=".$data['alb_post_id']."'><img src='".AL_BLOG_DIR."asset/images/finish.png' width='16' /></a></td>";
        echo "</tr>";
    }
    echo "</table>";
    if ($total_ap > 30) {
        echo "<div align='center' style=';margin-top:5px;'>\n".makepagenav($_GET['rowstart2'],30,$total_un,3,FUSION_SELF.$aidlink."&p=posts&rowstart1=".$_GET['rowstart1']."&","rowstart2")."</div>";
    }
} else {
    echo $locale['alb43'];
}
closetable();

?>