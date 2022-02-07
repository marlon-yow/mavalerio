<?php
/*!
* @Autor MV https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.5 [2022-fev-04]
* @copyleft GPLv3
*/
$libCacheUpdate = date('dmH');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
    <title><?php echo (defined('NOME_APRESENTACAO') ? NOME_APRESENTACAO : $appname);?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">

    <link rel="shortcut icon" type="image/png" href="<?php echo $caminho;?>favicon.png">
    <meta http-equiv="Cache-control" content="public">
    <link rel="manifest" href="<?php echo $caminho;?>/manifest.json">

    <link rel="shortcut icon" href="<?php echo $caminho;?>favicon.png">
    <link rel="apple-touch-icon image_src" href="<?php echo $caminho;?>favicon.png">
    <meta property="og:image" itemprop="image primaryImageOfPage" content="<?php echo $caminho;?>favicon.png">

    <!-- JQuery -->
    <?php if(defined('LIB_JQUERY') and LIB_JQUERY == 3){ ?><script src='<?php echo $caminho;?>/vendor/jquery/js/jquery-3.4.1.min.js' type='text/javascript'></script><?php } ?>

    <!-- bootstrap 3 ou 4 -->
    <?php if(defined('LIB_BS') and LIB_BS == 3){ ?>
        <link href="<?php echo $caminho;?>/vendor/mavalerio/css/default1-bs3.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $caminho;?>/vendor/bootstrap-3/css/bootstrap.min.css" rel="stylesheet">
        <script src='<?php echo $caminho;?>/vendor/bootstrap-3/js/bootstrap.min.js' type='text/javascript'></script>
        <script src='<?php echo $caminho;?>/vendor/mavalerio/js/screenHelper-bs3.js?t=<?php echo $libCacheUpdate;?>' charset="UTF-8" type='text/javascript'></script>
    <?php } ?>

    <?php if(defined('LIB_BS') and LIB_BS == 4){ ?>
        <link href="<?php echo $caminho;?>/vendor/mavalerio/css/default1-bs4.css?t=<?php echo $libCacheUpdate;?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo $caminho;?>/vendor/bootstrap-4.5.3/css/bootstrap.min.css" rel="stylesheet">
        <script src='<?php echo $caminho;?>/vendor/bootstrap-4.5.3/js/bootstrap.min.js' type='text/javascript'></script>
        <script src='<?php echo $caminho;?>/vendor/mavalerio/js/screenHelper-bs4.js?t=<?php echo $libCacheUpdate;?>' charset="UTF-8" type='text/javascript'></script>
    <?php } ?>

    <!-- TOAST -->
    <?php if(defined('LIB_TOAST') and LIB_TOAST){ ?>
        <link rel="stylesheet" href="<?php echo $caminho;?>/vendor/jquery/jquery.toast/jquery.toast.min.css" type="text/css">
        <script src='<?php echo $caminho;?>/vendor/jquery/jquery.toast/jquery.toast.min.js' type='text/javascript'></script>
        <script src='<?php echo $caminho;?>/vendor/mavalerio/js/mensagemDeCanto.js?t=<?php echo $libCacheUpdate;?>' type='text/javascript'></script>
    <?php } ?>

    <!-- Javascript -->
    <?php if(defined('LIB_MEIOMASK') and  LIB_MEIOMASK ){ ?><script src='<?php echo $caminho;?>/vendor/jquery/js/meiomask.js' type='text/javascript'></script><?php } ?>
    <?php if(defined('LIB_DATAEN') and  LIB_DATAEN ){ ?><script src='<?php echo $caminho;?>/vendor/mavalerio/js/DataEn.js?t=<?php echo $libCacheUpdate;?>' type='text/javascript' charset="utf-8"></script><?php } ?>
    <?php if(defined('LIB_UTILS') and  LIB_UTILS ){ ?>
        <script src='<?php echo $caminho;?>/vendor/mavalerio/js/utils.js?t=<?php echo $libCacheUpdate;?>' type='text/javascript' charset="iso-8859-1"></script>
        <script> var caminho = '<?php echo $caminho;?>';</script>
        <script> tmout(); </script>
    <?php } ?>
    <?php if(defined('JS_APP') and file_exists(PATH_SIS."/js/".JS_APP)){ ?><script src='<?php echo JS.JS_APP ?>?t=<?php echo $libCacheUpdate;?>' type='text/javascript' charset="utf-8"></script><?php } ?>
    <?php if(defined('RES') and file_exists(MAVALERIO_ALT_CONFIG_FOLDER."_preHead_extras.php")){ require (MAVALERIO_ALT_CONFIG_FOLDER."_preHead_extras.php"); } ?>

    <!-- CSS -->
    <?php if(defined('LIB_FA') and  LIB_FA == 5 ){ ?>
        <link href="<?php echo $caminho;?>/vendor/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet">
        <script src='<?php echo $caminho;?>/vendor/fontawesome-free-5.15.4-web/js/all.js' type='text/javascript'></script>
    <?php } ?>
    <?php if(defined('LIB_FA') and  LIB_FA == 4 ){ ?><link href="<?php echo $caminho;?>/vendor/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet"><?php } ?>
    <?php if(defined('LIB_CSS_NO_PRINT') and  LIB_CSS_NO_PRINT ){ ?><link href="<?php echo $caminho;?>/vendor/mavalerio/css/noPrint.css" rel="stylesheet"><?php } ?>
    <?php if(defined('LIB_CSS_MOBILE') and  LIB_CSS_MOBILE ){ ?><link href="<?php echo $caminho;?>/vendor/mavalerio/css/mobile.css" rel="stylesheet"><?php } ?>
    <!-- CSS DO SISTEMA -->
    <?php if(defined('CSS_APP') and file_exists(PATH_SIS."/css/".CSS_APP)){ ?><link href='<?php echo CSS.CSS_APP ?>?t=<?php echo $libCacheUpdate;?>' rel="stylesheet" type="text/css" /><?php } ?>
</head>
