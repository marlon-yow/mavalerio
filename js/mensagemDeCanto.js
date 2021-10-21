/*!
* @Autor Mavalerio https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.2 [2020-may-26]
* @copyleft GPLv3
*/
    /** Fun��o antiga que apresenta um aviso em tela (retrocompatibilidade para toast)
	*	@parameter mixed textoAApresentar
	*	@parameter mixed corDoAviso (verde/amarelo/vermelho)
	*/
    function launchWarning(data,level){
        var cor='';
        if(level == 'verde'){
            cor = toast.types.VERDE;
        }else if(level == 'vermelho'){
            cor = toast.types.VERMELHO;
        }else{
            cor = toast.types.AZUL;
        }
        mensagemDeCanto(data,cor,toast.sticky.APAGAR);
    }

    /** Funcao que apresenta mensagem de feedback na tela (requere jqueryToast)
	*	@parameter mixed
	*	@parameter mixed corDoAviso usar toast.types.{VERMELHO,AZUL,VERDE}
	*	@parameter mixed permanenciaDoAviso usar toast.sticky.{FICAR, APAGAR}
    */
    function mensagemDeCanto(message, type, keep){
        var options = {
            duration: 5001,
            sticky: keep,
            type: type
        };
        $.toast(message, options);
    }

    toast = new Object;
    toast.types = {VERMELHO:'danger',AZUL:'info',VERDE:'success'}
    toast.sticky = { FICAR:true, APAGAR:false}
    $(document).ready(function(){
		if($.toast){
			$.toast.config.align = 'center';
			$.toast.config.width = 400;
		}
    });
