<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
include INFUSIONS."al_streams/infusion_db.php";
if (file_exists(INFUSIONS."al_streams/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_streams/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_streams/locale/Russian.php";
}
require_once INCLUDES."bbcode_include.php";
$sets = dbarray(dbquery("SELECT * FROM ".DB_SS_SETTINGS.""));

if (isset($_POST['screate']) && iMEMBER) {

$provider = $_POST['provider'];
$ident = trim(stripinput($_POST['ident']));
$desc = trim($_POST['desc']);

$ons = dbquery("INSERT INTO ".DB_SS_STREAMS." (st_provider,st_provider_id,st_user,st_desc) VALUES ('".$provider."','".$ident."','".$userdata['user_id']."','".$desc."')");
$id = mysql_insert_id();
redirect(FUSION_SELF."?action=view&id=".$id);
}

 if (isset($_POST['supdate']) && iMEMBER) {

$provider = $_POST['provider'];
$ident = trim(stripinput($_POST['ident']));
$desc = trim($_POST['desc']);

$ons = dbquery("UPDATE ".DB_SS_STREAMS." SET st_provider='".$provider."',st_provider_id='".$ident."',st_desc='".$desc."' WHERE st_id='".$_POST['st_id']."'");
redirect(FUSION_SELF."?action=view&id=".$_POST['st_id']);
}

if (isset($_GET['action']) && $_GET['action'] == "create") {
if (iMEMBER && checkgroup($sets['set_usergroup'])) {
$check = dbquery("SELECT * FROM ".DB_SS_STREAMS." WHERE st_user='".$userdata['user_id']."'");
if (!dbrows($check)) {
opentable($locale['ss4']);
echo "<form method='post' name='inputform'><table width='100%'>";
echo "<tr><td class='tbl2' colspan='2'>".$locale['ss30']."</td></tr>";
echo "<tr><td class='tbl2' width='250'>".$locale['ss5']."</td><td class='tbl2'><select name='provider'>";
$ps = array(1=>"regame.tv","own3d.tv","justin.tv/twitch.tv","cybergame.tv");
for ($i=1;$i<=4;$i++) {
echo "<option value='".$i."'>".$ps[$i]."</option>";
}
echo "</select></td></tr>";
 echo "<tr><td class='tbl2' width='250'>".$locale['ss6']."</td><td class='tbl2'><input type='text' class='textbox' name='ident' /></td></tr>";
 echo "<tr><td class='tbl2' width='250'>".$locale['ss7']."</td><td class='tbl2'><textarea name='desc' class='textbox' cols='45' rows='10'></textarea>";
echo display_bbcodes("240px;", "desc", "inputform");
echo "</td></tr>";
 echo "<tr><td class='tbl2' colspan='2'><input type='submit' class='button' name='screate' value='".$locale['ss8']."' /></td></tr>";
echo "</table></form>";
closetable();
} else {
redirect(FUSION_SELF."?action=edit");
}
} else {
redirect("login.php");
}

} else if (isset($_GET['action']) && $_GET['action'] == "edit" && iMEMBER) {

if (isset($_GET['id']) && isnum($_GET['id'])) {

 $check = dbquery("SELECT * FROM ".DB_SS_STREAMS." WHERE st_id='".$_GET['id']."'");
if (dbrows($check)) {
$data2 = dbarray($check);
if ($data2['st_user'] == $userdata['user_id'] || checkrights("SS")) {
$data = $data2;
$data2 = "";
} else {
redirect(FUSION_SELF);
}
} else {
redirect(FUSION_SELF);
}


} else {
$check = dbquery("SELECT * FROM ".DB_SS_STREAMS." WHERE st_user='".$userdata['user_id']."'");
if (dbrows($check)) {
$data = dbarray($check);
} else {
redirect(FUSION_SELF);
}
}



opentable($locale['ss22']);
echo "<form method='post' name='inputform'><table width='100%'>";
echo "<tr><td class='tbl2' width='250'>".$locale['ss5']."</td><td class='tbl2'><select name='provider'>";
$ps = array(1=>"regame.tv","own3d.tv","justin.tv/twitch.tv","cybergame.tv","cybergame.tv");
for ($i=1;$i<=4;$i++) {
echo "<option value='".$i."'".($data['st_provider'] == $i ? " selected='selected'" : "").">".$ps[$i]."</option>";
}
echo "</select></td></tr>";
 echo "<tr><td class='tbl2' width='250'>".$locale['ss6']."</td><td class='tbl2'><input type='text' class='textbox' name='ident' value='".$data['st_provider_id']."' /></td></tr>";
 echo "<tr><td class='tbl2' width='250'>".$locale['ss7']."</td><td class='tbl2'><textarea name='desc' class='textbox' cols='45' rows='10'>".$data['st_desc']."</textarea>";
echo display_bbcodes("240px;", "desc", "inputform");
echo "</td></tr>";
 echo "<tr><td class='tbl2' colspan='2'><input type='hidden' name='st_id' value='".$data['st_id']."' /><input type='submit' class='button' name='supdate' value='".$locale['ss23']."' /></td></tr>";
echo "</table></form>";
closetable();

} else if (isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['id']) && isnum($_GET['id'])) {

$result = dbquery("SELECT * FROM ".DB_SS_STREAMS." WHERE st_id='".$_GET['id']."'");

if (dbrows($result)) {
$data = dbarray($result);
if ($data['st_user'] == $userdata['user_id'] || checkrights("SS")) {
$del = dbquery("DELETE FROM ".DB_SS_STREAMS." WHERE st_id='".$_GET['id']."'");
}
}
redirect(FUSION_SELF);
} else if (isset($_GET['action']) && $_GET['action'] == "view" && isset($_GET['id']) && isnum($_GET['id'])) {

$result = dbquery("SELECT st.*,u.user_name FROM ".DB_SS_STREAMS." st LEFT JOIN ".DB_USERS." u ON u.user_id=st.st_user WHERE st_id='".$_GET['id']."'");

if (dbrows($result)) {
$data = dbarray($result);


opentable($locale['ss20'].$data['user_name']);

echo "<table width='100%'>";
echo "<tr><td class='tbl2'><span id='ststatus'></span><span style='float:right;'>".($data['st_user'] == $userdata['user_id'] || checkrights("SS") ? "<a href='".FUSION_SELF."?action=edit&id=".$data['st_id']."'><img src='".IMAGES."edit.png' alt='Edit' title='Edit' width='16' /></a>  <a href='".FUSION_SELF."?action=delete&id=".$data['st_id']."'><img src='".IMAGES."no.png' alt='Delete' title='Delete' width='16' /></a>" : "")."</span></td></tr>";
echo "<tr><td class='tbl' align='center'>";
switch ($data['st_provider']) {
case 1:

break;
case 2:
echo "<iframe height='360' width='640' frameborder='0' src='http://www.own3d.tv/liveembed/".$data['st_provider_id']."'></iframe>
";
break;
case 3:
echo "<object type='application/x-shockwave-flash' height='390' width='640' id='jtv_player_flash' data='http://www.justin.tv/widgets/jtv_player.swf?channel=".$data['st_provider_id']."' bgcolor='#000000'><param name='allowFullScreen' value='true' /><param name='allowscriptaccess' value='always' /><param name='movie' value='http://www.justin.tv/widgets/jtv_player.swf' /><param name='flashvars' value='channel=".$data['st_provider_id']."&auto_play=false&start_volume=25' /></object>";
break;
case 4:
echo "<iframe src='http://api.cybergame.tv/p/embed.php?c=".$data['st_provider_id']."&w=640&h=360c&type=embed' width='640' height='360' frameborder='0'></iframe>";
break;
}

echo "</td></tr>";
echo "<tr><td class='tbl'>".nl2br(parsesmileys(parseubb($data['st_desc'])))."</td></tr>";
echo "</table>";
closetable();

//comments
//require_once INCLUDES."comments_include.php";
//showcomments("SS", DB_SS_STREAMS, "st_id", $_GET['id'], BASEDIR."streams.php?action=view&id=".$_GET['id']);

 echo "<script type='text/javascript'>

$(document).ready(function(){
var id = '".$data['st_id']."';
var pid = '".$data['st_provider_id']."';
var provider = '".$data['st_provider']."';
var bdir = '".BASEDIR."';


$.ajax({
type: 'post',
url: bdir+'infusions/al_streams/backend.php',
dataType: 'json',
cache: false,
data: {
provider: provider,
provider_id: pid,
id: id,
action: 'get_info'
},
success: function(data){
if (data.is_live == 1) {
var info = '".$locale['ss11']."';
} else {
var info = '".$locale['ss13']."';
}

$('#ststatus').empty().html(info);
}
});

});


</script>";


echo "<script type='text/javascript'>

    var channel_id = '".$data['st_id']."';
    var user_id = '".($userdata['user_id'] && $userdata['user_id'] != "" ? $userdata['user_id'] : 0)."';
    var bdir = '".BASEDIR."';
    
    function trimLeft(str) {
        return str.replace(/^\s+/, '');
    }
    var intervalID = setInterval(alstrp_refresh,5000);
    function alstrp_refresh() {
        $.ajax({
            type: 'post',
            url: bdir+'infusions/al_streams/chat_backend.php',
            dataType: 'json',
            cache: false,
            data: {
                action: 'refresh',
                channel_id: channel_id,
                user_id: user_id
            }, success: function(data) {
                //ONLINE!!!!!!!!!!!!!!!!!!!!!!
                $('#ss_chat_online').empty().append('".$locale['ss33']."'+data.online+'".$locale['ss37']."');
                if (data.count > 0) {
                    $('#ss_chat').empty();
                    for (var key in data.msgs) {
                        //alert(data.msgs[key].user_name);
                        //var temp_date = new Date(data.msgs[key].date*1000);
                        //var date = temp_date.toUTCString('m d yy');
                        $('#ss_chat').append('<div class=\'tbl-border tbl\'><a href=\'profile\.php?lookup='+data.msgs[key].user_id+'\'>'+data.msgs[key].user_name+'</a> &rarr; '+data.msgs[key].message+'<i class=\'small\' style=\'float\:right\;\'>'+data.msgs[key].date+'</i></div>');
                        
                    }
                } else {
                    $('#ss_chat').html('".$locale['ss36']."');
                }
            }
        });
        
    }

    $(document).ready(function(){
        
        alstrp_refresh();
        $('form[name=ss-chat-form]').submit(function(){
            var ssmessage = trimLeft($('input[name=ss-chat-message]').val());
            //alert(ssmessage);
            if (ssmessage == '') {
                alert('Enter your message');
            } else {
            $.ajax({
                type: 'post',
                url: bdir+'infusions/al_streams/chat_backend.php',
                dataType: 'json',
                cache: false,
                data: {
                    action: 'shout',
                    channel_id: channel_id,
                    user_id: user_id,
                    message: ssmessage
                }, success: function(data) {
                    //alert(data);
                }
            });
            $('input[name=ss-chat-message]').val('');
            }
            alstrp_refresh();
            return false;
        });
        
    });


</script>";



opentable($locale['ss31']);

if (iMEMBER) {
    echo "<div id='ss_chat_shout' class='tbl2'>";
        echo "<form name='ss-chat-form'>";
            echo "<input type='text' class='textbox' name='ss-chat-message' style='width:350px;' maxlength='80' /> <input type='submit' class='button' name='ss-shout' value='".$locale['ss32']."' />";
        
        echo "</form>";
    echo "</div>";
} else {
    echo "<div class='tbl2'>";
    echo $locale['ss35'];
    echo "</div>";
}
echo "<div id='ss_chat_online' class='tbl2'></div>";
echo "<div id='ss_chat'></div>";


closetable();





} else {
redirect(FUSION_SELF);
}

} else {

$result = dbquery("SELECT st.*,u.user_name FROM ".DB_SS_STREAMS." st LEFT JOIN ".DB_USERS." u ON u.user_id=st.st_user ORDER BY st_id DESC");
$count = dbcount("(st_id)",DB_SS_STREAMS);

$result4 = dbquery("SELECT * FROM ".DB_SS_STREAMS." WHERE st_user='".$userdata['user_id']."'");

opentable($locale['ss10']);
echo (iMEMBER && !dbrows($result4) && checkgroup($sets['set_usergroup']) ? "<a href='".FUSION_SELF."?action=create'>".$locale['ss24']."</a>" : "");
if (dbrows($result)) {
$ids = array(); $i=0;
echo "<table width='100%'><tr>";
while ($data=dbarray($result)) {
$i++;
$ids[$i] = array("provider"=>$data['st_provider'],"ident"=>$data['st_provider_id'],"id"=>$data['st_id']);
echo "<td width='50%' class='tbl2' align='center'>";
echo "<a href='".BASEDIR."profile.php?lookup=".$data['st_user']."'>".$data['user_name']."</a>";
echo "<div style='height:150px;width:100%;text-align:center;' id='a".$data['st_id']."'><img src='".INFUSIONS."al_streams/loading.gif' /><br />Loading...</div>";
echo "</td>";
if ($i%2==0) { echo "</tr><tr>"; }
}
$z = $count%2;
if ($z > 0) {
for ($x=1;$x<=$z;$x++) {
echo "<td width='50%' class='tbl1'>&nbsp;</td>";
}
}
$ids_json = json_encode($ids);
echo "<script type='text/javascript'>

$(document).ready(function(){
var xxx = '".$ids_json."';
var ids = eval( '(' +xxx+ ')' );
//ids = '".$ids_json."';
var num = '".$i."';
var bdir = '".BASEDIR."';

for (var i = 1;i <= num; i++) {

$.ajax({
type: 'post',
url: bdir+'infusions/al_streams/backend.php',
dataType: 'json',
cache: true,
data: {
provider: ids[i].provider,
provider_id: ids[i].ident,
id: ids[i].id,
action: 'get_info'
},
success: function(data){
if (data.is_live == 1) {
var info = '".$locale['ss11']."<br />".$locale['ss12']."'+data.view+' ".$locale['ss33']."'+data.online_chat;
} else {
var info = '".$locale['ss13']."';
}

$(data.id).empty().html('<a href=\'streams.php?action=view&id='+data.sid+'\'><img src=\''+data.img+'\' width=\'200\' height=\'120\' /></a><br />'+info);
}
});

}

});


</script>";

echo "</tr></table>";
} else {

}
closetable();



}

require_once THEMES."templates/footer.php";
?>
