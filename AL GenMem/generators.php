<?php
require_once "maincore.php";
require_once INFUSIONS."al_genmem/infusion_db.php";
require_once THEMES."templates/header.php";
if (file_exists(INFUSIONS."al_genmem/locale/".$settings['locale'].".php")) {
    require_once INFUSIONS."al_genmem/locale/".$settings['locale'].".php";
} else {
    require_once INFUSIONS."al_genmem/locale/Russian.php";
}


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
                    action: 'vote_gen'
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


    $total = dbcount("(gen_id)",DB_GEM_GENERATORS);
    if ($total > 0) {


        echo "<a href='".FUSION_SELF."'>".$locale['gem23']."</a> / <a href='".FUSION_SELF."?sort=popular'>".$locale['gem24']."</a> / <a href='".FUSION_SELF."?sort=best'>".$locale['gem25']."</a> / <a href='".FUSION_SELF."?sort=random'>".$locale['gem26']."</a> / <a href='".BASEDIR."generator.php?action=create'>".$locale['gem27']."</a>";
        if (!isset($_GET['rowstart']) ||  !isnum($_GET['rowstart'])) $_GET['rowstart'] = 0;
        if (isset($_GET['sort'])) {

            if ($_GET['sort'] == "popular") {

                $title = $locale['gem22'];
                $result = dbquery("SELECT * FROM ".DB_GEM_GENERATORS." ORDER BY gen_views DESC LIMIT ".$_GET['rowstart'].",16");

            } else if ($_GET['sort'] == "best") {

                $title = $locale['gem22'];
                $result = dbquery("SELECT * FROM ".DB_GEM_GENERATORS." ORDER BY gen_rating DESC LIMIT ".$_GET['rowstart'].",16");

            } else if ($_GET['sort'] == "random") {

                $title = $locale['gem22'];
                $result = dbquery("SELECT * FROM ".DB_GEM_GENERATORS." ORDER BY RAND() LIMIT ".$_GET['rowstart'].",16");

            } else {
                redirect(FUSION_SELF);
            }

        } else {

            $title = $locale['gem20'];
            $result = dbquery("SELECT * FROM ".DB_GEM_GENERATORS." ORDER BY gen_id DESC LIMIT ".$_GET['rowstart'].",16");

        }

        opentable($title);
        echo "<table width='100%'>";
        echo "<tr>";
        $i=0;
        while ($data=dbarray($result)) {
            if ($i%4==0 && $i!=0) echo "</tr><tr>";

            echo "<td class='tbl' width='25%' align='center'>";
            echo "<a href='".BASEDIR."generator.php?action=create&id=".$data['gen_id']."'><img src='".INFUSIONS."al_genmem/asset/generators/mems/".$data['gen_mem_image']."' width='80%' /></a><br /><br />";
            $voters = explode("|",$data['gen_voters']);
            echo !in_array(FUSION_IP,$voters) ? "<a class='quote-rating-plus-".$data['gen_id']."' href='#vm' onclick='javascript: quote_vote(1,".$data['gen_id'].");'>+</a> " : "";
            echo "<span class='quote-rating-".$data['gen_id']."' style='color:".($data['gen_rating'] >= 0 ? "green" : "red").";'>".$data['gen_rating']."</span>";
            echo !in_array(FUSION_IP,$voters) ? " <a class='quote-rating-minus-".$data['gen_id']."' href='#vm' onclick='javascript: quote_vote(2,".$data['gen_id'].");'>-</a>" : "";
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




require_once THEMES."templates/footer.php";
?>