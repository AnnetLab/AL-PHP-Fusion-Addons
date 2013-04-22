<?php
require_once "../../maincore.php";
require_once THEMES."templates/header.php";
include INFUSIONS."al_streams/infusion_db.php";
if (file_exists(INFUSIONS."al_streams/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_streams/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_streams/locale/Russian.php";
}

if (isset($_GET['stream_id']) && isnum($_GET['stream_id'])) {
    $check = dbquery("SELECT * FROM ".DB_SS_STREAMS." WHERE st_id='".$_GET['stream_id']."'");
    if (dbrows($check)) {
        opentable($locale['ss38']);

        $total = dbcount("(cm_id)",DB_SS_CHAT_MESSAGES,"cm_channel_id='".$_GET['stream_id']."'");
        $rowstart = isset($_GET['rowstart']) && isnum($_GET['rowstart']) ? $_GET['rowstart'] : 0;
        
        if ($total > 0) {
            
            $result = dbquery("SELECT cm.*, u.user_name FROM ".DB_SS_CHAT_MESSAGES." cm LEFT JOIN ".DB_USERS." u ON u.user_id=cm.cm_user_id WHERE cm_channel_id='".$_GET['stream_id']."' ORDER BY cm_timestamp DESC LIMIT ".$rowstart.",100");
            while ($data=dbarray($result)) {
                echo "<div class='tbl-border tbl'>";
                    echo "<a href='".BASEDIR."profile.php?lookup=".$data['cm_user_id']."'>".$data['user_name']."</a> &rarr; ".iconv("UTF-8",$locale['charset'],$data['cm_message'])."<i class='small' style='float:right;'>".showdate("forumdate",$data['cm_timestamp'])."</i>";
                echo "</div>";
            }
            
            if ($total > 100) {
                echo makepagenav($rowstart,100,$total,3);
            }
            
        } else {
            echo "No messages...";
        }


        closetable();
    } else {
        redirect(BASEDIR."index.php");
    }
} else {
    redirect(BASEDIR."index.php");
}
require_once THEMES."templates/footer.php";
?>