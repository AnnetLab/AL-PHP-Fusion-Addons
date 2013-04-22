<?php
if (!defined("IN_FUSION")) { die("denied"); }

require_once INFUSIONS."al_streams/infusion_db.php";
if (file_exists(INFUSIONS."al_streams_panel/".$settings['locale'].".php")) {
    include INFUSIONS."al_streams_panel/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_streams_panel/Russian.php";
}
add_to_head("<link rel='stylesheet' type='text/css' href='".INFUSIONS."al_streams_panel/style.css' />");

$result = dbquery("SELECT st.*,u.user_name FROM ".DB_SS_STREAMS." st LEFT JOIN ".DB_USERS." u ON u.user_id=st.st_user ORDER BY st_id DESC");




openside($locale['alstrp1']);

if (dbrows($result)) {
    echo "<ul id='alstrp-list'>";
        echo "<li id='alstrp-loader' style='width:100%;text-align:center;'><img src='".INFUSIONS."al_streams_panel/loading.gif' /> Loading data...</li>";
        echo "<li id='alstrp-nostr'>".$locale['alstrp3']."</li>";
        $ids = array(); $i=0;
        while ($data = dbarray($result)) {
            $i++;
            $ids[$i] = array("provider"=>$data['st_provider'],"ident"=>$data['st_provider_id'],"id"=>$data['st_id']);
        }
    echo "</ul>";
    
    $ids_json = json_encode($ids);
    echo "<script type='text/javascript'>

    $(document).ready(function(){
        
        var xxx = '".$ids_json."';
        var ids = eval( '(' +xxx+ ')' );
        var num = '".$i."';
        var bdir = '".BASEDIR."';
        //var count_live = 0;
        //$('#alstrp-nostr').hide();

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
                        var info = '<a href=\''+bdir+'profile.php?lookup='+data.user_id+'\'>'+data.user_name+'</a><br />".$locale['alstrp4']."'+data.view+' ".$locale['alstrp5']."'+data.online_chat;
                        $('#alstrp-list').append('<li><a href=\'streams.php?action=view&id='+data.sid+'\'><img src=\''+data.img+'\' width=\'200\' height=\'120\' /></a><br />'+info+'</li>');
                        //count_live++;
                        $('#alstrp-nostr').hide();
                    } else {
                        //alert(num);
                        //if (i > num) {
                            //$('#alstrp-nostr').show();
                        //}
                    }
                

                    
                }
            });
            
        //if (i = num) {
        //if (count_live == 0) {
//            $('#alstrp-list').html('<li>".$locale['alstrp3']."</li>');
//        }
//        }
        }
            $('li#alstrp-loader').fadeOut();
            

    });


</script>";
    
    
    
} else {
    echo $locale['alstrp2'];
}

closeside();


?>