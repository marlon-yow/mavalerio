<?php
/*!
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.5 [2022-fev-04]
* @copyleft GPLv3
*/
    if(file_exists(MAVALERIO_ALT_CONFIG_FOLDER.'sec_pg.php')){ require_once(MAVALERIO_ALT_CONFIG_FOLDER.'sec_pg.php'); }

    require_once (__DIR__.'/paginacao/_prePaginacao.php');

    if(file_exists(__DIR__.'/_preHead.php')){ require_once(__DIR__.'/_preHead.php'); }

    if(defined('LIB_BS')){
        if(LIB_BS == 3){
            echo "<body style='padding:45px 10px;'>";
        }else if(LIB_BS == 4){
            echo "<body class='bg-light'>";
        }else if(LIB_BS == 5){
            echo "<body class='bg-light'>";
        }
    }else{
        echo "<body>";
    }

    if(file_exists(__DIR__.'/menu.php')){ require_once(__DIR__.'/menu.php'); }
    if(file_exists(MAVALERIO_ALT_CONFIG_FOLDER."banner_print.php")){ include(MAVALERIO_ALT_CONFIG_FOLDER."banner_print.php");}
    if(file_exists(__DIR__.'/breadCrumbs.php')){ require_once(__DIR__.'/breadCrumbs.php'); }

    if(defined('LIB_BS')){
        if(LIB_BS == 3){
            echo "<div class='content' style='display:table;margin: 0 auto;'>";
        }else if(LIB_BS == 4){
            echo "<main role='main'>";
        }
    }

    if(file_exists(MAVALERIO_ALT_CONFIG_FOLDER."_body_extras.php")){ include(MAVALERIO_ALT_CONFIG_FOLDER."_body_extras.php");}
    ?>
    <script> var perm = <?php echo json_encode( (isset($_SESSION[$appname]['PERM']) ? $_SESSION[$appname]['PERM'] : "") );?></script>
    <span id='spgenInfo' style='position:fixed; right:20px; top:150px;'></span>
    <div tabindex="-1" role="dialog" class='modal' id='divmodal'></div>