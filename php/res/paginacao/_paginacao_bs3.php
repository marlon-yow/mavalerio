<?php
/*!
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.2 [2020-may-26]
* @copyleft GPLv3
*/
?>
<nav aria-label="Page navigation">
    <ul class="pagination">
        <?php
        $quant_pg = ceil($quantreg/$numreg);

        $link = getLinkGet('pg');


            $_end = "<span aria-hidden='true'>&laquo;</span></a></li>";
            if ( $pg > 1) {
                    echo "<li><a href='".$PHP_SELF."?pg=".($pg-1)."$link' aria-label='anterior' >$_end";
            } else {
                    echo "<li class='disabled'><a href='#' aria-label='anterior'>$_end";
            } ?>

        <li <?php if($pg==1){echo "class='active'";}?>><a href='<?php echo $PHP_SELF."?pg=1$link";?>'>1</a></li>

        <?php
            //conter quantidades absurdas de paginas.
            if($pg>10){
                echo "<li><a>...</a></li> ";
                $show_inicial = $pg-3;
                if($quant_pg>($pg+5)){
                    $show_final = $pg+5;
                    $last = 'mostre';
                }else{
                    $show_final = $quant_pg;
                }
            }else{
                $show_inicial = 2;
                if($quant_pg>10){
                    $show_final = 11;
                    $last = 'mostre';
                }else{
                    $show_final = $quant_pg;
                }
            }

            for($i=$show_inicial;$i<=$show_final;$i++) {
                echo "<li";
                if($pg == $i){ echo " class='active'";}
                echo "><a href='".$PHP_SELF."?pg=$i$link'>$i</a></li>";
            }

            if($last == 'mostre'){
                echo "<li><a>...</a></li><li><a href='".$PHP_SELF."?pg=$quant_pg$link'>$quant_pg</a></li>";
            }

            // Verifica se esta na ultima página, se nao estiver ele libera o link para próxima
            if (($pg+1) <= $quant_pg) {
                echo "<li><a href='".$PHP_SELF."?pg=".($pg+1)."$link' aria-label='próximo' >";
            } else {
                    echo "<li class='disabled'><a href='#' aria-label='próximo'>";
            }
            ?>
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
    </ul>
</div>

<?php
/*GET*/
if($_GET){ ?>
    <script type="text/javascript">
        get = <?php echo json_encode(utf8Encode($_GET));?>

        $.each(get,function(i,itm){
            $('#'+i).val(itm);
        })
    </script>
<?php }
