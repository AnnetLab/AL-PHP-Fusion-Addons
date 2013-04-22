<?php
require_once "maincore.php";
require_once INFUSIONS."al_genmem/infusion_db.php";
require_once THEMES."templates/header.php";
if (file_exists(INFUSIONS."al_genmem/locale/".$settings['locale'].".php")) {
    require_once INFUSIONS."al_genmem/locale/".$settings['locale'].".php";
} else {
    require_once INFUSIONS."al_genmem/locale/Russian.php";
}

$genmem_settings['mem_height'] = 600;
$genmem_settings['mem_width'] = 800;

if (isset($_POST['create_generator'])) {

    if ($_POST['name'] == '') {
        $data = dbarray(dbquery("SELECT * FROM ".DB_GEM_GENERATORS." ORDER BY gen_id DESC LIMIT 1"));
        $name = "#".($data['gen_id']+1);
    } else {
        $name = trim(stripinput($_POST['name']));
    }
    $new = dbquery("INSERT INTO ".DB_GEM_GENERATORS." (gen_name,gen_thumb_image,gen_mem_image,gen_dem_image,gen_views,gen_rating,gen_voters) VALUES ('".$name."','".$_POST['original']."','".$_POST['mem']."','".$_POST['dem']."','0','0','')");
    $id = mysql_insert_id();
    redirect(FUSION_SELF."?action=create&id=".$id);
}


if (isset($_GET['action']) && $_GET['action'] == "create") {
    if (isset($_GET['id']) && isnum($_GET['id'])) {
        //load generator

        $result = dbquery("SELECT * FROM ".DB_GEM_GENERATORS." WHERE gen_id='".$_GET['id']."'");
        if (dbrows($result)) {

            add_to_head("<link href='".INFUSIONS."al_genmem/includes/colorpicker/jquery.miniColors.css' rel='stylesheet' media='screen' />");
            add_to_head("<script src='".INFUSIONS."al_genmem/includes/colorpicker/jquery.miniColors.min.js'></script>");

            $meminfo = dbarray($result);
            opentable($locale['gem7']);
            echo "<table width='100%'>";
            echo "<tr valign='top'><td width='70%' class='tbl'>";
            echo "<div id='image-preview' style='width:500px;text-align:center;min-height:".$genmem_settings['mem_height']."px'><img src='".INFUSIONS."al_genmem/includes/gen_image.php?image=".$meminfo['gen_id']."&gen_type=1&text1=&text2=' style='max-width:500px;' /></div>";
            echo "</td><td class='tbl' width='30%'>";
            echo "<label for='gen_type_1'><input id='gen_type_1' type='radio' name='gen_type' value='1' checked='checked' /> ".$locale['gem8']."</label> <label for='gen_type_2'><input id='gen_type_2' type='radio' name='gen_type' value='2' /> ".$locale['gem9']."</label><br />";
            echo $locale['gem10']." <input type='text' class='textbox' name='text1' /><br />";
            echo "<div id='options_1'>";
                echo $locale['gem12']." <select name='size1' class='textbox'>";
                    echo "<option value='12'>12</option>";
                    echo "<option value='16'>16</option>";
                    echo "<option value='24'>24</option>";
                    echo "<option value='32' selected='selected'>32</option>";
                    echo "<option value='48'>48</option>";
                echo "</select><br />";
                echo $locale['gem13']." <select name='font1' class='textbox'>";
                    echo "<option value='1'>Impact</option>";
                    echo "<option value='2'>Arial</option>";
                    echo "<option value='3'>Tahoma</option>";
                    echo "<option value='4'>Times</option>";
                    echo "<option value='5'>Vardana</option>";
                echo "</select><br />";
                echo $locale['gem14']." <input type='text' name='color1' class='textbox' value='#FFFFFF' style='width:50px;' />";
            echo "</div>";


            echo $locale['gem11']." <input type='text' class='textbox' name='text2' /><br />";
            echo "<div id='options_2'>";
                echo $locale['gem12']." <select name='size2' class='textbox'>";
                    echo "<option value='12'>12</option>";
                    echo "<option value='16'>16</option>";
                    echo "<option value='24'>24</option>";
                    echo "<option value='32' selected='selected'>32</option>";
                    echo "<option value='48'>48</option>";
                echo "</select><br />";
                echo $locale['gem13']." <select name='font2' class='textbox'>";
                    echo "<option value='1'>Impact</option>";
                    echo "<option value='2'>Arial</option>";
                    echo "<option value='3'>Tahoma</option>";
                    echo "<option value='4'>Times</option>";
                    echo "<option value='5'>Vardana</option>";
                echo "</select><br />";
                echo $locale['gem14']." <input type='text' name='color2' class='textbox' value='#FFFFFF' style='width:50px;' />";
            echo "</div>";
            echo "<a href='#opt' id='toggle-options'>".$locale['gem15']."</a><br /><br /><input type='submit' class='button' name='save_dem' value='".$locale['gem16']."' />";
            echo "</td></tr>";
            echo "</table>";
            closetable();

            echo "<script>

            $(document).ready(function(){
                

                
                var gen_type = 1;
                var image = '".$meminfo['gen_id']."';
                var text1 = '';
                var text2 = '';
                var size1 = '32';
                var size2 = '32';
                var font1 = '1';
                var font2 = '1';
                var color1 = 'ffffff';
                var color2 = 'ffffff';

                $('input[name=color1]').miniColors({
                    close: function(hex, rgba) {
                        color1 = hex.substr(1);
                        refresh_image();
                    }
                });
                $('input[name=color2]').miniColors({
                    close: function(hex, rgba) {
                        color2 = hex.substr(1);
                        refresh_image();
                    }
                });

                $('#gen_type_1').click(function(){
                    gen_type = 1;
                    refresh_image();
                });
                $('#gen_type_2').click(function(){
                    gen_type = 2;
                    refresh_image();
                });
                $('input[name=text1]').blur(function(){
                    text1 = $('input[name=text1]').val();
                    refresh_image();
                })
                $('input[name=text2]').blur(function(){
                    text2 = $('input[name=text2]').val();
                    refresh_image();
                })
                $('select[name=size1]').change(function(){
                    size1 = $('select[name=size1]').val();
                    refresh_image();
                })
                $('select[name=size2]').change(function(){
                    size2 = $('select[name=size2]').val();
                    refresh_image();
                })
                $('select[name=font1]').change(function(){
                    font1 = $('select[name=font1]').val();
                    refresh_image();
                })
                $('select[name=font2]').change(function(){
                    font2 = $('select[name=font2]').val();
                    refresh_image();
                })

                $('#options_1').hide();
                $('#options_2').hide();
                $('#toggle-options').click(function(){
                    $('#options_1').toggle('slow');
                    $('#options_2').toggle('slow');
                })


                function refresh_image() {
                    $('#image-preview').html('').append('<img src=\'".INFUSIONS."al_genmem/includes/gen_image.php?image='+image+'&gen_type='+gen_type+'&text1='+text1+'&text2='+text2+'&size1='+size1+'&size2='+size2+'&font1='+font1+'&font2='+font2+'&color1='+color1+'&color2='+color2+'\' style=\'max-width:500px;\' />');
                }

                $('input[name=save_dem]').click(function(){

                    if (text1 != '' || text2 != '') {
                    $(this).attr('disabled','disabled').css('opacity','0.5').val('".$locale['gem17']."');
                    $.ajax({
                        url: '".INFUSIONS."al_genmem/includes/gen_image.php?image='+image+'&gen_type='+gen_type+'&text1='+text1+'&text2='+text2+'&size1='+size1+'&size2='+size2+'&font1='+font1+'&font2='+font2+'&color1='+color1+'&color2='+color2+'&action=save',
                        type: 'GET',
                        dataType: 'JSON',
                        success: function(data) {
                            //alert(data);
                            if (data.result == 'success') {
                                //alert('ok');
                                window.location.href = 'mems.php?view='+data.id;
                            }
                        }
                    });
                }
                });

            });

            </script>";


        } else {
            redirect(FUSION_SELF);
        }


    } else {

        opentable($locale['gem2']);

        echo "<table width='100%'>";
        echo "<tr>";
            echo "<td class='tbl' width='200'>".$locale['gem3']."</td>";
            echo "<td class='tbl'>";
                echo "<form id='inputform' method='post' action='".FUSION_SELF."'>";
                echo "<input type='text' name='name' class='textbox' style='width:250px;' />";
                echo "<input type='hidden' name='original' value='' />";
                echo "<input type='hidden' name='mem' value='' />";
                echo "<input type='hidden' name='dem' value='' />";
                echo "<input type='hidden' name='create_generator' />";
                echo "</form>";
            echo "</td>";
        echo "</tr><tr valign='top'>";
            echo "<td class='tbl'>".$locale['gem4']."</td>";
            echo "<td class='tbl'>";
                echo "<form method='post' action='' enctype='multipart/form-data'>";
                echo "<input type='file' name='image' id='image-upload' class='textbox' />";
                echo "</form><br />";
                echo "<div id='image-loading'>".$locale['gem5']."<img src='".INFUSIONS."al_genmem/asset/loading.gif' /></div>";
                echo "<div id='images-uploaded'></div>";
            echo "</td>";
        echo "</tr><tr>";
            echo "<td class='tbl'></td><td class='tbl'><input type='submit' id='generator-submit' name='create_generator' class='button' value='".$locale['gem6']."' /></td>";
        echo "</tr>";
        echo "</table>";
        closetable();

        add_to_head("<script src='".INFUSIONS."al_genmem/includes/ajaxupload/ajaxfileupload.js' type='text/javascript'></script>");
        echo "<script>
            $(document).ready(function(){

            $('#inputform').keypress(function(event){
                if (event.keyCode == 13) {
                return false;
                }
            });
            $('#generator-submit').click(function(){
                $('#inputform').submit();
            });

            $('#image-loading').hide();
            $('#generator-submit').hide();
                $('#image-upload').change(function(){

                    $('#image-loading')
                    .ajaxStart(function(){
                        $(this).show();
                    })
                    .ajaxComplete(function(){
                        $(this).hide();
                    });
                    $.ajaxFileUpload({
                            url: '".INFUSIONS."al_genmem/includes/backend_upload.php',
                            secureuri:false,
                            fileElementId:'image-upload',
                            dataType: 'json',
                            success: function (data){
                                //console.log(data);
                                if (data.success == true) {
                                    $('#images-uploaded').html('');
                                    $('#generator-submit').show();
                                    //$('#images-uploaded').append('<img src=\'".INFUSIONS."al_genmem/asset/generators/originals/'+data.original+'\' />');
                                    $('#images-uploaded').append('<img src=\'".INFUSIONS."al_genmem/asset/generators/mems/'+data.mem+'\' />');
                                    //$('#images-uploaded').append('<img src=\'".INFUSIONS."al_genmem/asset/generators/dems/'+data.dem+'\' />');
                                    $('input[name=original]').val(data.original);
                                    $('input[name=mem]').val(data.mem);
                                    $('input[name=dem]').val(data.dem);
                                } else {
                                    switch (data.error) {
                                        case 1:
                                            alert('".$locale['gem_imgerr_1']."');
                                        break;
                                        case 2:
                                            alert('".$locale['gem_imgerr_2']."');
                                        break;
                                        case 3:
                                            alert('".$locale['gem_imgerr_3']."');
                                        break;
                                    }
                                }
                            },
                            error: function(data) {
                                alert('error');
                            }
                    });

		            return false;
                });
            });


        </script>";

    }
} else {
    redirect(FUSION_SELF."?action=create");
}

require_once THEMES."templates/footer.php";
?>