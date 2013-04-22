<?php
require_once "maincore.php";
require_once INFUSIONS."al_genmem/infusion_db.php";
require_once THEMES."templates/header.php";
if (file_exists(INFUSIONS."al_genmem/locale/".$settings['locale'].".php")) {
    require_once INFUSIONS."al_genmem/locale/".$settings['locale'].".php";
} else {
    require_once INFUSIONS."al_genmem/locale/Russian.php";
}
require_once INCLUDES."comments_include.php";

echo "<script>
    var bdir = '".BASEDIR."';

        function quote_vote(type,id) {
            $.ajax({
                url: bdir+'infusions/al_genmem/backend.php',
                dataType: 'json',
                type: 'post',
                data: {
                    type: type,
                    id: id,
                    action: 'vote'
                },
                success: function(data){
                    $('.quote-rating-'+data.id).empty().append(data.new_rating);
                    $('.quote-rating-plus-'+data.id).hide();
                    $('.quote-rating-minus-'+data.id).hide();
                }
            });
            return false;
        }
</script>";


if (isset($_GET['view']) && isnum($_GET['view'])) {

    $result = dbquery("SELECT * FROM ".DB_GEM_MEMS." WHERE mem_id='".$_GET['view']."'");
    if (dbrows($result)) {
        $upd = dbquery("UPDATE ".DB_GEM_MEMS." SET mem_views=mem_views+1 WHERE mem_id='".$data['mem_id']."'");
        $data = dbarray($result);
        opentable($locale['gem18'].$data['mem_id']);
        echo "<div style='float:left;'>";
            echo "<a href='".FUSION_SELF."'>".$locale['gem19']."</a> / <a href='".BASEDIR."generator.php?action=create&id=".$data['mem_gen_id']."'>".$locale['gem27']."</a>";
        echo "</div>";
        echo "<div style='float:right;'>";
            $voters = explode("|",$data['mem_voters']);
            echo !in_array(FUSION_IP,$voters) ? "<a class='quote-rating-plus-".$data['mem_id']."' href='#vm' onclick='javascript: quote_vote(1,".$data['mem_id'].");'>+</a> " : "";
            echo "<span class='quote-rating-".$data['mem_id']."' style='color:".($data['mem_rating'] >= 0 ? "green" : "red").";'>".$data['mem_rating']."</span>";
            echo !in_array(FUSION_IP,$voters) ? " <a class='quote-rating-minus-".$data['mem_id']."' href='#vm' onclick='javascript: quote_vote(2,".$data['mem_id'].");'>-</a>" : "";
        echo "</div>";
        echo "<div style='clear:both;'></div>";
        echo "<div style='width:100%;text-align:center;'>";
            echo "<img src='".INFUSIONS."al_genmem/asset/images/".$data['mem_image']."' />";
            echo "<br /><br />";
            echo $data['mem_text1']."<br />".$data['mem_text2'];
        
        echo "</div>";

        closetable();

        echo showcomments("G", DB_GEM_MEMS, "mem_id", $_GET['view'], FUSION_SELF."?view=".$_GET['view']);

    } else {
        redirect(FUSION_SELF);
    }

} else {

    $total = dbcount("(mem_id)",DB_GEM_MEMS);
    if ($total > 0) {


        echo "<a href='".FUSION_SELF."'>".$locale['gem23']."</a> / <a href='".FUSION_SELF."?sort=popular'>".$locale['gem24']."</a> / <a href='".FUSION_SELF."?sort=best'>".$locale['gem25']."</a> / <a href='".FUSION_SELF."?sort=random'>".$locale['gem26']."</a> / <a href='".BASEDIR."generator.php?action=create'>".$locale['gem27']."</a>";
        if (!isset($_GET['rowstart']) ||  !isnum($_GET['rowstart'])) $_GET['rowstart'] = 0;
        if (isset($_GET['sort'])) {

            if ($_GET['sort'] == "popular") {

                $title = $locale['gem22'];
                $result = dbquery("SELECT * FROM ".DB_GEM_MEMS." ORDER BY mem_views DESC LIMIT ".$_GET['rowstart'].",16");

            } else if ($_GET['sort'] == "best") {

                $title = $locale['gem22'];
                $result = dbquery("SELECT * FROM ".DB_GEM_MEMS." ORDER BY mem_rating DESC LIMIT ".$_GET['rowstart'].",16");

            } else if ($_GET['sort'] == "random") {

                $title = $locale['gem22'];
                $result = dbquery("SELECT * FROM ".DB_GEM_MEMS." ORDER BY RAND() LIMIT ".$_GET['rowstart'].",16");

            } else {
                redirect(FUSION_SELF);
            }

        } else {

            $title = $locale['gem20'];
            $result = dbquery("SELECT * FROM ".DB_GEM_MEMS." ORDER BY mem_id DESC LIMIT ".$_GET['rowstart'].",16");

        }

        opentable($title);
            echo "<table width='100%'>";
            echo "<tr>";
            $i=0;
            while ($data=dbarray($result)) {
                if ($i%4==0 && $i!=0) echo "</tr><tr>";

                echo "<td class='tbl' width='25%' align='center'>";
                    echo "<a href='".FUSION_SELF."?view=".$data['mem_id']."'><img src='".INFUSIONS."al_genmem/asset/images/".$data['mem_image']."' width='80%' /></a><br /><br />";
                    $voters = explode("|",$data['mem_voters']);
                    echo !in_array(FUSION_IP,$voters) ? "<a class='quote-rating-plus-".$data['mem_id']."' href='#vm' onclick='javascript: quote_vote(1,".$data['mem_id'].");'>+</a> " : "";
                    echo "<span class='quote-rating-".$data['mem_id']."' style='color:".($data['mem_rating'] >= 0 ? "green" : "red").";'>".$data['mem_rating']."</span>";
                    echo !in_array(FUSION_IP,$voters) ? " <a class='quote-rating-minus-".$data['mem_id']."' href='#vm' onclick='javascript: quote_vote(2,".$data['mem_id'].");'>-</a>" : "";
                echo "<br /><br /></td>";

                $i++;
            }
            if ($i%4!=0) {
                while ($i%4!=0) {
                    echo "<td width='25%' class='tbl'></td>";
                    $i++;
                }
            }

            echo "</tr></table>";
            if ($total>16) {
                if (isset($_GET['sort'])) {
                    $link = FUSION_SELF."?sort=".$_GET['sort']."&";
                } else {
                    $link = FUSION_SELF."?";
                }
                echo "<div align='center' style=';margin-top:5px;'>".makepagenav($_GET['rowstart'],16,$total,3,$link)."</div>";
            }
        closetable();


    } else {
        opentable($locale['gem20']);
            echo $locale['gem21'];
        closetable();
    }

}


require_once THEMES."templates/footer.php";
?>