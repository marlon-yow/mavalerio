<?php
/*!
* @Autor MV https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.5 [2022-jan-04]
* @copyleft GPLv3
*/

    if(!$quantreg and $quantReg) $quantreg = $quantReg;

    if(defined('LIB_BS') and LIB_BS == 4){ require_once (__DIR__.'/paginacao/_paginacao_bs4.php'); }
    if(defined('LIB_BS') and LIB_BS == 3){ require_once (__DIR__.'/paginacao/_paginacao_bs3.php'); }

if(isset($_GET)){ ?>
    <script type="text/javascript">
        get = <?php echo json_encode($FUN->utf8Encode($_GET));?>

        $.each(get,function(i,itm){
            $('#'+i).val(itm);
        })
    </script>
<?php }
