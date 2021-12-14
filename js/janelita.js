/*! ---UTF-8---
* @Autor Mavalerio https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.1 [2021-dez-07]
* @copyleft GPLv3
*/
/*
janelita.iptSetId:'idEmpresa';
janelita.iptSetNome:'spnEmpName';
janelita.objDaBusca = Empresa;
janelita.itemId = 'IDEMPRESA';
janelita.itemCodigo = 'CODEMPRESA';
janelita.itemNome = 'NOMEEMPRESA';

janelita.janelaPesquisar()
*/
var janelita ={
    lista:{},
    iptSetId:'',
    iptSetNome:'',
    objDaBusca:null,
    itemId:'',
    itemCodigo:'',
    itemNome:'',
    tituloJanela: 'Busca',

    janelaPesquisar: function(){
        var recheio = $('<div>')
        .append($('<input>').prop('type','text')
            .attr('onkeyup','janelita.buscaRegex(this.value);')
            .prop('placeholder','Busca')
            .attr('onfocus','janelita.buscaRegex(this.value)')
        )
        .append($('<div>').prop('id','dvJanelitaLista'));

        janelita.objDaBusca.getAll(function(as){
            janelita.lista = as;
            setTimeout(function(){ janelita.buscaRegex(); }, 200);
        });

        divmodalStart(janelita.tituloJanela,recheio);
    },

    buscaRegex: function(val){
        $('#dvJanelitaLista').html('');
        $.each(janelita.lista,function(idx,itm){
            if( janelita.regexTrue(itm[janelita.itemNome],val) || janelita.regexTrue(itm[janelita.itemCodigo],val) ){
                $('#dvJanelitaLista').append( janelita.mkLink(idx,itm) );
            }
        });
    },

    regexTrue: function(haystak,needle){
        if(!haystak) return false;
        if(!needle == null || needle == '') return true;
        return (haystak.search(new RegExp(needle, "i")) > -1);
    },

    mkLink: function(idx,itm){
        return $("<div>")
            .append( $("<a>").attr('onclick','janelita.setItem('+idx+')').attr('href','#')
                .append(itm[janelita.itemCodigo]+' - '+itm[janelita.itemNome])
            );
    },

    preencherElemento: function(elem,valor){
        if(elem){
            var tipo = $('#'+elem).prop('nodeName');
            //console.log(elem,valor,tipo);
            switch(tipo){
                case 'INPUT': $('#'+elem).val(valor); break;
                default: $('#'+elem).html(valor); break;
            }
        }
    },

    setItem: function(idx) {
        var obj = janelita.lista[idx];
        janelita.preencherElemento(janelita.iptSetId,obj[janelita.itemId]);
        if(janelita.itemCodigo){
            janelita.preencherElemento(janelita.iptSetNome,obj[janelita.itemCodigo]+' - '+obj[janelita.itemNome]);
        }else{
            janelita.preencherElemento(janelita.iptSetNome,obj[janelita.itemNome]);
        }

        divmodalHide();
    },
}
