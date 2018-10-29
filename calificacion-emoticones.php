<?php
/**
 * Plugin Name: Reacción Emoticones
 * Plugin URI: #
 * Description: Crea Calificación por cada Noticia validando una sola vez y presenta estadísticas en el panel de administración
 * Version: 1.0.0
 * Author: Ariel Burgos
 * Author URI: https://ecuadata.net/perfil
 * Requires at least: 3.0,1
 * Tested up to: 4.9
 *
 * Text Domain: reaccion-emoticones
 */

defined( 'ABSPATH' ) or die( 'Prohibido XD' );


//registro de style "a lo wordpress"


function calificacion_emoticones_enqueue_style(){
   wp_register_style( 'calificacion_emoticiones_style', plugins_url("css/style.css", __FILE__ ) );
    wp_enqueue_style( 'calificacion_emoticiones_style' );

    wp_enqueue_script( 'script', plugins_url("js/funciones.js", __FILE__ ), array ( 'jquery' ), 1.0, true);
    wp_localize_script( 'script', 'my_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('wp_enqueue_scripts', 'calificacion_emoticones_enqueue_style');

/* acción crear tabla */
register_activation_hook( __FILE__, 'db_calificacion_emoticon' );
function db_calificacion_emoticon() {
    // aqui crearemos la tabla
    global $wpdb;
    $table_name = $wpdb->prefix . "caliemoticones";
    $sql = "CREATE TABLE $table_name(
                 ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                 nemoticon VARCHAR(80) NOT NULL DEFAULT '',
                 npost VARCHAR (255) NOT NULL DEFAULT '',
                 puntuacion VARCHAR (255) NOT NULL DEFAULT '',
                 ip VARCHAR (18) NOT NULL DEFAULT '',
                 fecha DATETIME,
                 PRIMARY KEY (ID));";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}


/*
* @description Hook que se ejecuta al desactivar el plugin
*/
register_deactivation_hook(__FILE__, 'db_calificacion_emoticon_remove_tables' );

/*
* @description Función que se ejecuta al desactivar el plugin
*/
function db_calificacion_emoticon_remove_tables()
{
    //obtenemos el objeto $wpdb
    global $wpdb;

    //el nombre de la tabla, utilizamos el prefijo de wordpress
    $table_name = $wpdb->prefix . 'caliemoticones';

    //sql con el statement de la tabla
    $sql = "DROP table IF EXISTS $table_name";

    $wpdb->query($sql);

}



function add_calificacion_emoticon($content) {
    $content.= '<h4 style="color:#FF5733; margin-bottom: 10px;margin-left: 5px; font-size: 12px;" id="calificacionemoticones">DÁNOS TU REACCIÓN</h4>';
    if (is_single()) {

      $content.= '<div id="rspemo">';
      include('emoticones.php');
      $content.= '</div>';
    }
    return $content;
}
add_action('the_content', 'add_calificacion_emoticon');


function mi_funcion_ajax(){
    //verificacion de post con usuario
    global $wpdb;
    $posID = $_POST['idpost'];
    $numCli = $_POST['numerip'];
    $emot =  $_POST['nom'];
    $table_name = $wpdb->prefix . 'caliemoticones';
    $table_update = $wpdb->prefix . 'caliemoticones';
    if(empty($_POST['numerip']) and ($_POST['numerip']=="")){

        echo "vacio";
    }else {

        date_default_timezone_set('America/Guayaquil');//define tu zona horaria

        $r0w = $wpdb->get_results("SELECT * FROM $table_name WHERE npost=$posID and ip=$numCli");
        if ($r0w) {

          $wpdb->update(
             $table_update,
              array(
                'nemoticon' => $emot),
                    array(
                       'npost' => $_POST['idpost'],
                       'ip' => $_POST['numerip']
                     ),
                     array('%s'), array( '%s', '%s') );



        } else {
          date_default_timezone_set('America/Guayaquil');
            $fechaHora = date("Y-m-d H:i:s");
            $wpdb->insert(
                $table_name,
                array(
                    'nemoticon' => $_POST['nom'],
                    'npost' => $_POST['idpost'],
                    'puntuacion' => $_POST['pt'],
                    'ip' => $_POST['numerip'],
                    'fecha' => $fechaHora
                )
            );

        }

    }

    require("emoticones.php");
    echo $content_core;

}
// Creando las llamadas Ajax para el plugin de WordPress
add_action( 'wp_ajax_nopriv_mi_funcion_accion', 'mi_funcion_ajax' );
add_action( 'wp_ajax_mi_funcion_accion', 'mi_funcion_ajax' );


//panel de administracion

add_action("admin_menu", "EmoticonAdmin");
function EmoticonAdmin() {
    add_menu_page('EM Reacciones',
            'EM Reacciones',
        'manage_options',
        'reaccionesemo',
        'output_menu');
}
function output_menu()
{
    ?>
    <link rel="stylesheet" href="../wp-content/plugins/CalificacionEmoticones/css/style.css"/>
    <div class="contenedor_estadistica">
    <div id="titulo_estadistica">Bienvenido a las Estadisticas de las Reacciones de <?php echo bloginfo('name'); ?>
    </div>
    <div id="tabla_estadistica">
    <table>
    <tr>
        <td id="tituloe" class="meencanta">Me Encanta</td>
        <td id="tituloe" class="mesorprende">Me Sorprende</td>
        <td id="tituloe" class="mealegra">Me Alegra</td>
        <td id="tituloe" class="meentristece">Me Entristece</td>
        <td id="tituloe" class="meenoja">Me Enoja</td>
        <td id="tituloe">Link del Post</td>
    </tr>
    <?php
    global $wpdb;


    $table_name = $wpdb->prefix . 'caliemoticones';


    //codigo para paginacion

    $items_per_page = 10;
    $page = isset($_GET['cpage']) && !empty($_GET['cpage']) ? $_GET['cpage'] : 1;
    $offset = ($page * $items_per_page) - $items_per_page;
    $querynormal = $wpdb->get_results("SELECT npost, COUNT(*) numpost FROM $table_name GROUP BY npost HAVING COUNT(*) > 0");
    $stotal = $wpdb->num_rows;
    $respuestax = $wpdb->get_results("SELECT npost, COUNT(*) numpost FROM $table_name GROUP BY npost HAVING COUNT(*) > 0 lIMIT $offset,$items_per_page");

    $totalPage = ceil($stotal / $items_per_page);



        if($totalPage > 0){

            echo '<div>';
            for ($i = 1; $i <= $totalPage ; $i++) {
                $separator = ( $i < $totalPage  ) ? ' | ' : '';
                $url_args = add_query_arg( 'cpage', $i );
                echo  "<a href='$url_args' class='btn-paginador'>Pagina $i</a>".$separator;
            }
            echo '</div><br><br><br>';

        foreach ($respuestax as $resx) {

                //codigo para identificar las noticias por nombre
                $idPostx = $resx->npost;

                $querystr = "SELECT $wpdb->posts.* FROM $wpdb->posts, $wpdb->postmeta WHERE $wpdb->posts.ID = $idPostx";

                $pageposts = $wpdb->get_results($querystr, OBJECT);



                global $wpdb;
                //codigo para identificar las reacciones por separado
                $table_n = $wpdb->prefix . 'caliemoticones';

                $res0 = $wpdb->get_results("SELECT SUM(puntuacion) as me_sorprende FROM $table_n WHERE npost=$idPostx and nemoticon='1'");

                $res1 = $wpdb->get_results("SELECT SUM(puntuacion) as me_alegra  FROM $table_n WHERE npost=$idPostx and nemoticon='2'");

                $res2 = $wpdb->get_results("SELECT SUM(puntuacion) as me_entristece  FROM $table_n WHERE npost=$idPostx and nemoticon='3'");

                $res3 = $wpdb->get_results("SELECT SUM(puntuacion) as me_enoja  FROM $table_n WHERE npost=$idPostx and nemoticon='4'");
                
                $res4 = $wpdb->get_results("SELECT SUM(puntuacion) as me_encanta  FROM $table_n WHERE npost=$idPostx and nemoticon='5'");
                //asignar variable
                $rea1 = $res0[0]->me_sorprende;
                $rea2 = $res1[0]->me_alegra;
                $rea3 = $res2[0]->me_entristece;
                $rea4 = $res3[0]->me_enoja;
                 $rea5 = $res4[0]->me_encanta;
                $noti = $pageposts[0]->post_title;
                if (empty($rea1)) {
                    $rea1 = '0';
                }
                if (empty($rea2)) {
                    $rea2 = '0';
                }
                if (empty($rea3)) {
                    $rea3 = '0';
                }
                if (empty($rea4)) {
                    $rea4 = '0';
                }
                if (empty($rea5)) {
                    $rea5 = '0';
                }



            echo '<tr><td>' . $rea5 . '</td>';
            echo '<td>' . $rea1 . '</td>';
            echo '<td>' . $rea2 . '</td>';
            echo '<td>' . $rea3 . '</td>';
            echo '<td>' . $rea4 . '</td>';
            echo '<td id="enlaces"><a href="../?p=' . $resx->npost . '" target="blank">'.$noti.'</a></td></tr>';

        }

        echo '<div id="copyright">Desarrollador: Ariel Burgos - Plugin Open Source cualquier sugerencia <a href="mailto:aburgos@ecuadata.net" target="blank"> escribir aquí</a></div>';

        }


        ?>



        </table>
    </div>
    </div>
    <?php
}
