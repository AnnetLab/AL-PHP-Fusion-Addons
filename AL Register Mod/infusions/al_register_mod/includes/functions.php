<?php
if (!defined("IN_FUSION")) die("fu");

$field_types = array(
1 => "text",
2 => "textarea",
3 => "select",
4 => "infobox"
);

function check_group($id) {
global $userdata;
if (iMEMBER) {
if (!empty($userdata['user_groups'])) {
$groups = explode(".",$userdata['user_groups']);
if (is_array($groups)) {
if (in_array($id,$groups)) {
return true;
} else {
return false;
}
} else {
if ($groups == $id) {
return true;
} else {
return false;
}
}
} else {
return false;
}
} else {
return false;
}
}

?>
