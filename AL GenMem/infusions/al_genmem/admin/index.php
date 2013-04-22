<?php
require_once "../../../maincore.php";
require_once INFUSIONS."al_genmem/infusion_db.php";
require_once THEMES."templates/admin_header.php";
if (file_exists(INFUSIONS."al_genmem/locale/".$settings['locale'].".php")) {
    require_once INFUSIONS."al_genmem/locale/".$settings['locale'].".php";
} else {
    require_once INFUSIONS."al_genmem/locale/Russian.php";
}

if (!checkAdminPageAccess("GEM")) redirect(BASEDIR);




if (isset($_POST['update'])) {

    $mem_width = isset($_POST['mem_width']) && isnum($_POST['mem_width']) && $_POST['mem_width']>0 ? $_POST['mem_width'] : 800;
    $mem_height = isset($_POST['mem_height']) && isnum($_POST['mem_height']) && $_POST['mem_height']>0 ? $_POST['mem_height'] : 600;
    $dem_padding_top = isset($_POST['dem_padding_top']) && isnum($_POST['dem_padding_top']) && $_POST['dem_padding_top']>0 ? $_POST['dem_padding_top'] : 20;
    $dem_padding_side = isset($_POST['dem_padding_side']) && isnum($_POST['dem_padding_side']) && $_POST['dem_padding_side']>0 ? $_POST['dem_padding_side'] : 30;
    $dem_padding_bottom = isset($_POST['dem_padding_bottom']) && isnum($_POST['dem_padding_bottom']) && $_POST['dem_padding_bottom']>0 ? $_POST['dem_padding_bottom'] : 120;
    $dem_border = isset($_POST['dem_border']) && isnum($_POST['dem_border']) && $_POST['dem_border']>0 ? $_POST['dem_border'] : 3;
    $dem_after_border = isset($_POST['dem_after_border']) && isnum($_POST['dem_after_border']) && $_POST['dem_after_border']>0 ? $_POST['dem_after_border'] : 5;

    $update = dbquery("UPDATE ".DB_GEM_SETTINGS." SET mem_width='".$mem_width."',mem_height='".$mem_height."',dem_padding_top='".$dem_padding_top."',dem_padding_side='".$dem_padding_side."',dem_padding_bottom='".$dem_padding_bottom."',dem_border='".$dem_border."',dem_after_border='".$dem_after_border."'");
    //print_r($_POST);
    redirect(FUSION_SELF.$aidlink);

}

$gm_settings = dbarray(dbquery("SELECT * FROM ".DB_GEM_SETTINGS));

opentable($locale['gem28']);

    echo "<form action='".FUSION_SELF.$aidlink."' method='post'>";
    echo "<table width='100%'>";
    echo "<tr>";
        echo "<td class='tbl' width='250'>".$locale['gem29']."</td>";
        echo "<td class='tbl'><input type='text' class='textbox' style='width: 250px' name='mem_width' value='".$gm_settings['mem_width']."' /></td>";
    echo "</tr>";
    echo "<tr>";
        echo "<td class='tbl' width='250'>".$locale['gem30']."</td>";
        echo "<td class='tbl'><input type='text' class='textbox' style='width: 250px' name='mem_height' value='".$gm_settings['mem_height']."' /></td>";
    echo "</tr>";
    echo "<tr>";
        echo "<td class='tbl' width='250'>".$locale['gem32']."</td>";
        echo "<td class='tbl'><input type='text' class='textbox' style='width: 250px' name='dem_padding_top' value='".$gm_settings['dem_padding_top']."' /></td>";
    echo "</tr>";
    echo "<tr>";
        echo "<td class='tbl' width='250'>".$locale['gem33']."</td>";
        echo "<td class='tbl'><input type='text' class='textbox' style='width: 250px' name='dem_padding_side' value='".$gm_settings['dem_padding_side']."' /></td>";
    echo "</tr>";
    echo "<tr>";
        echo "<td class='tbl' width='250'>".$locale['gem34']."</td>";
        echo "<td class='tbl'><input type='text' class='textbox' style='width: 250px' name='dem_padding_bottom' value='".$gm_settings['dem_padding_bottom']."' /></td>";
    echo "</tr>";
    echo "<tr>";
        echo "<td class='tbl' width='250'>".$locale['gem35']."</td>";
        echo "<td class='tbl'><input type='text' class='textbox' style='width: 250px' name='dem_border' value='".$gm_settings['dem_border']."' /></td>";
    echo "</tr>";
    echo "<tr>";
        echo "<td class='tbl' width='250'>".$locale['gem36']."</td>";
        echo "<td class='tbl'><input type='text' class='textbox' style='width: 250px' name='dem_after_border' value='".$gm_settings['dem_after_border']."' /></td>";
    echo "</tr>";
    echo "<tr>";
        echo "<td class='tbl' colspan='2'><input type='submit' class='button' name='update' value='".$locale['gem31']."' /></td>";
    echo "</tr>";
    echo "</table>";
    echo "</form>";

closetable();


require_once THEMES."templates/footer.php";
?>