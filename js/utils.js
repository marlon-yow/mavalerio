/*! ---UTF-8---
* @Autor Mavalerio https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.4 [2021-dez-06]
* @copyleft GPLv3
*/

  /** Funcao para esconder/mostrar elementos - compatibilidade
    *    @parameter mixed nomeDoObjeto
    */
    function toggle(obj) {$('#'+obj).slideToggle();}

    /** Funcão que mostra e esconde menu dropdown
    *    @parameter html_object objetoReferencia (this)
    *    @parameter mixed idDoMenu
    */
    function showMenu(obj,divmenu){
        var rect = obj.getBoundingClientRect();
        $('#'+divmenu).css('top',rect.top+25).css('left',rect.left).slideToggle();
    }
    /** Funcao retrocompativel, usar showMenu
    */
    function gMS(obj,divmenu){ //manter retrocompatibilidade
        showMenu(obj,divmenu);
    }

    /** Função que redimensiona textarea para o conteúdo - NOVA
    *    @parameter htmlObject (this)
    */
    function sz(t) {
        var objT = $(t);
        var a = objT.val().split('\n');
        var newSz = a.length;
        $.each(a,function(i,itm){
            if(itm.length > 80){
                newSz += parseInt(itm.length / 80)+1;
            }
        });
        newSz =  18 * newSz;
        if (newSz > parseInt(objT.css('height')) ) objT.css('height',newSz);
    }

    /** Funcao que conta quantos caracteres ainda tem restantes no campo texto
    *   @parameter htmlObject (this)
    *   @returns int
    *   @required atributo maxlength no objeto de texto
    *   @sample <textarea name='' id='' onclick='sz(this)' maxlength="255" onkeyup='$("#tobslimit").html(testTextAreaSize(this))'></textarea>
    *    <span id='tobslimit'>0</span>
    */
    function testTextAreaSize(obj){
        o = $(obj);
        maxlength = o.attr('maxlength');
        atualLenth = o.val().length;
        if(atualLenth >= maxlength){
            alert('Acabou o espaço!')
        }
        return (maxlength-atualLenth);
    }

    /**
     * funcao que conta quantos caracteres tem e mostra quantos faltam para o maximo
     * @param  DOM (this) textare que vai ser contada
     * @param  int maxChar máximo de char
     * @param  mixed spanName nome do Span/DIV que vai ter o contador
     */
    function contadorCaracteres(edt,maxChar,spanName) {
        if(!maxChar) maxChar = 500;
        var qtd = $(edt).val().length;
        if(qtd > maxChar){
            $(edt).val($(edt).val().substr(0,maxChar));
        }
        $('#'+spanName).html( maxChar - qtd);
    }

    /** Funcao que limpa a tabela conservando a linha de cabeçalho
    *    @parameter mixed idTabela
    *   @parameter int quantidade de linhas a manter
    */
    function clearTable(tbName,num) {
        if($('#'+tbName+' thead')){
            $('#'+tbName+' tbody').html('');
        }else{

            var tt = $('#'+tbName+' tr');
            var x = '';
            if(!num) num = 1;
            for (var i = 0; i < num; i++) {
                x = x +'<tr>'+$(tt[i]).html()+'</tr>';
            };
            $('#'+tbName).html(x);

            if(num>1){$('input:text').setMask();}
        }
    }

    /** Função que impede a página de ser fechada quando algum campo do formulário é modificado
    * confirmação via browser api request
    */
    var canClose = true;
    function impedirFechamentoFormulario(){
        function myConfirmation() {
            if(!canClose) return 'Are you sure you want to quit?';
        }
        $('body').on('change','input, select, textarea', function(){ canClose = false; });
        window.onbeforeunload = myConfirmation;
    }
    /**/

    /** Função impede que formulário seja submetido quando bate enter
    */
    function trocaEnterPorTab(){
        $('body').on('keydown', 'input, select, textarea', function(e) {
                var self = $(this)
                , form = self.parents('form:eq(0)')
                , focusable
                , next
                , prev
                ;

                if (e.shiftKey) {
                    if (e.keyCode == 13) {
                        focusable =   form.find('input,a,select,button,textarea').filter(':visible');
                        prev = focusable.eq(focusable.index(this)-1);

                        if (prev.length) {
                            prev.focus();
                        } else {
                            form.submit();
                        }
                    }
                }
                else
                    if (e.keyCode == 13) {
                        focusable = form.find('input,a,select,button,textarea').filter(':visible');
                        next = focusable.eq(focusable.index(this)+1);
                        if (next.length) {
                            next.focus();
                        } else {
                          //  form.submit();
                        }
                        return false;
                    }
                });
    }
    /**/

    /** Função que bloqueia o formulario inteiro para somente leitura
    */
   function formularioSomenteLeitura(){
       //campos normais
       $('input').attr('readonly','readonly').css('border','0px none');
       //selects
       $('select').attr('disabled','true').css('border','0px none').css('-webkit-appearance','none').css('-moz-appearance','none').css('text-indent','1px').css('text-overflow','');
       //text areas
       $.each($('textarea'),function(i,itm){
           $(itm).after($('<xmp>').html($(itm).val())).hide();
       });
   }

    /**************/
    /* DOCUMENTOS */
    /**************/

    /**
    *    Função que valida CPF
    *     @parameter mixed cpf (com ou sem mascara)
    *    @returns boolean (true para valido)
    */
    function ValidarCPF(cpf){
        return cpf.isCPF();
    }

    function ValidarCNPJ(cnpj){
        return cnpj.isCNPJ();
    }

    function ValidarCPFCNPJ(doc){
        return ( doc.isCPF() || doc.isCNPJ() );
    }

        function validarNome(nome){
            if(!nome) return false;
            if(nome.length < 5) return false;
            if(nome.search(new RegExp(" ", "i")) == -1) return false;
            return true;
        }

    /***************************************
    * String.isCPF Function v1.0
    * Autor: Carlos R. L. Rodrigues
    Como Chamar a função: String.isCPF()
    Retorna: True ou False
    ***************************************/
    String.prototype.isCPF = function(){
        var dbg = false;
        var c = this;

        if(dbg) console.log('testar:',c);

        if(!c){
            if(dbg) console.log('vazio');
            return false;
        }
        if(typeof(c) != "string"){
            c = c.toString();
            if(dbg) console.log('nao e string',typeof(c),c);

            if(typeof(c) != "string"){
                if(dbg) console.log('nao e string',typeof(c),c);
                return false;
            }
        }

        var cpf = c.replace(/[^\d]+/g,'');
        if(cpf == ''){
            if(dbg) console.log('vazio2');
            return false;
        }
        // Elimina CPFs invalidos conhecidos
        if (cpf.length != 11){
            if(dbg) console.log('!11 char');
        }

        if(cpf == "00000000000" ||
        cpf == "11111111111" ||
        cpf == "22222222222" ||
        cpf == "33333333333" ||
        cpf == "44444444444" ||
        cpf == "55555555555" ||
        cpf == "66666666666" ||
        cpf == "77777777777" ||
        cpf == "88888888888" ||
        cpf == "99999999999"){
            if(dbg) console.log('!repeticao');
            return false;
        }
        // Valida 1o digito
        add = 0;
        for (i=0; i < 9; i ++){
            add += parseInt(cpf.charAt(i)) * (10 - i);
            if(dbg) console.log(add);
        }
        rev = 11 - (add % 11);
        if(dbg) console.log(rev,(add % 11));

        if (rev == 10 || rev == 11){
            rev = 0;
            if(dbg) console.log("rev set to 0");
        }
        if (rev != parseInt(cpf.charAt(9))){
            if(dbg) console.log(rev, "!=", parseInt(cpf.charAt(9)));
            return false;
        }
        // Valida 2o digito
        add = 0;
        for (i = 0; i < 10; i ++){
            add += parseInt(cpf.charAt(i)) * (11 - i);
            if(dbg) console.log(add);
        }
        rev = 11 - (add % 11);
        if(dbg) console.log(rev,(add % 11));
        if (rev == 10 || rev == 11){
            rev = 0;
            if(dbg) console.log("rev set to 0");
        }
        if (rev != parseInt(cpf.charAt(10))){
            if(dbg) console.log(rev, "!=", parseInt(cpf.charAt(10)));
            return false;
        }
        if(dbg) console.log('valido');
        return true;
    };

    /***************************************
    * String.isCNPJ Function v1.0
    * Autor: Carlos R. L. Rodrigues
    Como Chamar a função: String.isCNPJ()
    Retorna: True ou False
    ***************************************/
    String.prototype.isCNPJ = function(){
        var b = [6,5,4,3,2,9,8,7,6,5,4,3,2], c = this;
        if(!c) return false;
        if(typeof(c) != "string"){
            c = c.toString();
            if(typeof(c) != "string"){
                return false;
            }
        }
        if((c = c.replace(/[^\d]/g,"").split("")).length != 14) return false;
        for(var i = 0, n = 0; i < 12; n += c[i] * b[++i]);
        if(c[12] != (((n %= 11) < 2) ? 0 : 11 - n)) return false;
        for(var i = 0, n = 0; i <= 12; n += c[i] * b[i++]);
        if(c[13] != (((n %= 11) < 2) ? 0 : 11 - n)) return false;
        if(parseInt(c.join('')) <= 4 ) return false;
        return true;
    };

    /**************/
    /*** TEXTO **/
    /**************/

    /** Funcao que retorna uma string cortada até a primeira occ de
     *  um caracter expecificado ou espaço se não espacificar
     * @param mixed
     * @paramOptional char
     * @return mixed
     */
    function firstWord(txt,chr){
        if(!txt) return '';
        if(!chr) chr = ' ';
        var x = txt.search(new RegExp(chr, "i"));
        if(x < 0) return txt;
        return txt.substr(0,x);
    }

    /**************/
    /*** NUMEROS **/
    /**************/

    /** Função que corta os numeros finais de um float
    *     @parameter mixed floatACortar
    *     @parameter int quantidadeDeCasas
    *     @returns mixed numeroTruncado
    */
    function truncaDeVerdade(flutuante, casas){
        var s_a2 = flutuante.toString();
        var arr_a2 = s_a2.split('.');
        if(!arr_a2[1]){arr_a2[1] = '0';}
        s_a2 = parseFloat(arr_a2[0]).toString()+'.'+arr_a2[1].substr(0,casas);
        var a2 = parseFloat(s_a2);
        return a2;
    }

    /** Funcao preenche com zeros à esquerda o numero
    *    @parameter mixed numero
    *    @parameter int casas
    *    @returns mixed
    */
    function pad(number, length) {
        var str = '' + number;
        while (str.length < length) {
            str = '0' + str;
        }
        return str;
    }

    /** Função que evita que o usuário digite vírgula em campo numérico
    *   @parameter this (usar no onkeyup)
    */
    function preventComa(obj){
        var cuRsoR = obj.selectionStart;
        obj.value = obj.value.replace(',','.');
        obj.setSelectionRange(cuRsoR,cuRsoR);
    }

    /** Função que apresenta valores monetários na máscara correta
    */
    function moneyMask(a){
        return "R$ "+ a.toFixed(2);
    }

    /** Função que remove caracteres não numéricos da string
    */
    function somenteNumeros(a){
        var b = a.replace(/[^\d]/g,'');
        return b;
    }

    /**************/
    /*** OUTROS **/
    /**************/

    /** Funcao remove caracter não Letras da string
    */
    function somenteLetras(a){
        if(!a) return "";
        var b = a.replace(/[^a-zA-Z]+/g,'');
        return b;
    }

    function somenteLetrasENumeros(a){
        if(!a) return "";
        var b = a.replace(/[^a-zA-Z0-9]+/g,'');
        return b;
    }

    function somenteLetrasNumerosEEspacos(a){
        return a.replace(/[^a-z0-9][:space:]/gi,'');
    }

    function copiaObj(obj){
        return jQuery.extend(true, {}, obj);
    }

    /* funçao de ping para keepalive*/
    function tmout(){
        if(!$('#footerPing').length){
            $('#footer_texto').append(
                $('<div>').attr('id','footerPing').addClass('no-print')
            );
        }

        $.post('/ping.php',function(data) {
            $('#footerPing').html('')
            .append(data[0])
            .append(' ')
            .append(data[3]);
            setTimeout(function() {tmout();}, 30000);
        },'json');
    }

/* Inicialização de bibliotecas de másca de campo e desativa cache do jqery*/
(function($){
    $(function(){
        if($('input:text').setMask) $('input:text').setMask();
        $.ajaxSetup({
            cache: false,
            headers: { "cache-control": "no-cache" },
        });
    });
})(jQuery);
