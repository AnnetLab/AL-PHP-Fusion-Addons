<?php
require_once AL_CATALOG_DIR."functions.php";
add_to_title(": ".$locale['ctg4']);

if (isset($_GET['status']) && !isset($message)) {
    if ($_GET['status'] == "success") {
        $message = $locale['ctg35'];
    } elseif ($_GET['status'] == "su") {
        $message = $locale['ctg36'];
    } elseif ($_GET['status'] == "del") {
        $message = $locale['ctg37'];
    }
    if ($message) {	echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; }
}
add_to_head("<script src='".AL_CATALOG_DIR."asset/plupload/js/plupload.full.js'></script>");

$result = dbquery("SELECT * FROM ".DB_AL_CATALOG_CATS);
if (dbrows($result)) {
    while ($data = dbarray($result)) {
        $cats[$data['ctg_cat_id']] = $data;
    }
} else {
    $cats = null;
}

$is_edit = false; $error = array();
if (isset($_POST['save'])) {

    $title = trim(stripinput($_POST['title']));
    $desc = trim(stripinput($_POST['desc']));
    $short_desc = trim(stripinput($_POST['short_desc']));
    $cost = trim(stripinput($_POST['cost']));
    $cat_id = $_POST['cat_id'];
    $cover_image = isset($_POST['cover']) && isnum($_POST['cover']) ? $_POST['cover'] : 0;
    $tab_1_title = '';
    $tab_1_desc = '';
    $tab_2_title = '';
    $tab_2_desc = '';
    $tab_3_title = '';
    $tab_3_desc = '';
    $tab_4_title = '';
    $tab_4_desc = '';
    $tab_5_title = '';
    $tab_5_desc = '';
    $tab_6_title = '';
    $tab_6_desc = '';
    $tab_7_title = '';
    $tab_7_desc = '';
    $tab_8_title = '';
    $tab_8_desc = '';
    $tab_9_title = '';
    $tab_9_desc = '';
    $tab_10_title = '';
    $tab_10_desc = '';

    if (isset($_POST['tab']) && !empty($_POST['tab'])) {
        $i=1;
        foreach ($_POST['tab'] as $tab) {
            if (isset($tab['title']) && !empty($tab['title'])) {
                ${"tab_".$i."_title"} = $tab['title'];
                ${"tab_".$i."_desc"} = addslash($tab['desc']);
            }
            $i++;
        }
    }

    if (isset($_POST['item_id']) && isnum($_POST['item_id'])) {

        dbquery("UPDATE ".DB_AL_CATALOG_ITEMS." SET ctg_item_title='".$title."',ctg_item_cost='".$cost."',ctg_item_short_desc='".$short_desc."',ctg_item_desc='".$desc."',ctg_item_cat='".$cat_id."',ctg_item_image='".$cover_image."',ctg_item_tab_1_title='".$tab_1_title."',ctg_item_tab_1_desc='".$tab_1_desc."',ctg_item_tab_2_title='".$tab_2_title."',ctg_item_tab_2_desc='".$tab_2_desc."',ctg_item_tab_3_title='".$tab_3_title."',ctg_item_tab_3_desc='".$tab_3_desc."',ctg_item_tab_4_title='".$tab_4_title."',ctg_item_tab_4_desc='".$tab_4_desc."',ctg_item_tab_5_title='".$tab_5_title."',ctg_item_tab_5_desc='".$tab_5_desc."',ctg_item_tab_6_title='".$tab_6_title."',ctg_item_tab_6_desc='".$tab_6_desc."',ctg_item_tab_7_title='".$tab_7_title."',ctg_item_tab_7_desc='".$tab_7_desc."',ctg_item_tab_8_title='".$tab_8_title."',ctg_item_tab_8_desc='".$tab_8_desc."',ctg_item_tab_9_title='".$tab_9_title."',ctg_item_tab_9_desc='".$tab_9_desc."',ctg_item_tab_10_title='".$tab_10_title."',ctg_item_tab_10_desc='".$tab_10_desc."' WHERE ctg_item_id='".$_POST['item_id']."'");

        dbquery("DELETE FROM ".DB_AL_CATALOG_IMAGES_ITEMS." WHERE ctg_item_id='".$_POST['item_id']."'");

    } else {

        dbquery("INSERT INTO ".DB_AL_CATALOG_ITEMS." (ctg_item_title,ctg_item_cost,ctg_item_short_desc,ctg_item_desc,ctg_item_cat,ctg_item_image,ctg_item_tab_1_title,ctg_item_tab_1_desc,ctg_item_tab_2_title,ctg_item_tab_2_desc,ctg_item_tab_3_title,ctg_item_tab_3_desc,ctg_item_tab_4_title,ctg_item_tab_4_desc,ctg_item_tab_5_title,ctg_item_tab_5_desc,ctg_item_tab_6_title,ctg_item_tab_6_desc,ctg_item_tab_7_title,ctg_item_tab_7_desc,ctg_item_tab_8_title,ctg_item_tab_8_desc,ctg_item_tab_9_title,ctg_item_tab_9_desc,ctg_item_tab_10_title,ctg_item_tab_10_desc)
        VALUES
        ('".$title."','".$cost."','".$short_desc."','".$desc."','".$cat_id."','".$cover_image."','".$tab_1_title."','".$tab_1_desc."','".$tab_2_title."','".$tab_2_desc."','".$tab_3_title."','".$tab_3_desc."','".$tab_4_title."','".$tab_4_desc."','".$tab_5_title."','".$tab_5_desc."','".$tab_6_title."','".$tab_6_desc."','".$tab_7_title."','".$tab_7_desc."','".$tab_8_title."','".$tab_8_desc."','".$tab_9_title."','".$tab_9_desc."','".$tab_10_title."','".$tab_10_desc."')");
        $item_id = mysql_insert_id();

    }

    $images_uploaded = array();
    if (isset($_POST['images-uploaded']) && !empty($_POST['images-uploaded'])) {
        foreach ($_POST['images-uploaded'] as $img) {
            dbquery("INSERT INTO ".DB_AL_CATALOG_IMAGES_ITEMS." (ctg_image_id,ctg_item_id) VALUES ('".$img."','".(isset($_POST['item_id']) ? $_POST['item_id'] : $item_id)."')");
            if (isset($_POST['images-hidden']) && !empty($_POST['images-hidden']) && in_array($img,$_POST['images-hidden'])) {
                dbquery("UPDATE ".DB_AL_CATALOG_IMAGES." SET ctg_image_show='0' WHERE ctg_image_id='".$img."'");
            }
        }
    }

    if (isset($_POST['item_id']) && isnum($_POST['item_id'])) {
        redirect(FUSION_SELF.$aidlink."&page=items&status=su");
    } else {
        redirect(FUSION_SELF.$aidlink."&page=items&status=success");
    }


} else if (isset($_POST['delete'])) {

    $result = dbquery("SELECT * FROM ".DB_AL_CATALOG_ITEMS." WHERE ctg_item_id='".$_POST['item_id']."'");
    if (dbrows($result)) {
        $del = dbquery("DELETE FROM ".DB_AL_CATALOG_ITEMS." WHERE ctg_item_id='".$_POST['item_id']."'");
    }
    redirect(FUSION_SELF.$aidlink."&page=items&status=del");

} else if (isset($_POST['edit'])) {

    $result = dbquery("SELECT * FROM ".DB_AL_CATALOG_ITEMS." WHERE ctg_item_id='".$_POST['item_id']."'");
    if (dbrows($result)) {
        $data = dbarray($result);
        $title = $data['ctg_item_title'];
        $cost = $data['ctg_item_cost'];
        $short_desc = $data['ctg_item_short_desc'];
        $desc = $data['ctg_item_desc'];
        $cat_id = $data['ctg_item_cat'];
        $cover_image = $data['ctg_item_image'];
        $cat_id = $data['ctg_item_cat'];
        $item_id = $data['ctg_item_id'];
        $is_edit = true;
        $tabs = array();
        for ($i=1;$i<=10;$i++) {
            if ($data['ctg_item_tab_'.$i.'_title'] && !empty($data['ctg_item_tab_'.$i.'_title'])) {
                $tabs[] = array(
                    'title' => $data['ctg_item_tab_'.$i.'_title'],
                    'desc' => phpentities(stripslashes($data['ctg_item_tab_'.$i.'_desc'])),
                );
            }
        }
        $images_result = dbquery("SELECT ii.*,i.* FROM ".DB_AL_CATALOG_IMAGES_ITEMS." ii LEFT JOIN ".DB_AL_CATALOG_IMAGES." i ON i.ctg_image_id=ii.ctg_image_id WHERE ii.ctg_item_id='".$data['ctg_item_id']."'");
        $images = array();
        if (dbrows($images_result)) {
            $images = make_assoc($images_result);
        }

    } else {
        redirect(FUSION_SELF.$aidlink."&page=items");
    }

} else {
    $title = '';
    $cost = '';
    $short_desc = '';
    $desc = '';
    $images = array();
    $tabs = array();
    $cover_image = '';
    $cat_id = '';
}


opentable($locale['ctg30']);

echo "<div style='width:100%;text-align:center;'>";
$result = dbquery("SELECT * FROM ".DB_AL_CATALOG_ITEMS);
if (dbrows($result)) {
    $items = make_assoc($result);
}
if (isset($items) && !empty($items)) {
    echo "<form action='".FUSION_SELF.$aidlink."&page=items' method='post'>";
    echo "<select name='item_id' class='textbox'>";
        foreach ($items as $item) {
            echo "<option value='".$item['ctg_item_id']."'>".$item['ctg_item_title']."</option>";
        }
    echo "</select>";
    echo "&nbsp;<input type='submit' name='edit' value='".$locale['ctg7']."' class='button' />";
    echo "&nbsp;<input type='submit' name='delete' value='".$locale['ctg8']."' class='button' />";
    echo "</form>";
} else {
    echo $locale['ctg32'];
}
echo "</div>";
closetable();

opentable($locale['ctg31']);
echo "<form action='".FUSION_SELF.$aidlink."&page=items' method='post' enctype='multipart/form-data'>";
echo "<table width='100%'>";
if (isset($error) && !empty($error)) {
    echo "<tr>";
    foreach ($error as $e) {
        echo "<td class='tbl'></td><td class='tbl'>".$e."</td>";
    }
    echo "</tr>";
}
echo "<tr>";
echo "<td class='tbl' width='250'>".$locale['ctg10']."</td>";
echo "<td class='tbl'><input type='text' class='textbox' style='width:250px;' name='title' value='".$title."' /></td>";
echo "</tr><tr>";
echo "<td class='tbl' width='250'>".$locale['ctg33']."</td>";
echo "<td class='tbl'><input type='text' class='textbox' style='width:250px;' name='cost' value='".$cost."' /></td>";
echo "</tr><tr>";
echo "<td class='tbl' width='250'>".$locale['ctg11a']."</td>";
echo "<td class='tbl'><textarea class='textbox' style='width:250px;' name='short_desc'>".$short_desc."</textarea></td>";
echo "</tr><tr>";
echo "<td class='tbl' width='250'>".$locale['ctg11']."</td>";
echo "<td class='tbl'><textarea class='textbox' style='width:250px;' name='desc'>".$desc."</textarea></td>";
echo "</tr><tr>";
echo "<td class='tbl'>".$locale['ctg12']."</td>";
echo "<td class='tbl'><select name='cat_id' class='textbox'><option value='0'>".$locale['ctg19']."</option>".build_cats_tree_select(build_cats_tree_array($cats),0,$cat_id)."</select></td>";
echo "</tr><tr valign='top'>";

    echo "<td class='tbl'>".$locale['ctg38']."</td>";
    echo "<td class='tbl'>";
        echo "<div id='files-uploaded'>";
            if ($images && !empty($images)) {
                foreach ($images as $image) {
                    echo "<div class='uimage uimage-".$image['ctg_image_id']."'><input type='radio' name='cover' value='".$image['ctg_image_id']."'".($image['ctg_image_id'] == $cover_image ? " checked='checked'" : "")." /><img src='".AL_CATALOG_DIR."uploads/".$image['ctg_image_thumb']."' height='50' /><a href='#' data-image-id='".$image['ctg_image_id']."' class='delete-image'>".$locale['ctg8']."</a><input type='hidden' name='images-uploaded[]' value='".$image['ctg_image_id']."' />
                    <input type='checkbox' name='images-hidden[]' value='".$image['ctg_image_id']."'".($image['ctg_image_show'] == 0 ? " checked" : "")." /> ".$locale['ctg49']."
                    <pre>[CATALOG_IMAGE_".$image['ctg_image_id']."]</pre>
                    </div>";
                }
            }
        echo "</div>";
        echo "<div id='files-container'><div id='filelist'></div><br /><a href='#' id='ctg-upload-image'>".$locale['ctg40']."</a></div>";
    echo "</td>";

echo "</tr><tr>";

    echo "<td class='tbl'>".$locale['ctg39']."</td>";
    echo "<td class='tbl' id='tabs-container'>";
        if ($tabs && !empty($tabs)) {
            foreach ($tabs as $key=>$tab) {
                echo "<input type='text' class='textbox' class='tab-title' name='tab[".$key."][title]' value='".$tab['title']."' /><br />";
                echo "<textarea class='textarea' name='tab[".$key."][desc]'>".$tab['desc']."</textarea><br />";
                $last_tab_key = $key+1;
            }
        } else {
            $last_tab_key = 0;
        }
        echo "<br /><a href='#' id='ctg-add-tab'>".$locale['ctg41']."</a><br /><br />";
    echo "</td>";

echo "</tr><tr>";

echo "<td colspan='2' class='tbl'><input type='submit' class='button' name='save' value='".$locale['ctg14']."' />";
if ($is_edit) {
    echo "<input type='hidden' name='item_id' value='".$item_id."' />";
    echo "&nbsp;<a class='button' href='".FUSION_SELF.$aidlink."&page=items'>".$locale['ctg15']."</a>";
}
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</form>";
closetable();

?>

<script type="text/javascript">

    $(document).ready(function(){

        var lastTabKey = <?php echo $last_tab_key; ?>;
        var addTabBtn = $('#ctg-add-tab');
        var deleteTEXT = '<?php echo $locale['ctg8']; ?>';
        var alCatalogDir = '<?php echo AL_CATALOG_DIR; ?>';

        addTabBtn.click(function(e){
            e.preventDefault();
            if (lastTabKey < 10) {
                lastTabKey = lastTabKey+1;
                $('#tabs-container').append(
                    '<input type="text" class="textbox" class="tab-title" name="tab['+lastTabKey+'][title]" /><br />' +
                    '<textarea class="textarea" name="tab['+lastTabKey+'][desc]"></textarea><br />');
                if (lastTabKey >= 10) {
                    addTabBtn.css('display','none');
                }
            }
        });

        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash',
            browse_button : 'ctg-upload-image',
            container : 'files-container',
            max_file_size : '2mb',
            url : alCatalogDir+'asset/plupload/images-upload.php',
            flash_swf_url : alCatalogDir+'asset/plupload/js/plupload.flash.swf',
            filters : [
                {title : 'Image files', extensions : 'jpg,gif,png'}
            ]
        });

        uploader.init();

        uploader.bind('FilesAdded', function(up, files) {
            $.each(files, function(i, file) {
                $('#filelist').append(
                    '<div id=\'' + file.id + '\'>' +
                        file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
                        '</div>');
            });

            up.refresh();
            uploader.start();
        });

        uploader.bind('UploadProgress', function(up, file) {
            $('#' + file.id + ' b').html(file.percent + '%');
        });

        uploader.bind('Error', function(up, err) {
            $('#filelist').append('<div>Error: ' + err.code +
                ', Message: ' + err.message +
                (err.file ? ', File: ' + err.file.name : '') +
                '</div>'
            );

            up.refresh();
        });

        uploader.bind('FileUploaded', function(up, file, data) {
            var response = $.parseJSON(data.response);
            $('#files-uploaded').append('<div class=\'uimage uimage-'+response.id+'\'><input type=\'radio\' name=\'cover\' value=\''+response.id+'\' /> <img src=\''+alCatalogDir+'uploads/'+response.thumb+'\' height=\'50\' /><a href=\'#\' data-image-id=\''+response.id+'\' class=\'delete-image\'>['+deleteTEXT+']</a><input type=\'hidden\' name=\'images-uploaded[]\' value=\''+response.id+'\' /><input type=\'checkbox\' name=\'images-hidden[]\' value=\''+response.id+'\' /> <?php echo $locale['ctg49']; ?><pre>[CATALOG_IMAGE_'+response.id+']</pre></div>');
            $('#' + file.id + ' b').html('100%');
            $('#' + file.id).fadeOut().remove();
        });

        $(document).on('click','.delete-image',function(e){
            e.preventDefault();
            var id = $(this).attr('data-image-id');
            $.ajax({
                url: alCatalogDir+'asset/plupload/images-upload.php',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    action: 'delete_image',
                    image_id: id
                },
                success: function() {
                    $('.uimage-'+id).remove();
                }
            });
        });

    });


</script>
