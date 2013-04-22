<?php
if (!defined("IN_FUSION")) die("fu");

 add_to_head("<script type='text/javascript' src='".INCLUDES."jquery/jquery-ui.js'></script>");
add_to_head("<link rel='stylesheet' href='".INFUSIONS."al_news_panel/asset/columns.css' type='text/css' media='all' />");
 add_to_head("<script type='text/javascript'>
$(document).ready(function() {
	$('.column1').sortable({
		handle : '.handle',
		placeholder: 'state-highlight',
		connectWith: '.connected',
		scroll: true,
		axis: 'y',
		update: function () {
			var ul = $(this),
				order = ul.sortable('serialize'),
				i = 0;
			$('#info1').load('column_updater.php".$aidlink."&id=1&'+order);
			ul.find('.num').each(function(i) {
				$(this).text(i+1);
			});
			ul.find('li').removeClass('tbl2').removeClass('tbl1');
			ul.find('li:odd').addClass('tbl2');
			ul.find('li:even').addClass('tbl1');
			window.setTimeout('closeDiv();',2500);
		}
	});
 $('.column2').sortable({
		handle : '.handle',
		placeholder: 'state-highlight',
		connectWith: '.connected',
		scroll: true,
		axis: 'y',
		update: function () {
			var ul = $(this),
				order = ul.sortable('serialize'),
				i = 0;
			$('#info2').load('column_updater.php".$aidlink."&id=2&'+order);
			ul.find('.num').each(function(i) {
				$(this).text(i+1);
			});
			ul.find('li').removeClass('tbl2').removeClass('tbl1');
			ul.find('li:odd').addClass('tbl2');
			ul.find('li:even').addClass('tbl1');
			window.setTimeout('closeDiv();',2500);
		}
	});
 $('.column3').sortable({
		handle : '.handle',
		placeholder: 'state-highlight',
		connectWith: '.connected',
		scroll: true,
		axis: 'y',
		update: function () {
			var ul = $(this),
				order = ul.sortable('serialize'),
				i = 0;
			$('#info3').load('column_updater.php".$aidlink."&id=3&'+order);
			ul.find('.num').each(function(i) {
				$(this).text(i+1);
			});
			ul.find('li').removeClass('tbl2').removeClass('tbl1');
			ul.find('li:odd').addClass('tbl2');
			ul.find('li:even').addClass('tbl1');
			window.setTimeout('closeDiv();',2500);
		}
	});
 $('.column4').sortable({
		handle : '.handle',
		placeholder: 'state-highlight',
		connectWith: '.connected',
		scroll: true,
		axis: 'y',
		update: function () {
			var ul = $(this),
				order = ul.sortable('serialize'),
				i = 0;
			$('#info4').load('column_updater.php".$aidlink."&id=4&'+order);
			ul.find('.num').each(function(i) {
				$(this).text(i+1);
			});
			ul.find('li').removeClass('tbl2').removeClass('tbl1');
			ul.find('li:odd').addClass('tbl2');
			ul.find('li:even').addClass('tbl1');
			window.setTimeout('closeDiv();',2500);
		}
	});
 $('.column5').sortable({
		handle : '.handle',
		placeholder: 'state-highlight',
		connectWith: '.connected',
		scroll: true,
		axis: 'y',
		update: function () {
			var ul = $(this),
				order = ul.sortable('serialize'),
				i = 0;
			$('#info5').load('column_updater.php".$aidlink."&id=5&'+order);
			ul.find('.num').each(function(i) {
				$(this).text(i+1);
			});
			ul.find('li').removeClass('tbl2').removeClass('tbl1');
			ul.find('li:odd').addClass('tbl2');
			ul.find('li:even').addClass('tbl1');
			window.setTimeout('closeDiv();',2500);
		}
	}); 
 $('.column6').sortable({
		handle : '.handle',
		placeholder: 'state-highlight',
		connectWith: '.connected',
		scroll: true,
		axis: 'y',
		update: function () {
			var ul = $(this),
				order = ul.sortable('serialize'),
				i = 0;
			$('#info6').load('column_updater.php".$aidlink."&id=6'+order);
			ul.find('.num').each(function(i) {
				$(this).text(i+1);
			});
			ul.find('li').removeClass('tbl2').removeClass('tbl1');
			ul.find('li:odd').addClass('tbl2');
			ul.find('li:even').addClass('tbl1');
			window.setTimeout('closeDiv();',2500);
		}
	}); 
});
</script>"); 

if (isset($_POST['save'])) {

$anid = $_POST['nnid'];
//$text = trim(nl2br($_POST['ntext']));
$order2 = $_POST['norder'];
$order = $order2 == "0" ? 1 : $order2;
$column = $_POST['ncolumn']; 
$ninfo = dbarray(dbquery("SELECT * FROM ".DB_AN_NEWS." WHERE anews_id='".$anid."'"));
$old_order = $ninfo['anews_order']; 
if (isnum($order)) {
$update = dbquery("UPDATE ".DB_AN_NEWS." SET anews_column='".$column."' WHERE anews_id='".$anid."'"); 
if ($order == $ninfo['anews_order']) {
// just update

} else {
// shamanit
$max = dbresult(dbquery("SELECT MAX(anews_order) FROM ".DB_AN_NEWS." WHERE anews_column='".$column."'"), 0); 
if ($order > $max) {
$new_order = $max;
$upd_order = dbquery("UPDATE ".DB_AN_NEWS." SET anews_order='".$new_order."' WHERE anews_id='".$anid."'");
$upd_order_all = dbquery("UPDATE ".DB_AN_NEWS." SET anews_order=anews_order-1 WHERE anews_column='".$column."' AND anews_order>'".$old_order."' AND anews_id<>'".$anid."'");
} else {
if ($order > $old_order) {
$upd_order = dbquery("UPDATE ".DB_AN_NEWS." SET anews_order='".$order."' WHERE anews_id='".$anid."'"); 
$upd_order_all = dbquery("UPDATE ".DB_AN_NEWS." SET anews_order=anews_order-1 WHERE anews_column='".$column."' AND anews_order>'".$old_order."' AND anews_order<='".$order."' AND anews_id<>'".$anid."'");
} else {
$upd_order = dbquery("UPDATE ".DB_AN_NEWS." SET anews_order='".$order."' WHERE anews_id='".$anid."'"); 
 $upd_order_all = dbquery("UPDATE ".DB_AN_NEWS." SET anews_order=anews_order+1 WHERE anews_column='".$column."' AND anews_order>='".$order."' AND anews_order<'".$old_order."' AND anews_id<>'".$anid."'"); 
}
}
}
}
redirect(INFUSIONS."al_news_panel/admin/index.php".$aidlink."&p=news"); 
}


if (isset($_POST['add'])) {
$nid = $_POST['nnews'];
//$text = trim(nl2br($_POST['ntext']));
$order2 = $_POST['norder'];
$order = $order2 == "0" ? 1 : $order2;
$column = $_POST['ncolumn'];
if (isnum($order)) {
$max = dbresult(dbquery("SELECT MAX(anews_order) FROM ".DB_AN_NEWS." WHERE anews_column='".$column."'"), 0);
//print_r($_POST);
//var_dump($max);
$max_order = $max ? $max : 0;
if ($order > $max_order+1) {
$order = $max_order+1;
} elseif ($order <= $max_order) {
//refresh order
$refresh = dbquery("UPDATE ".DB_AN_NEWS." SET anews_order=anews_order+1 WHERE anews_column='".$column."' AND anews_order>='".$order."'");
}
$add = dbquery("INSERT INTO ".DB_AN_NEWS." (anews_news, anews_column, anews_order) VALUES ('".$nid."','".$column."','".$order."')");
} // isnum
redirect(INFUSIONS."al_news_panel/admin/index.php".$aidlink."&p=news"); 
}

if (isset($_GET['e']) && isnum($_GET['e'])) {
$anews = dbquery("SELECT an.*, nn.news_subject, nn.news_news FROM ".DB_AN_NEWS." an LEFT JOIN ".DB_NEWS." nn ON nn.news_id=an.anews_news WHERE anews_id='".$_GET['e']."'");
if (dbrows($anews)) {
$anews = dbarray($anews);
$atitle = $anews['news_subject'];
$atext = $anews['news_news'];
$aorder = $anews['anews_order'];
$acolumn = $anews['anews_column'];
$anid = $anews['anews_news'];
$aid = $anews['anews_id']; 
$aedit = true;
} else {
redirect(INFUSIONS."al_news_panel/admin/index.php".$aidlink."&p=news");
}
} else {
$atitle = 0;
$atext = "";
$aorder = 1;
$acolumn = 1;
$aedit = false;
}

if (isset($_GET['d']) && isnum($_GET['d'])) {
$check = dbquery("SELECT * FROM ".DB_AN_NEWS." WHERE anews_id='".$_GET['d']."'");
if (dbrows($check)) {
$ninfo = dbarray($check);
$delete = dbquery("DELETE FROM ".DB_AN_NEWS." WHERE anews_id='".$_GET['d']."'"); 
$update_order = dbquery("UPDATE ".DB_AN_NEWS." SET anews_order=anews_order-1 WHERE anews_order>'".$ninfo['anews_order']."' AND anews_column='".$ninfo['anews_column']."'");
}
 redirect(INFUSIONS."al_news_panel/admin/index.php".$aidlink."&p=news"); 
} else {
opentable($locale['an18']);
//form
echo "<form name='inputform' method='post'><table width='100%'>";
echo "<tr><td class='tbl2' width='250'>".$locale['an20']."</td><td class='tbl2'>";
if ($aedit) {
echo "<a href='".BASEDIR."news.php?readmore=".$anid."'>".$atitle."</a><input type='hidden' name='nnid' value='".$aid."' />";
} else {
$all_news = dbquery("SELECT news_subject, news_id FROM ".DB_NEWS." WHERE news_draft='0' ORDER BY news_id DESC");
echo "<select name='nnews'>";
while ($all_new = dbarray($all_news)) {
echo "<option value='".$all_new['news_id']."'>".$all_new['news_subject']."</option>";
}
echo "</select>";
}
echo "</td></tr>";
if ($aedit) {
echo "<tr><td class='tbl2' width='250'>".$locale['an21']."</td><td class='tbl2'>".$atext."</td></tr>";
}
 echo "<tr><td class='tbl2' width='250'>".$locale['an22']."</td><td class='tbl2'>";
$columns = dbarray(dbquery("SELECT * FROM ".DB_AN_COLUMNS.""));
echo "<select name='ncolumn'>";
for ($i=1;$i<=6;$i++) {
echo "<option value='".$i."' style='font-color:".($columns['column'.$i.'_enable'] == "1" ? "green" : "red").";'".($i == $acolumn ? " selected='selected'" : "").">".$columns['column'.$i.'_name']."</option>";
}
echo "</select>";
echo "</td></tr>";
 echo "<tr><td class='tbl2' width='250'>".$locale['an23']."</td><td class='tbl2'><input type='text' class='textbox' size='3' name='norder' value='".$aorder."' /></td></tr>";
 echo "<tr><td class='tbl2' colspan='2'><input type='submit' class='button' name='".($aedit ? "save" : "add")."' value='".$locale['an24']."' /></td></tr>"; 
echo "</table></form>";
closetable();
}
opentable($locale['an19']);
//liste
$cc = dbarray(dbquery("SELECT * FROM ".DB_AN_COLUMNS.""));
echo "<table width='100%'>";
for ($k=1;$k<=5;$k=$k+2) {
$z = $k+1;
echo "<tr><td class='tbl2' width='50%'><strong style='color:".($cc['column'.$k.'_enable'] == "1" ? "green" : "red").";'>".$k.". ".$cc['column'.$k.'_name']."</strong></td><td class='tbl2'><strong style='color:".($cc['column'.$z.'_enable'] == "1" ? "green" : "red").";'>".$z.". ".$cc['column'.$z.'_name']."</strong></td></tr>";
echo "<tr valign='top'><td>";
 echo "<div id='info".$k."'></div>";

echo "<ul id='column".$k."' style='list-style: none;' class='column".$k." connected'>";
 $nns1 = dbquery("SELECT an.*, nn.news_subject FROM ".DB_AN_NEWS." an LEFT JOIN ".DB_NEWS." nn ON nn.news_id=an.anews_news WHERE anews_column='".$k."' ORDER BY anews_order ASC"); 
if (dbrows($nns1)) {
	$i = 0;
	while($data = dbarray($nns1)) {
		$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
		echo "<li id='listItem_".$data['anews_id']."' class='".$row_color."'>";
		echo "<div style='float:left; width:30px;'><img src='".IMAGES."arrow.png' alt='move' class='handle' /></div>";
		echo "<div style='float:left;'>\n";
				echo "<a href='".BASEDIR."news.php?readmore=".$data['anews_news']."'>".$data['news_subject']."</a>";


		echo "</div>";
		echo "<div style='float:right; width:100px; text-align:center;'>";
		echo "<a href='".INFUSIONS."al_news_panel/admin/index.php".$aidlink."&amp;p=news&e=".$data['anews_id']."'><img src='".IMAGES."edit.png' width='10' /></a> ";
		echo "<a href='".INFUSIONS."al_news_panel/admin/index.php".$aidlink."&amp;p=news&d=".$data['anews_id']."'><img src='".IMAGES."no.png' width='10' /></a>";
		echo "</div>";
		echo "<div class='num' style='float:right; width:15%; text-align:center;'>".$data['anews_order']."</div>";

		echo "<div style='clear:both;'></div>";
		echo "</li>";
		$i++;
	}

} else {
	echo $locale['an25'];
} 
 echo "</ul>";
echo "</td><td>"; 

 echo "<div id='info".$z."'></div>";

echo "<ul id='column".$z."' style='list-style: none;' class='column".$z." connected'>";
 $nns2 = dbquery("SELECT an.*, nn.news_subject FROM ".DB_AN_NEWS." an LEFT JOIN ".DB_NEWS." nn ON nn.news_id=an.anews_news WHERE anews_column='".$z."' ORDER BY anews_order ASC"); 
if (dbrows($nns2)) {
	$i = 0;
	while($data = dbarray($nns2)) {
		$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
		echo "<li id='listItem_".$data['anews_id']."' class='".$row_color."'>";
		echo "<div style='float:left; width:30px;'><img src='".IMAGES."arrow.png' alt='move' class='handle' /></div>";
		echo "<div style='float:left;'>\n";
				echo "<a href='".BASEDIR."news.php?readmore=".$data['anews_news']."'>".$data['news_subject']."</a>";


		echo "</div>";
		echo "<div style='float:right; width:100px; text-align:center;'>";
		echo "<a href='".INFUSIONS."al_news_panel/admin/index.php".$aidlink."&amp;p=news&e=".$data['anews_id']."'><img src='".IMAGES."edit.png' width='10' /></a> ";
		echo "<a href='".INFUSIONS."al_news_panel/admin/index.php".$aidlink."&amp;p=news&d=".$data['anews_id']."'><img src='".IMAGES."no.png' width='10' /></a>";
		echo "</div>";
		echo "<div class='num' style='float:right; width:15%; text-align:center;'>".$data['anews_order']."</div>";

		echo "<div style='clear:both;'></div>";
		echo "</li>";
		$i++;
	}

} else {
	echo $locale['an25'];
} 
 echo "</ul>"; 

echo "</td></tr>";

}

echo "</table>";

closetable();


?>
