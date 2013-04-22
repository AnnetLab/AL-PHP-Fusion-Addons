<?php

function showNav() {
    global $locale, $aidlink;
    echo "<button class='button'><a href='".INFUSIONS."al_stats/admin/index.php".$aidlink."'>".$locale['st3']."</a></button> ";
    echo "<button class='button'><a href='".INFUSIONS."al_stats/admin/index.php".$aidlink."&p=stats'>".$locale['st4']."</a></button> ";
    echo "<button class='button'><a href='".INFUSIONS."al_stats/admin/index.php".$aidlink."&p=teams'>".$locale['st5']."</a></button> ";
    echo "<button class='button'><a href='".INFUSIONS."al_stats/admin/index.php".$aidlink."&p=games'>".$locale['st6']."</a></button> ";
}

?>