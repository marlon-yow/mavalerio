<?php
/*!
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.3 [2022-fev-04]
* @copyleft GPLv3
*/
?>
<nav class="navbar navbar-expand-md navbar-dark bg-dark no-print">
    <a class="navbar-brand" href="<?php echo $caminho;?>"><?php echo NOME_APRESENTACAO;?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
            <?php if(is_array($menu)) foreach($menu as $itemMenu){
                $link_base = criaLink($itemMenu);
                if($link_base){
                    ?>
                    <li class='nav-item'>
                        <?php echo $link_base; ?>
                    </li>
                    <?php
                }
            }
            if(file_exists(MAVALERIO_ALT_CONFIG_FOLDER.'_menu_extra.php')){ require_once(MAVALERIO_ALT_CONFIG_FOLDER.'_menu_extra.php');}
            ?>
        </ul>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <?php if(isset($_SESSION[$appname])){ ?>
                        <a class="nav-link" onclick="$('#userMenu').slideToggle();">
                            <i class="fa fa-user"></i>
                            <?php echo (isset($_SESSION['nome']) ? firstword($_SESSION['nome']) : '');?>
                            <span class="caret"></span>
                        </a>
                        <ul id="userMenu" class="list-group" style="display: none;top: 33px">
                            <li class="list-group-item">
                                <a href='<?php echo $caminho;?>login.php'>
                                    <i class="fa fa-power-off"></i>
                                    Sair
                                </a>
                            </li>
                        </ul>
                <?php } ?>
            </li>
        </ul>
        <span id='tmout'></span>
    </div>
</nav>

<?php /*********************************************************************************************/
    if(is_array($submenu)) foreach($submenu as $chave => $itens){ ?>
    	<ul id="<?php echo $chave;?>" class="dropdown-menu" role="menu" style="display: none;z-index: 1030;">
		<?php foreach($itens as $itemMenu){

			$link = criaLink($itemMenu);
	    		if($link){
	    		?>
					<li>
						<?php echo $link; ?>
					</li>
				<?php
			}
		}?>
		</ul>
	<?php }
