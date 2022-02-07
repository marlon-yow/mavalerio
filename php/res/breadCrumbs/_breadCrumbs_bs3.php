<?php
/*!
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.2 [2020-may-26]
* @copyleft GPLv3
*/
?>
<ol class="breadcrumb">
    <?php for($i=0;$i<$breadSize; $i++){
        echo  "<li><a href='".$breadcrumbs[$i]['href']."'>".$breadcrumbs[$i]['titulo']."</a></li>";
    }?>
    <li><a class='active' href='<?php echo $breadcrumbs[$breadSize]['href'];?>'><?php echo $breadcrumbs[$breadSize]['titulo'];?></a></li>
</ol>
