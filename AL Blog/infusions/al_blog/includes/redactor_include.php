<?php defined("IN_FUSION") or die("DENIED");
add_to_head("<link rel='stylesheet' href='".INFUSIONS."al_blog/asset/js/redactor/redactor.css' type='text/css' media='screen' />");
//add_to_head("<script type='text/javascript' src='".INFUSIONS."al_blog/asset/js/redactor/redactor.min.js'></script>");
add_to_head("<script type='text/javascript' src='".INFUSIONS."al_blog/asset/js/redactor/redactor.js'></script>");
add_to_head("<script type='text/javascript' src='".INFUSIONS."al_blog/asset/js/redactor/ru.js'></script>");
add_to_head("<script type='text/javascript'>
    $(document).ready(function(){
		$('.redactor').redactor({
			imageUpload: '".INFUSIONS."al_blog/includes/image_upload.php',
			imageGetJson: '".INFUSIONS."al_blog/asset/uploaded_images/images.json?".mt_rand(1,111111111)."',
			buttons: ['bold', 'italic', 'deleted', '|','unorderedlist','|','image', 'video', 'table', 'link', '|','fontcolor', 'backcolor', '|', 'alignment', '|', 'horizontalrule'],
		    lang: 'ru'
		});
    });
</script>");
?>