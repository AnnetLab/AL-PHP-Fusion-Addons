<?php
require_once "../../../maincore.php";
require_once INCLUDES."infusions_include.php";
require_once INFUSIONS."al_genmem/includes/image_functions.php";
if(isset($_FILES['image'])){

    $image_uploaded = upload_image("image", "", INFUSIONS."al_genmem/asset/generators/originals/", "1800", "1600","1500000", false, false, false);
    if ($image_uploaded['error'] == 0) {
        $result = create_memdem_images($image_uploaded['image_name']);
        $result['original'] = $image_uploaded['image_name'];
        $success = true;
        $error = false;
    } else {
        $success = false;
        $error = $image_uploaded['error'];
        $result = array();
    }
    print json_encode(array_merge(array("success"=>$success,"error"=>$error),$result));

}

?>