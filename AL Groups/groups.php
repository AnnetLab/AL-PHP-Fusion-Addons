<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
 if (file_exists(INFUSIONS."al_group/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_groups/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_groups/locale/English.php";
}
require_once INFUSIONS."/al_groups/infusion_db.php";

/* add_to_head("<link rel='stylesheet' href='".INCLUDES."jquery/colorbox/colorbox.css' media='screen' type='text/css' />");
add_to_head("<script type='text/javascript' src='".INCLUDES."jquery/colorbox/jquery.colorbox.js'></script>"); 



add_to_head("<script type='text/javascript'>
$(document).ready(function(){
$('.vote').click(function(){
var id = $(this).attr('rel');
id = id.substr(2);
var url = $(this).attr('rel');
$.colorbox({inline:true,href:url});
return false;
});

});

</script>");*/

 add_to_head("<script type='text/javascript' src='".INFUSIONS."al_groups/includes/fancybox/jquery.easing-1.3.pack.js'></script>");
 add_to_head("<script type='text/javascript' src='".INFUSIONS."al_groups/includes/fancybox/jquery.mousewheel-3.0.4.pack.js'></script>"); 
add_to_head("<script type='text/javascript' src='".INFUSIONS."al_groups/includes/fancybox/jquery.fancybox-1.3.4.js'></script>");
add_to_head("<link rel='stylesheet' href='".INFUSIONS."al_groups/includes/fancybox/jquery.fancybox-1.3.4.css' type='text/css' media='screen' />"); 

add_to_head("<script type='text/javascript'>

$(document).ready(function(){
$('.vote').fancybox({'type':'ajax'});
$('#cat_select').change(function(){
var cat = $(this).val();
window.location = 'groups.php?cat='+cat;
});


});

</script>");

if (!isset($_GET['rowstart'])) $_GET['rowstart'] = 0;

if (isset($_GET['cat']) && isnum($_GET['cat'])) {

opentable($locale['gs16']);
$cats = dbquery("SELECT * FROM ".DB_GS_CATS." ORDER BY cat_id");
$csel = "<select name='cat_select' id='cat_select'><option value='0'>All categories</option>";
while ($cat=dbarray($cats)) {
$csel .= "<option value='".$cat['cat_id']."'".($_GET['cat'] == $cat['cat_id'] ? " selected='selected'" : "").">".$cat['cat_name']."</option>";
}
$csel .= "</select>";
echo "<div>".$csel."</div>";
echo "<div style='float:right;'><a href='".BASEDIR."group_admin.php
?action=create'>".$locale['gs17']."</a></div>";
$result = dbquery("SELECT gr.*, gc.*, gv.voter_id FROM ".DB_GS_GROUPS." gr LEFT JOIN ".DB_GS_CATS." gc ON gc.cat_id=gr.group_cat LEFT JOIN ".DB_GS_VOTERS_GROUPS." gv ON gv.voter_ip='".USER_IP."' AND gv.voter_group=gr.group_id ".($_GET['cat'] != "0" ? "WHERE group_cat='".$_GET['cat']."' " : "")."ORDER BY group_stat DESC LIMIT ".$_GET['rowstart'].",15");
if (dbrows($result)) {
echo "<table width='100%'><tr align='center'><td class='tbl2' width='1%'>#</td><td class='tbl2'>".$locale['gs25']."</td><td class='tbl2'>".$locale['gs26']."</td><td class='tbl2' width='1%'>".$locale['gs27']."</td><td class='tbl2' width='20%'>".$locale['gs28']."</td></tr>";
$i = $_GET['rowstart'];
while ($data=dbarray($result)) {
echo "<tr><td class='".($i%2==0 ? "tbl1" : "tbl2")."' align='center'>".($i+1)."</td><td class='".($i%2==0 ? "tbl1" : "tbl2")."'><a href='".BASEDIR."group.php?view=".$data['group_id']."'>".$data['group_name']."</a></td><td class='".($i%2==0 ? "tbl1" : "tbl2")."'>".$data['cat_name']."</td><td class='".($i%2==0 ? "tbl1" : "tbl2")."' align='center'>".$data['group_stat']."</td><td class='".($i%2==0 ? "tbl1" : "tbl2")."' align='center'>";
//echo "<a href='#' class='vote' rel='go".$data['group_id']."'>&uarr;</a>";
// href: 'infusions/al_groups/includes/rate.php?id='+id 
//print_r($data);
//var_dump($data['voter_id']);
if (!$data['voter_id']) {
 echo "<a href='rate.php?id=".$data['group_id']."' class='vote'>&uarr; ".$locale['gs54']."</a>";
 } else {
 echo "<a href='rate.php?id=already' class='vote'>".$locale['gs59']."</a>";
 }
echo "</td></tr>";
$i++;
}

echo "</table>";
} else {
echo $locale['gs18'];
}
closetable();
$count = dbcount("(group_id)", DB_GS_GROUPS, "group_cat='".$_GET['cat']."'");
echo makepagenav($_GET['rowstart'], 15, $count, 3, FUSION_SELF."?cat=".$_GET['cat']."&"); 

} else {
redirect(BASEDIR."groups.php?cat=0");
}

require_once THEMES."templates/footer.php";
?>
