<?php
/*!
* @Autor MV https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.5 [2022-jan-04]
* @copyleft GPLv3
*/

    if(!$quantreg and $quantReg) $quantreg = $quantReg;

    if(defined('LIB_BS')){
        if(file_exists(__DIR__.'/paginacao/_paginacao_bs'.LIB_BS.'.php')){
            require_once (__DIR__.'/paginacao/_paginacao_bs'.LIB_BS.'.php');
        }else{
            echo "Arquivo não encontrado: ".__DIR__.'/paginacao/_paginacao_bs'.LIB_BS.'.php';
            echo __FILE__.":".__LINE__;
            die;
        }
    }

if(isset($_GET)){ ?>
    <script type="text/javascript">
        get = <?php echo json_encode($_GET);?>

        $.each(get,function(i,itm){
            $('#'+i).val(itm);
        })
    </script>
<?php }