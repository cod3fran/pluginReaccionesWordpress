<?php
$iduserip=$_SERVER["REMOTE_ADDR"];
$ipuserplace=str_replace('.', '0',$iduserip);
if (!isset($_POST['idpost']))
    $emo_postID = get_the_ID();
else
    $emo_postID = $_POST['idpost'];//por si viene por ajax
$Nemoticones = array('Me Sorprende' => 1, 'Me Alegra' => 2, 'Me Entristese' => 3, 'Me Enoja' => 4, 'Me Encanta' => 5);
global $wpdb;
$table_name = $wpdb->prefix . 'caliemoticones';
$res0= $wpdb->get_results( "SELECT SUM(puntuacion) as me_sorprende FROM $table_name WHERE npost=$emo_postID and nemoticon='1'" );

$res1= $wpdb->get_results( "SELECT SUM(puntuacion) as me_alegra  FROM $table_name WHERE npost=$emo_postID and nemoticon='2'" );

$res2= $wpdb->get_results( "SELECT SUM(puntuacion) as me_entristece  FROM $table_name WHERE npost=$emo_postID and nemoticon='3'" );

$res3= $wpdb->get_results( "SELECT SUM(puntuacion) as me_enoja  FROM $table_name WHERE npost=$emo_postID and nemoticon='4'" );

$res4= $wpdb->get_results( "SELECT SUM(puntuacion) as me_encanta  FROM $table_name WHERE npost=$emo_postID and nemoticon='5'" );

$content_core = '<label><input type="checkbox" value="'.$Nemoticones['Me Encanta'].'" name="nemoticon" id="nemoticon"><img src="'.plugins_url("image/emoticones/me_encanta2.png", __FILE__ ) .'"  class="e1" /><span>'.$res4[0]->me_encanta.'</span><div id="nombreEmoticon1">Me Encanta</div></label><label><input type="checkbox" value="'.$Nemoticones['Me Sorprende'].'" name="nemoticon" id="nemoticon"><img src="'.plugins_url("image/emoticones/asombrado2.png", __FILE__ ) .'"  class="e1" /><span>'.$res0[0]->me_sorprende.'</span><div id="nombreEmoticon1">Me Sorprende</div></label>
   <label><input type="checkbox" value="'.$Nemoticones['Me Alegra'].'" name="nemoticon" id="nemoticon"><img src="'.plugins_url( "image/emoticones/alegra.png" , __FILE__ ).'"  class="e2"/><span>'.$res1[0]->me_alegra.'</span><div id="nombreEmoticon2">Me Alegra</div></label>
   <label><input type="checkbox" value="'.$Nemoticones['Me Entristese'].'" name="nemoticon" id="nemoticon"><img src="'.plugins_url( "image/emoticones/triste2.png" , __FILE__ ).'"  class="e3"/><span>'.$res2[0]->me_entristece.'</span><div id="nombreEmoticon3">Me entristece</div></label>
   <label><input type="checkbox" value="'.$Nemoticones['Me Enoja'].'" name="nemoticon" id="nemoticon"><img src="'.plugins_url( "image/emoticones/enojado2.png" , __FILE__ ).'"  class="e4"/><span>'.$res3[0]->me_enoja.'</span><div id="nombreEmoticon4">Me Enoja</div></label>
    <input type="hidden" name="Nemouser" value="'.$ipuserplace.'"><input type="hidden" value="'.$emo_postID.'" name="postID"/><div style="display:none;"> ';

$content.=
    '<style>* {cursor: pointer;}</style><div id="formu"><form action="" method="post" name="emoform" id="emoform"><div class="emoticones" id="enviar" onchange="calificaPost();return false;">
        '.$content_core.'
   </div> </form></div><div id="resultado"></div>';