/*!
* @Autor Mavalerio https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.2 [2020-may-26]
* @copyleft GPLv3
*/
    /**
	*	Funcao que abre uma janela modal na tela com bot�o de OK
	*	@parameter mixed titulo
	*	@parameter mixed oQueVaiDentroDaJanela texto/html/objJquery
	*/
	function geraAvisoOKModal(titulo,recheio){
		var container = $('<div>').addClass('modal').attr('id','avisoOKModal');
		var cabecalho = $('<div>').addClass('modal-header').
			append(
					$('<button>').addClass('close').attr('data-dismiss',"modal").attr('aria-hidden',"true").html("&times;")
				).
			append(
					$('<h3>').html(titulo)
				);
		var corpo = $('<div>').addClass('modal-body').html(recheio);
		var rodape = $('<div>').addClass('modal-footer').append(
				$('<button>').attr('data-dismiss',"modal").addClass('btn btn-primary').html('OK')
			);
		container.append(cabecalho).append(corpo).append(rodape).modal();
	}

	/**
	*	Fun��o que abre uma tela modal com a confirma��o de exclus�o de um item
	*	@parameter mixed idDoItem
	*	@parameter mixed textoNomeDoElemento
	*	@parameter mixed elemento
	*/
    function excluir(id,txt,obj){
        var container = $('<div>').addClass('modal').attr('id','avisoOKModal');

        var cabecalho = $('<div>').addClass('modal-header')
        .append(
            $('<button>').addClass('close').attr('data-dismiss',"modal").attr('aria-hidden',"true").html("&times;")
        )
        .append(
            $('<h3>').html("Aviso de Exclus�o")
        );

        var corpo = $('<div>').addClass('modal-body').html("<label>Tem certeza que deseja excluir "+txt+"?<br>"+
            "Essa opera��o n�o pode ser desfeita.</label>");


        if(obj){
            var lnk = obj+'_excluir.php?id='+id;
        }else{
            var lnk = id;
        }

        var rodape = $('<div>').addClass('modal-footer')
        .append(
            $('<button>').attr('data-dismiss',"modal").addClass('btn btn-primary').html('N�o')
        )
        .append($('<a/>').attr('href', lnk).addClass('btn btn-primary').html("Sim"));

        container.append(cabecalho).append(corpo).append(rodape);
        container.modal();
    }

    /**
    *	Fun��o que abre janela modal com 1 e 2 bot�es
    * 	@parameter mixed titulo
    * 	@parameter mixed conteudoDaJanela texto/html/objJquery
    * 	@parameter mixed textoDoBotao (opcional)
    * 	@parameter mixed funcaoDoBotao (opcional)
    * 	@parameter mixed mensagemDeRodape (opcional)
    */
    var divmodal = $('<div>').addClass('modal').attr('id','divmodal');
    function divmodalStart(titulo,recheio,buttonText,buttonOnclick,footerMessage){
        var cabecalho = $('<div>').addClass('modal-header').
            append(
                    $('<button>').addClass('close').attr('data-dismiss',"modal").attr('aria-hidden',"true").html("&times;")
                ).
            append(
                    $('<h3>').html(titulo)
                );
        var corpo = $('<div>').addClass('modal-body').html(recheio);

        var btn = '';
        if(buttonText && buttonOnclick){
            btn = $('<div>')
            .append('<a>').attr('onClick',buttonOnclick).addClass('btn btn-primary').html(buttonText)
        }

        var rodape = $('<div>').addClass('modal-footer')
            .append( $('<span>').attr('id','divfootermodalmessage').append(footerMessage) )
            .append( $('<button>').attr('data-dismiss',"modal").addClass('btn btn-defult').html('Fechar') )
            .append( btn );

        divmodal.html('').append(cabecalho).append(corpo).append(rodape).modal();
	}

	/**
    *	Fun��o que fecha janela modal com 1 e 2 bot�es
    */
	function divmodalHide(){
        $('#divmodal').modal('hide');
		$('.modal').modal('hide');
	}

	/**
    *	Fun��o que insere texto de rodap� na janela modal com 1 e 2 bot�es (requere res/modal.php)
    * 	@parameter mixed texto/html/objJquery
    */
	function divmodalMessage(txt){
		$('#divfootermodalmessage').html('').append(txt);
	}

    /**
    *	Fun��o que cria pagina��o
    * 	@parameter int paginaAtual
    * 	@parameter int quantidadeTotalDeRegistros
    * 	@parameter int quantidadeDeRegistrosPorPagina
    * 	@parameter mixed funcaoDeTrocaDePagina
    * 	@returns jQeryObject
    */
	function paginacaoJS(pagina,quantreg,registrosPorPagina,funcao){
		if(!pagina){pagina = 1;}
		var quantasPaginas = Math.ceil(quantreg / registrosPorPagina);
		var paginacao = $('<div/>').addClass('pagination pagination-mini');
		var ul = $('<ul/>');
		for(i=1; i<= quantasPaginas;i++){
			if( (i <= 5) || ((i >= pagina-5) && (i <= pagina+5)) || (i >= (quantasPaginas-5)) ){
				var a = $("<a/>").html(i).attr('onClick',funcao+"("+i+")");
				if(i == pagina){
					a.css('color','#ff0000');
				}
				ul.append( $('<li/>').append(a) );
			}
		}
		paginacao.append($('<hr/>'));
		paginacao.append(ul);
		paginacao.append($('<br/>'));
		paginacao.append("<i style='color:#aaaaaa'>"+quantreg+" registros</i>");
		return paginacao;
	}

	(function($){
        $(function(){
        $('body').css('padding-top',$('.navbar-fixed-top').css('height')); //layout-fix
        });
    })(jQuery);
