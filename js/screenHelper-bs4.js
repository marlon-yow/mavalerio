/*! ---UTF-8---
* @Autor Mavalerio https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.5 [2021-dez-06]
* @copyleft GPLv3
*/

    divmodal = $('<div tabindex="-1" role="dialog">').addClass('modal fade').attr('id','divmodal');
    function genericModal(titulo,recheio,footer){
        divmodal.html('');

        divmodal
        .append(
            $('<div role="document">').addClass('modal-dialog')
            .append(
                $('<div>').addClass('modal-content')
                .append(
                    $('<div>').addClass('modal-header')
                    .append(
                        $('<h3>').html(titulo)
                    )
                    .append(
                        $('<button>').addClass('close').attr('data-dismiss',"modal").attr('aria-hidden',"true").html("&times;")
                    )
                )
                .append(
                    $('<div>').addClass('modal-body').html(recheio)
                )
                .append(
                    $('<div>').addClass('modal-footer').append(footer)
                )
            )
        )
        .modal();
    }

    /**
    *    Funcao que abre uma janela modal na tela com botão de OK
    *    @parameter mixed titulo
    *    @parameter mixed oQueVaiDentroDaJanela texto/html/objJquery
    */
    function geraAvisoOKModal(titulo,recheio){
        var _footer = $('<button>').attr('data-dismiss',"modal").addClass('btn btn-primary').html('OK');
        genericModal(titulo,recheio,_footer);
    }

    /**
    *    Função que abre uma tela modal com a confirmação de exclusão de um item
    *    @parameter mixed idDoItem
    *    @parameter mixed textoNomeDoElemento
    *    @parameter mixed elemento
    */
    function excluir(id,txt,obj){
        var _titulo = "Aviso de Exclusão";

        var _recheio = $('<div>').addClass('modal-body').html("<label>Tem certeza que deseja excluir "+txt+"?<br>"+
            "Essa operação não pode ser desfeita.</label>");

        var _footer = $('<div>')
            .append( $('<button>').attr('data-dismiss',"modal").addClass('btn btn-primary').html('Não') )
            .append(' ')
            .append($('<a/>').attr('href', obj+'_excluir.php?id='+id).addClass('btn btn-danger').html("Sim"));

        genericModal(_titulo,_recheio,_footer);
    }

    /**
    *    Função que abre janela modal com 1 e 2 botões
    *     @parameter mixed titulo
    *     @parameter mixed conteudoDaJanela texto/html/objJquery
    *     @parameter mixed textoDoBotao (opcional)
    *     @parameter mixed funcaoDoBotao (opcional)
    *     @parameter mixed mensagemDeRodape (opcional)
    */
    function divmodalStart(titulo,recheio,buttonText,buttonOnclick,footerMessage){

        var btn = '';
        if(buttonText && buttonOnclick){
            btn = $('<div>')
            .append('<a>').attr('onClick',buttonOnclick).addClass('btn btn-primary').html(buttonText)
        }

        var rodape = $('<div>')
            .append( $('<span>').attr('id','divfootermodalmessage').append(footerMessage) )
            .append( $('<button>').attr('data-dismiss',"modal").addClass('btn btn-defult').html('Fechar') )
            .append( btn );

        genericModal(titulo,recheio,rodape);
    }

    /**
    *    Função que fecha janela modal com 1 e 2 botões
    */
    function divmodalHide(){
        $('#divmodal').modal('hide');
        $('.modal').modal('hide');
    }

    /**
    *    Função que insere texto de rodapé na janela modal com 1 e 2 botões (requere res/modal.php)
    *     @parameter mixed texto/html/objJquery
    */
    function divmodalMessage(txt){
        $('#divfootermodalmessage').html('').append(txt);
    }

    /**
    *    Função que cria paginação
    *     @parameter int paginaAtual
    *     @parameter int quantidadeTotalDeRegistros
    *     @parameter int quantidadeDeRegistrosPorPagina
    *     @parameter mixed funcaoDeTrocaDePagina
    *     @returns jQeryObject
    */
    function paginacaoJS(pagina,quantreg,registrosPorPagina,funcao){
        if(!pagina){pagina = 1;}
        var quantasPaginas = Math.ceil(quantreg / registrosPorPagina);
        var paginacao = $('<nav aria-label="Page navigation">');
        var ul = $('<ul>').addClass('pagination');
        for(i=1; i<= quantasPaginas;i++){
            if( (i <= 5) || ((i >= pagina-5) && (i <= pagina+5)) || (i >= (quantasPaginas-5)) ){
                var a = $("<a>").addClass('page-link').html(i).attr('onClick',funcao+"("+i+")");
                if(i == pagina){
                    a.css('color','#ff0000');
                }
                ul.append( $('<li>').addClass('page-item').append(a) );
            }
        }
        paginacao.append($('<hr>'));
        paginacao.append(ul);
        paginacao.append($('<br>'));
        paginacao.append("<i style='color:#aaaaaa'>"+quantreg+" registros</i>");
        return paginacao;
    }

    /**
    *    Função que testa o cpf do campo e pinta de verde ou vermelho
    *     @parameter DOM objeto campo com o CPF
    */
    function validarCampoCPF(campo){
        if(ValidarCPF(campo.value)){
            $(campo).addClass('success').removeClass('danger');
        }else{
            $(campo).addClass('danger').removeClass('success');
        }
    }
    
    /**
    * Função que testa o cnpj do campo e pinta de verde ou vermelho
    * @parameter DOM objeto campo com o CNPJ
    */
    function validarCampoCNPJ(campo){
        if(ValidarCNPJ(campo.value)){
            $(campo).addClass('success').removeClass('danger');
        }else{
            $(campo).addClass('danger').removeClass('success');
        }
    }
