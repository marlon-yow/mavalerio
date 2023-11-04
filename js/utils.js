/*! ---UTF-8---
* @Autor Mavalerio https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.6 [2023-jan-26]
* @copyleft GPLv3
*/
Utils = {
    dbg : false,

    Documentos : {
        /*
        * @author Brownie Marsalla Kawak
        */

        /**
        *    Função que valida CNPJ
        *    @parameter mixed cnpj (com ou sem mascara)
        *    @returns boolean (true para valido)
        */
        isCNPJ: (c) => {

            Utils.dbg && console.log('Utils.Documentos.isCNPJ -> testar:', c);

            if (!c){
                Utils.dbg && console.log('vazio');
                return false;
            }

            if (typeof (c) != "string") {
                Utils.dbg && console.log('nao e string, transformar', typeof (c), c);
                c = c.toString();

                if (typeof (c) != "string") {
                    Utils.dbg && console.log('nao e string, nao consegui transformar', typeof (c), c);
                    return false;
                }
            }

            var cnpj = c.replace(/[^\d]/g, "");
            if(cnpj === ''){
                Utils.dbg && console.log('vazio2');
                return false;
            }

            if (cnpj.split("").length !== 14){
                Utils.dbg && console.log('tamanho errado !14 char');
                return false;
            }
            if(/0{14}/.test(cnpj)){
                Utils.dbg && console.log('zeros');
                return false
            }
            //if (preg_match('/(\d)\1{13}/', $cnpj)) return false;

            c = cnpj.split("");

            Utils.dbg && console.log('teste primeiro dígito')

            var n = 0;
            var oldN = 0;
            var b = [6, 7, 8, 9, 2, 3, 4, 5, 6, 7, 8, 9];
            for (var i = 0; i < 12;i++){
                var mult = c[i] * b[i];
                Utils.dbg && console.log("i=",i,"mult = c[i] * b[i] ->",mult," = ",c[i]," * ",b[i]);
                oldN = n;
                n += mult;
                Utils.dbg && console.log(n," = ",oldN," + ",mult);
            }
            var resto1 = n % 11;
            Utils.dbg && console.log('resto1 = n % 11 ', resto1,' = ',n,' % 11');
            if(resto1 < 2){
                resto1 = 0;
                Utils.dbg && console.log('resto1 vira 0');
            }
            Utils.dbg && console.log(c[12],'==?',resto1);
            if(parseInt(c[12]) !== resto1){
                Utils.dbg && console.log('diferente');
                return false;
            }
            ///////

            Utils.dbg && console.log('teste segundo dígito')
            var n = 0;
            var oldN = 0;
            //aqui tem que trocar o B e colocar um 5 na frente
            var b = [5, 6, 7, 8, 9, 2, 3, 4, 5, 6, 7, 8, 9];
            for (var i = 0; i < 13;i++){
                var mult = c[i] * b[i];
                Utils.dbg && console.log("i=",i,"mult = c[i] * b[i] ->",mult," = ",c[i]," * ",b[i]);
                oldN = n;
                n += mult;
                Utils.dbg && console.log(n," = ",oldN," + ",mult);
            }
            var resto2 = n % 11;
            Utils.dbg && console.log('resto2 = n % 11 ', resto2,' = ',n,' % 11');

            if(resto2 >= 10){
                resto2 = 0;
                Utils.dbg && console.log('resto2 vira 0');
            }
            Utils.dbg && console.log(c[13],'==?',resto2);
            if(parseInt(c[13]) !== resto2){
                Utils.dbg && console.log('diferente');
                return false;
            }

            Utils.dbg && console.log('uma ultima verificacaozinha');
            if (parseInt(c.join('')) <= 4) return false;

            return true;
        },

        /**
        *    Função que valida CPF
        *    @parameter mixed cpf (com ou sem mascara)
        *    @returns boolean (true para valido)
        */
        isCPF: (c) => {

            Utils.dbg && console.log('Utils.Documentos.isCPF -> testar:', c);

            if (!c) {
                Utils.dbg && console.log("vazio");
                return false;
            }
            if (typeof c != "string") {
                c = c.toString();
                Utils.dbg && console.log("nao e string", typeof c, c);

                if (typeof c != "string") {
                    Utils.dbg && console.log("nao e string", typeof c, c);
                    return false;
                }
            }

            var cpf = c.replace(/[^\d]+/g, "");
            if (cpf === "") {
                Utils.dbg && console.log("vazio2");
                return false;
            }
            // Elimina CPFs invalidos conhecidos
            if (cpf.length !== 11) {
                Utils.dbg && console.log("!11 char");
            }

            if (
                cpf === "00000000000" ||
                cpf === "11111111111" ||
                cpf === "22222222222" ||
                cpf === "33333333333" ||
                cpf === "44444444444" ||
                cpf === "55555555555" ||
                cpf === "66666666666" ||
                cpf === "77777777777" ||
                cpf === "88888888888" ||
                cpf === "99999999999"
            ) {
                Utils.dbg && console.log("!repeticao");
                return false;
            }
            // Valida 1o digito
            var add = 0;
            for (let i = 0; i < 9; i++) {
                add += parseInt(cpf.charAt(i)) * (10 - i);
                Utils.dbg && console.log(add);
            }
            var rev = 11 - (add % 11);
            Utils.dbg && console.log(rev, add % 11);

            if (rev === 10 || rev === 11) {
                rev = 0;
                Utils.dbg && console.log("rev set to 0");
            }
            if (rev !== parseInt(cpf.charAt(9))) {
                Utils.dbg && console.log(rev, "!=", parseInt(cpf.charAt(9)));
                return false;
            }
            // Valida 2o digito
            add = 0;
            for (let i = 0; i < 10; i++) {
                add += parseInt(cpf.charAt(i)) * (11 - i);
                Utils.dbg && console.log(add);
            }
            rev = 11 - (add % 11);
            Utils.dbg && console.log(rev, add % 11);
            if (rev === 10 || rev === 11) {
                rev = 0;
                Utils.dbg && console.log("rev set to 0");
            }
            if (rev !== parseInt(cpf.charAt(10))) {
                Utils.dbg && console.log(rev, "!=", parseInt(cpf.charAt(10)));
                return false;
            }
            Utils.dbg && console.log("valido");
            return true;
        },

        isCPFCNPJ: (c) => {
            if(Utils.Documentos.isCNPJ(c)) return true;
            if(Utils.Documentos.isCPF(c)) return true;
            return false;
        },

        validarChaveNotaFiscal: (c) => {
            if (!c) {
                return false;
            }
            
            let chaveSemDigito = c.substring(0, 43);
            let soma = 0;
            let peso = 2;
          
            for (let i = 42; i >= 0; i--) {
                let digito = parseInt(chaveSemDigito.substring(i, i + 1));
                soma += digito * peso;
                peso = peso === 9 ? 2 : peso + 1;
            }
          
            //console.log(`A soma deu: ${soma}`);
            let digitoVerificador = soma % 11 < 2 ? 0 : 11 - soma % 11;
            //console.log(`O digito ver. calculado: ${digitoVerificador}`);
            let cDV = parseInt(c.substring(43, 44));
            //console.log(`O digito ver. real: ${cDV}`);
            //
            if (digitoVerificador == cDV) {
                return true;
            } else {
                return false;
            }
        }
    }
};

/** Funcão que mostra e esconde menu dropdown
*    @parameter html_object objetoReferencia (this)
*    @parameter mixed idDoMenu
*/
function gMS(obj,divmenu){ showMenu(obj,divmenu);}/** Funcao retrocompativel, usar showMenu*/
function showMenu(obj,divmenu){
    var rect = obj.getBoundingClientRect();
    $('#'+divmenu).css('top',rect.top+25).css('left',rect.left).slideToggle();

    var im = $(obj);
    if(im.hasClass('active')){
        im.removeClass('active');
    }else{
        im.addClass('active');
    }
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
function contadorCaracteres(edt, maxChar, spanName) {
    if (!maxChar) maxChar = 500;
    var qtd = $(edt).val().length;
    if (qtd > maxChar) {
        $(edt).val($(edt).val().substr(0, maxChar));
    }
    $('#' + spanName).html(maxChar - qtd);
}

/** Funcao que limpa a tabela conservando a linha de cabeçalho
*    @parameter mixed idTabela
*   @parameter int quantidade de linhas a manter
*/
function clearTable(tbName, num) {
    if ($('#' + tbName + ' thead')) {
        $('#' + tbName + ' tbody').html('');
    } else {
        var tt = $('#' + tbName + ' tr');
        var x = '';
        if (!num) num = 1;
        for (var i = 0; i < num; i++) {
            x = x + '<tr>' + $(tt[i]).html() + '</tr>';
        };
        $('#' + tbName).html(x);

        if (num > 1) { $('input:text').setMask(); }
    }
}

/** Função que impede a página de ser fechada quando algum campo do formulário é modificado
* confirmação via browser api request
*/
var canClose = true;
function impedirFechamentoFormulario() {
    function myConfirmation() {
        if (!canClose) return 'Are you sure you want to quit?';
    }
    $('body').on('change', 'input, select, textarea', function () { canClose = false; });
    window.onbeforeunload = myConfirmation;
}
/**/

/** Função impede que formulário seja submetido quando bate enter
*/
function trocaEnterPorTab() {
    $('body').on('keydown', 'input, select, textarea', function (e) {
        var self = $(this), form = self.parents('form:eq(0)'), focusable, next, prev;

        if (e.shiftKey) {
            if (e.keyCode == 13) {
                focusable = form.find('input,a,select,button,textarea').filter(':visible');
                prev = focusable.eq(focusable.index(this) - 1);
                if (prev.length) {
                    prev.focus();
                } else {
                    form.submit();
                }
            }
        }else if (e.keyCode == 13) {
            focusable = form.find('input,a,select,button,textarea').filter(':visible');
            next = focusable.eq(focusable.index(this) + 1);
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
function formularioSomenteLeitura() {
    //campos normais
    $('input').attr('readonly', 'readonly').css('border', '0px none');
    //selects
    $('select').attr('disabled', 'true').css('border', '0px none').css('-webkit-appearance', 'none').css('-moz-appearance', 'none').css('text-indent', '1px').css('text-overflow', '');
    //text areas
    $.each($('textarea'), function (i, itm) {
        $(itm).after($('<xmp>').html($(itm).val())).hide();
    });
}

/**************/
/* DOCUMENTOS */
/**************/

function ValidarCPF(cpf) { return Utils.Documentos.isCPF(cpf); }
function ValidarCNPJ(cnpj) { return Utils.Documentos.isCNPJ(cnpj); }
function ValidarCPFCNPJ(doc) { return (Utils.Documentos.isCPF(doc) || Utils.Documentos.isCNPJ(doc)); }
function ValidarChaveNotaFiscal(chave) { return Utils.Documentos.validarChaveNotaFiscal(chave); }

function validarNome(nome) {
    if (!nome) return false;
    if (nome.length < 5) return false;
    if (nome.search(new RegExp(" ", "i")) == -1) return false;
    return true;
}

/**************/
/*** TEXTO **/
/**************/

/** Funcao que retorna uma string cortada até a primeira occ de
 *  um caracter expecificado ou espaço se não especificar
 * @param mixed
 * @paramOptional char
 * @return mixed
 */
function firstWord(txt, chr) {
    if (!txt) return '';
    if (!chr) chr = ' ';
    var x = txt.search(new RegExp(chr, "i"));
    if (x < 0) return txt;
    return txt.substr(0, x);
}

/**
 * Funcao que retorna somente Letras de uma string
 * @param {string} a
 * @returns {string}
 */
function somenteLetras(a) {
    if (!a) return "";
    var b = a.replace(/[^a-zA-Z]+/g, '');
    return b;
}

/**
 * Funcao que retorna somente Letras e numeros de uma string
 * @param {string} a
 * @returns {string}
 */
function somenteLetrasENumeros(a) {
    if (!a) return "";
    var b = a.replace(/[^a-zA-Z0-9]+/g, '');
    return b;
}

/**
 * Funcao que retorna somente Letras, Numeros e espaços de uma string
 * @param {string} a
 * @returns {string}
 */
function somenteLetrasNumerosEEspacos(a) {
    return a.replace(/[^a-z0-9][:space:]/gi, '');
}

/**************/
/*** NUMEROS **/
/**************/

/** Função que corta os numeros finais de um float
*     @parameter mixed floatACortar
*     @parameter int quantidadeDeCasas
*     @returns mixed numeroTruncado
*/
function truncaDeVerdade(flutuante, casas) {
    var s_a2 = flutuante.toString();
    var arr_a2 = s_a2.split('.');
    if (!arr_a2[1]) { arr_a2[1] = '0'; }
    s_a2 = parseFloat(arr_a2[0]).toString() + '.' + arr_a2[1].substr(0, casas);
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
function preventComa(obj) {
    var cuRsoR = obj.selectionStart;
    obj.value = obj.value.replace(',', '.');
    obj.setSelectionRange(cuRsoR, cuRsoR);
}

/** Função que apresenta valores monetários na máscara correta
*/
function moneyMask(a) {
    return "R$ " + a.toFixed(2);
}

/** Função que remove caracteres não numéricos da string
*/
function somenteNumeros(a) {
    var b = a.replace(/[^\d]/g, '');
    return b;
}

/**************/
/*** OUTROS **/
/**************/

function copiaObj(obj) {
    return jQuery.extend(true, {}, obj);
}

/* funçao de ping para keepalive*/
function tmout() {
    if (!$('#footerPing').length) {
        $('#footer_texto').append(
            $('<div>').attr('id', 'footerPing').addClass('no-print')
        );
    }

    $.post('/ping.php', function (data) {
        $('#footerPing').html('')
            .append(data[0])
            .append(' ')
            .append(data[3]);
        setTimeout(function () { tmout(); }, 30000);
    }, 'json');
}

/* Inicialização de bibliotecas de másca de campo e desativa cache do jqery*/
(function ($) {
 $(function () {
  if ($('input:text').setMask) $('input:text').setMask();
  $.ajaxSetup({
   cache: false,
   headers: { "cache-control": "no-cache" },
  });
 });
})(jQuery);

/*CHANGELOG*/
/*2023-01-26
    removidoda funcao toggle
    removida funcao isCPF do prototype de string
    removida funcao isCNPJ do prototype de string
    arrumada identação para o padrão que EU gosto (minha lib, meu padrão)
    organizadas funçoes nos grupos
    comecei a transportar as funcoes para a classe Utils.
2023-07-24
    Bruna: Feita a função validarChaveNotaFiscal, chamada na linha 381;
*/
