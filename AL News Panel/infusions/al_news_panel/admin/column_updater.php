<?php
require_once "../../../maincore.php";


if (isset($_GET['listItem']) && is_array($_GET['listItem'])) {
	foreach ($_GET['listItem'] as $position => $item) {
		if (isnum($position) && isnum($item)) {
			dbquery("UPDATE ".DB_PREFIX."an_news SET anews_order='".($position+1)."' WHERE anews_id='".$item."'");
		}
	}
	header("Content-Type: text/html; charset=".$locale['charset']."\n");
	echo "<div id='close-message'><div class='admin-message'>moved</div></div>";
}
?>
