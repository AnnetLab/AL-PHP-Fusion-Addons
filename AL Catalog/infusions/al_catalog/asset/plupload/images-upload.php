<?php
require_once "../../../../maincore.php";
require_once INCLUDES."infusions_include.php";
require_once INFUSIONS."al_catalog/infusion_db.php";

if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {

    $upload_dir = AL_CATALOG_DIR."uploads/";

    header('Content-Type: application/json');

    $uploaded = upload_image(
        "file", "", $upload_dir, $catalog_settings['photo_max_width'], $catalog_settings['photo_max_height'],
        $catalog_settings['max_photo_size'], false, true, true,
        0, $upload_dir, "_t1", $catalog_settings['cat_thumb_width'], $catalog_settings['cat_thumb_height'],
        0, $upload_dir, "_t2", $catalog_settings['item_thumb_width'], $catalog_settings['item_thumb_height']
    );

    if ($uploaded['error'] == 0) {

        $insert = dbquery("INSERT INTO ".DB_AL_CATALOG_IMAGES." (ctg_image_file,ctg_image_thumb,ctg_image_thumb_item) VALUES ('".$uploaded['image_name']."','".$uploaded['thumb1_name']."','".$uploaded['thumb2_name']."')");
        $id = mysql_insert_id();
        die('{"jsonrpc" : "2.0", "thumb" : "'.$uploaded['thumb1_name'].'", "id" : "'.$id.'"}');

    } else {
        die('{"jsonrpc" : "2.0", "result" : "'.$uploaded['error'].'"}');
    }

}

if (isset($_POST['action']) && $_POST['action'] == "delete_image" && isset($_POST['image_id']) && isnum($_POST['image_id'])) {

    $img_result = dbquery("SELECT * FROM ".DB_AL_CATALOG_IMAGES." WHERE ctg_image_id='".$_POST['image_id']."'");
    if (dbrows($img_result)) {
        $img_data = dbarray($img_result);
        if (file_exists(AL_CATALOG_DIR."uploads/".$img_data['ctg_image_file'])) {
            unlink(AL_CATALOG_DIR."uploads/".$img_data['ctg_image_file']);
        }
        if (file_exists(AL_CATALOG_DIR."uploads/".$img_data['ctg_image_thumb'])) {
            unlink(AL_CATALOG_DIR."uploads/".$img_data['ctg_image_thumb']);
        }
        $del = dbquery("DELETE FROM ".DB_AL_CATALOG_IMAGES." WHERE ctg_image_id='".$_POST['image_id']."'");
    }
    header('Content-Type: application/json');
    echo json_encode(array('status'=>'ok'));
    die;

}

?>