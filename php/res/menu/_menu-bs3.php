<?php
/*!
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.3 [2022-fev-04]
* @copyleft GPLv3
*/
?>
<nav class="navbar navbar-default navbar-fixed-top no-print">
	<div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed pull-left" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
		<a class="navbar-brand" href="<?php echo $caminho;?>"><i class='fa fa-urbs'></i> <?php echo NOME_APRESENTACAO;?></a>
	</div>
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<span id='tmout'></span>
		<ul class="nav navbar-nav">
			<?php if(is_array($menu)) foreach($menu as $itemMenu){

			    	$link_base = criaLink($itemMenu);
			    	if($link_base){
			    	?>
						<li class='nav-item'>
							<div class="btn-group">
								<?php echo $link_base; ?>
							</div>
						</li>
					<?php
					}
				}

				if(file_exists(MAVALERIO_ALT_CONFIG_FOLDER.'_menu_extra.php')){ require_once(MAVALERIO_ALT_CONFIG_FOLDER.'_menu_extra.php');}
			?>
		</ul>
	</div>
        <?php if($_SESSION[$appname]){ ?>
	    <div class="btn-group pull-right" style='position: absolute;    right: 0;    top: 0px;'>
		<button class="btn btn-default" onclick="$('#userMenu').slideToggle();">
			<i class="fa fa-user"></i>
			<?php echo firstword($_SESSION['nome']);?>
			<span class="caret"></span>
		</button>
		<ul id="userMenu" class="dropdown-menu" role="menu" aria-labelledby="drop2" style="display: none;top: 33px">
                    <li class="divider"></li>
                    <li><a href='<?php echo $caminho;?>login.php'><i class="fa fa-power-off"></i> Sair</a></li>
		</ul>
            </div>
        <?php } ?>
</nav>
<span style="padding-top: 45px;"></span>

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
