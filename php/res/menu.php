<?php
/*!
* @Autor MV https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.6 [2022-fev-05]
* @copyleft GPLv3
*/
    if(file_exists(MAVALERIO_ALT_CONFIG_FOLDER.'_menu.php')) require_once(MAVALERIO_ALT_CONFIG_FOLDER.'_menu.php');

    function criaLink($itemMenu){
        global $appname;

        $print = false;
        if(!isset($itemMenu['permissao'])){
            $print = true;
        }else if(is_array($itemMenu['permissao'])){
            foreach($itemMenu['permissao'] as $perm_temp){
                if(isset($_SESSION[$appname]["PERM"][$perm_temp])){$print = true;}
            }
        }else{
            if($_SESSION[$itemMenu[$appname]["PERM"]['permissao']]){$print = true;}
        }

        if(isset($itemMenu['sem_permissao'])){
            if(is_array($itemMenu['sem_permissao'])){
                foreach($itemMenu['sem_permissao'] as $perm_temp){
                    if($_SESSION[$appname]["PERM"][$perm_temp]){$print = false;}
                }
            }else{
               if($_SESSION[$appname]["PERM"][$itemMenu['sem_permissao']]){$print = false;}
            }
        }

        if( $print ){
            if(defined('LIB_BS') and LIB_BS == 4){
                if(isset($itemMenu['class'])){
                    $itemMenu['class'] .= ' dropdown-item';
                }else{
                    $itemMenu['class'] = ' dropdown-item';
                }
            }

            $titulo = (isset($itemMenu['titulo']) ? $itemMenu['titulo'] : '');
            $inside = "";
            $icon = "";
            if(isset($itemMenu['class'])){   $inside .= "class='".$itemMenu['class']."'"; }
            if(isset($itemMenu['onclick'])){ $inside .= "onclick=\"".$itemMenu['onclick']."\""; }
            if(isset($itemMenu['href'])){    $inside .= "href='".$itemMenu['href']."'"; }
            if(isset($itemMenu['target'])){  $inside .= "target='".$itemMenu['target']."'"; }
            if(isset($itemMenu['icon'])){    $icon   .= "<i class='".$itemMenu['icon']."'></i> "; }

            return "<a $inside >$icon $titulo</a>";
        }else{
            return 0;
        }
    }

    if(defined('LIB_BS') and LIB_BS == 4){ require_once (__DIR__.'/menu/_menu-bs4.php'); }
    if(defined('LIB_BS') and LIB_BS == 3){ require_once (__DIR__.'/menu/_menu-bs3.php'); }

