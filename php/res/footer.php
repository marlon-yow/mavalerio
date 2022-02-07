<?php
/*!
* @Autor MV https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.2 [2020-may-26]
* @copyleft GPLv3
*/
?>
</div>
<div id="footer_content">
    <div id="footer_texto">
        <i class="fa fa-tachometer" style="font-size: inherit;"></i>  - Na ponta do mouse
        <br><?php echo $enviroment;?>
    </div>
</div>
<script>
var hora_atual_agora = DataEn.getHora();
if( hora_atual_agora.substr(0,2) != '<?php echo date('H');?>'){
    var txthor = 'Por alguma razão eu acho que são: <?php echo date('H:i');?> mas seu computador diz que são: '+hora_atual_agora;
    $('body').append(
        $('<div>')
        .append($('<button>').attr('type',"button").addClass("close").attr('data-dismiss',"alert").html('&times;'))
        .append(txthor).css('position','fixed').css('bottom','3px').css('right','32px').addClass('alert alert-danger')
    );
}
</script>
<?php
    if(isset($_SESSION[$appname]['mensagem'])){
        $_msg = $_SESSION[$appname]['mensagem'];
        if(is_array($_msg)){
            echo "<script>mensagemDeCanto('".$_msg[0]."',toast.types.".$_msg[1].",toast.sticky.".($_msg[2] ? $_msg[2] :'APAGAR').");</script>";
        }else{
            echo "<script>mensagemDeCanto('$_msg',toast.types.AZUL,toast.sticky.APAGAR);</script>";
        }
        unset($_SESSION[$appname]['mensagem']);
    }else if(isset($_SESSION['mensagem'])){
        $aviso1 = $_SESSION['mensagem'];
        echo "<script>mensagemDeCanto('$aviso1',toast.types.AZUL,toast.sticky.APAGAR);</script>";
        unset($_SESSION['mensagem']);
    }

	if(defined('UA_TAG')){ ?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo UA_TAG;?>"></script>
		<script>
  			window.dataLayer = window.dataLayer || [];
  			function gtag(){dataLayer.push(arguments);}
  			gtag('js', new Date());

  			gtag('config', '<?php echo UA_TAG;?>');
		</script>
	<?php } ?>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js',{scope: '/'})
            .then(function(reg){
                //console.log("registrou");
            }).catch(function(err) {
                console.log("Erro registro:", err);
            });
        }
    </script>
</body>
</html>
