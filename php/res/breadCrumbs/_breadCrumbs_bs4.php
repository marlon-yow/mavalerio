<?php
/*!
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.2 [2020-may-26]
* @copyleft GPLv3
*/
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <?php for($i=0;$i<$breadSize; $i++){
            echo  "<li class='breadcrumb-item'><a href='".$breadcrumbs[$i]['href']."'>".$breadcrumbs[$i]['titulo']."</a></li>";
        }?>
        <li class="breadcrumb-item active"><a href='<?php echo $breadcrumbs[$breadSize]['href'];?>'><?php echo $breadcrumbs[$breadSize]['titulo'];?></a></li>
    </ol>
</nav>
