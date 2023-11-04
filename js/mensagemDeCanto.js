/*! ---UTF-8---
* @Autor Mavalerio https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.2 [2020-may-26]
* @copyleft GPLv3
*/
    /** Função antiga que apresenta um aviso em tela (retrocompatibilidade para toast)
    *    @parameter mixed textoAApresentar
    *    @parameter mixed corDoAviso (verde/amarelo/vermelho)
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
    *    @parameter mixed
    *    @parameter mixed corDoAviso usar toast.types.{VERMELHO,AZUL,VERDE,AMARELO}
    *    @parameter mixed permanenciaDoAviso usar toast.sticky.{FICAR, APAGAR}
    */
    function mensagemDeCanto(message, type, keep){
        var options = {
            duration: 5001,
            sticky: keep,
            type: type,
            text:  message,
        };

        if(keep){
            options['hideAfter'] = false;
        }

        switch (type) {
            case 'VERDE':
                //options['heading'] = 'Success';
                options['icon'] = 'success';
                break;
            case 'VERMELHO':
                //options['heading'] = 'Error';
                options['icon'] = 'error';
                break;
            case 'AZUL':
                //options['heading'] = 'Information';
                options['icon'] = 'info';
                break;
            case 'AMARELO':
                //options['heading'] = 'Warning';
                options['icon'] = 'warning';
                break;
        }


        $.toast(options);
    }

    toast = new Object;
    toast.types = {VERMELHO:'VERMELHO',AZUL:'AZUL',VERDE:'VERDE',AMARELO:'AMARELO'}
    toast.sticky = { FICAR:true, APAGAR:false}