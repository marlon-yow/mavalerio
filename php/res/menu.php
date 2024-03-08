<?php
/*!
* @GREVOLYZADO
* @version 0.0.1.0 [2023-nov-06]
* @copyleft GPLv3
*/
    if(file_exists(MAVALERIO_ALT_CONFIG_FOLDER.'_menu.php')) require_once(MAVALERIO_ALT_CONFIG_FOLDER.'_menu.php');

    function criaLink($itemMenu){
        global $appname;

        $print = false;
        if(!isset($itemMenu['permissao']) or !sizeof($itemMenu['permissao'])){
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

        if (isset($itemMenu['env'])) {
            if (!testEnv($itemMenu['env'])){$print = false;}
        }

        if( $print ){
            if(defined('LIB_BS') and LIB_BS == 4){
                if(isset($itemMenu['class'])){
                    $itemMenu['class'] .= '';
                }else if(is_array($itemMenu)){
                    $itemMenu['class'] = '';
                }else{
                    echo "</div></div></nav>";
                    echo "<h1>Erro Configuração de Menu</h1>";
                    debug($itemMenu);
                }
            }

            $titulo = (isset($itemMenu['titulo']) ? $itemMenu['titulo'] : '');
            $inside = "";
            $class = "";
            $icon = "";
            if(isset($itemMenu['class'])){   $class .= "class='".$itemMenu['class']."'"; }
            if(isset($itemMenu['onclick'])){ $inside .= "onclick=\"".$itemMenu['onclick']."\""; }
            if(isset($itemMenu['href'])){    $inside .= "href='".$itemMenu['href']."'"; }
            if(isset($itemMenu['target'])){  $inside .= "target='".$itemMenu['target']."'"; }
        if(isset($itemMenu['id'])){    $id   .= "id='".$itemMenu['id']."'"; }
            if(isset($itemMenu['icon'])){    $icon   .= "<i class='".$itemMenu['icon']."'></i> "; }

            return "<a $inside><div $id $class> $icon $titulo </div></a>";
        }else{
            return "<div></div>";
        }
    }

    function testEnv($value = '') {
        global $enviroment;

        if ($value == $enviroment) return true;
        if ($enviroment == 'hom' and $value == 'prod') return true;
        if ($enviroment == 'dev') return true;
        return false;

        /*
        prod show prod
        hom show hom & prod
        dev show all
        */
    }
if(defined("MENU_NOVO") and MENU_NOVO == 1){
        require_once (__DIR__.'/menu/_menu_novo.php');

    } else if(defined('LIB_BS')){
        if(file_exists(__DIR__.'/menu/_menu-bs'.LIB_BS.'.php')){
            require_once (__DIR__.'/menu/_menu-bs'.LIB_BS.'.php');

        }else{
            echo "Arquivo não encontrado: ".__DIR__.'/menu/_menu-bs'.LIB_BS.'.php';
            echo __FILE__.":".__LINE__;
            die;
        }
    }