<?php
require_once "../../maincore.php";
require_once INFUSIONS."al_genmem/infusion_db.php";


if (isset($_POST['action']) && $_POST['action'] == "vote") {

    $result = dbquery("SELECT * FROM ".DB_GEM_MEMS." WHERE mem_id='".$_POST['id']."'");
    if (dbrows($result)) {
        $data = dbarray($result);
        $voters = explode("|",$data['mem_voters']);
        if (!in_array(FUSION_IP,$voters)) {
            $new_rating = $_POST['type'] == 1 ? $data['mem_rating']+1 : $data['mem_rating']-1;
            $voters_str = implode("|",array_merge($voters,array(FUSION_IP)));
            $upd = dbquery("UPDATE ".DB_GEM_MEMS." SET mem_rating='".$new_rating."', mem_voters='".$voters_str."' WHERE mem_id='".$data['mem_id']."'");
            print(json_encode(array('new_rating'=>$new_rating,'id'=>$data['mem_id'])));
        }
    }
    die();


}

if (isset($_POST['action']) && $_POST['action'] == "vote_gen") {

    $result = dbquery("SELECT * FROM ".DB_GEM_GENERATORS." WHERE gen_id='".$_POST['id']."'");
    if (dbrows($result)) {
        $data = dbarray($result);
        $voters = explode("|",$data['gen_voters']);
        if (!in_array(FUSION_IP,$voters)) {
            $new_rating = $_POST['type'] == 1 ? $data['gen_rating']+1 : $data['gen_rating']-1;
            $voters_str = implode("|",array_merge($voters,array(FUSION_IP)));
            $upd = dbquery("UPDATE ".DB_GEM_GENERATORS." SET gen_rating='".$new_rating."', gen_voters='".$voters_str."' WHERE gen_id='".$data['gen_id']."'");
            print(json_encode(array('new_rating'=>$new_rating,'id'=>$data['gen_id'])));
        }
    }
    die();


}


?>