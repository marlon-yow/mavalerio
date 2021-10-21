/*!
* @Autor Mavalerio https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.6 [2021-set-03]
* @copyleft GPLv3
*/

/**
 * Lib que trata as datas no padrão americano AAAA-MM-DD
 */




class DataEn extends Date {
    static dayNames = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
    static abbrDayNames = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'];
    static monthNames = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    static abbrMonthNames = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
    static ms_per_day = 86400000;// 1000 * 60 * 60 * 24;

    /*PROTO*/
    getDataText(){
        return this.getFullYear() + '-' + DataEn.pad((this.getMonth()+1),2) + '-' + DataEn.pad(this.getDate(),2);
    };
    getTimeText(){
        return DataEn.pad(this.getHours(),2)+":"+ DataEn.pad(this.getMinutes(),2) +":"+ DataEn.pad(this.getSeconds(),2);
    };
    getDia(){ return pad(this.getDate(),2); };
    getMes(){ return pad((this.getMonth()+1),2); };
    getAno(){ return this.getUTCFullYear(); };

	/**
    *    Funcao preenche com zeros a esquerda o numero
    *    @parameter mixed numero
    *    @parameter int casas
    *    @returns mixed
    */
    static pad(number, length) {
        var str = '' + number;
        while (str.length < length) {
            str = '0' + str;
        }
        return str;
    }

    /**
    * Funcao que retorna a hora atual (hh:mm:ss)
    * @param boolean true para remover segundos (:ss)
    * @returns mixed horaAtual
    */
    static getHora(half){
        var d = new DataEn;
        if(!half){ return d.getTimeText(); }
        return d.getTimeText().toString().substr(0,5);
    };

    /**
    *   Funcao que retorna o dia atual (aaaa-mm-dd)
    *   @returns mixed dataAtual
    *   @return mixed
    */
    static getHoje(){
        var d = new DataEn;
        return d.getDataText();
    };

    /**
    * Funcao que retorna data de objeto Date
    * @param obj Date()
    * @return mixed data (aaaa-mm-dd)
    */
    static dataToStr(to){
        return to.getDataText();
    };

    /**
    * Funcao que retorna objeto Date de str
    * @param mixed data (dd/mm/aaaa) ou (aaaa/mm/dd) com ou sem horas
    * @returns obj Date()
    */
    static strToData(dt){
        dt = this.normalizaData(dt);
        if(!dt) return null;

        if(dt.length == 10){
            dt = dt+'T'+'00:00:00';
        }else{
            dt = dt.substr(0,10) + 'T' + dt.substr(11,8);
        }
        var from = new DataEn(dt);
        return from;
    };

    /**
     * Normaliza Data
     * @param  mixed dt data em formato dd/mm/YYYY
     * @return mixed    data em formato YYYY-MM-DD
     *
     */
    static normalizaData(dt){
        if(!dt) return null;
        if(typeof(dt) == 'object'){
            if(dt.is(new DataEn)){
                dt = this.dataToStr(dt);
            }else{
                return '';
            }
        }
        var hr = '';
        if(dt.indexOf('-') == 4) return dt; /* data EN-US*/
        if(dt.indexOf('/') != 2){
            console.warn('FALHA:','normalizaData() com parâmetro:',dt,'nâo pôde ser processada');
            return null;
        }

        if(dt.length == 10){
            dt = dt;
            hr = '';
        }else{
            dt = dt.substr(0,10)
            hr = dt.substr(11,8);
        }
        dt = dt.split('/').reverse().join('-');

        if(hr) return dt+' '+hr; /*com timestamp*/
        return dt;
    }

    /**
    *   Funcao valida se a data existe
    *   @parameter mixed AAAA-MM-DD
    *   @returns boolean (true para data válida)
    */
    static validaData(dt){
        dt = this.normalizaData(dt);
        var hr = '';
        if(!dt) return false;
        try{
            if(dt.length == 10){
                dt = dt;
                hr = false;
            }else{
                dt = dt.substr(0,10)
                hr = dt.substr(11,8);
            }

            var arr = dt.split('-');
            var aaaa = parseInt(arr[0]);
            var mm = parseInt(arr[1]);
            var dd = parseInt(arr[2]);

            if((dd < 1 || dd > 31)) return false;
            if((mm < 1 || mm > 12)) return false;
            if((aaaa < 1900 || aaaa > 2100)) return false;

            var arr31 = {1:true,3:true,5:true,7:true,8:true,10:true,12:31}

            if(!arr31[mm] && dd==31){return false;}
            if(mm==2 && dd > 29){return false;}

            var arrBix = {2000:true, 2004:true, 2008:true, 2012:true, 2016:true, 2020:true, 2024:true,
            2028:true, 2032:true, 2036:true, 2040:true, 2044:true, 2048:true, 2052:true}
            if(mm==2 && dd == 29 && !arrBix[aaaa]){return false;}

            return true;
        }catch(err){
            console.warn('FALHA','validaData()',dt,err);
            return false;
        }
    };

    /**
    *   Funcao adiciona dias na data
    *   @parameter mixed AAAA-MM-DD
    *   @parameter int Quantidade dias
    *   @returns mixed AAAA-MM-DD
    */
    static addDias(dtBase,qDias){
        if(!dtBase){
            console.warn('FALHA','addDias()','data base em branco, failsafe: 01/01/1990');
            return '1990-01-01';
        }
        if(typeof(dtBase) != 'object'){
            dtBase = this.strToData(dtBase);
        }

        var to = new DataEn();
        to = dtBase;
        to.setDate(dtBase.getDate() + qDias);
        return this.dataToStr(to);
    };

    /**
    *   Funcao adiciona meses na data
    *   @parameter mixed DD/MM/AAAA ou AAAA-MM-DDDD
    *   @parameter int Quantidade meses
    *   @returns mixed AAAA-MM-DDDD
    */
    static addMes(dtBase,qMeses){
        dtBase = this.normalizaData(dtBase);
        var arr_dtBase = dtBase.split('-');
        if(arr_dtBase[0] && arr_dtBase[1] && arr_dtBase[2]){
            var aaaa = parseInt(arr_dtBase[0]);
            var mm = parseInt(arr_dtBase[1]);
            var dd = parseInt(arr_dtBase[2]);
            var tmp_anos = 0;

            if(qMeses < 0){
                console.warn('reducao de mês modo beta');
                var qTemp = qMeses*-1; //troca o sinal
                if(qTemp > 12){
                    tmp_anos = parseInt(qTemp/12); //reduzir anos direto
                    qMeses = qTemp-(tmp_anos*12); // pegar resto do mes
                    tmp_anos = tmp_anos*-1 //troca o sinal dos anos
                    qMeses = qMeses*-1;//troca o sinal dos meses
                }

                if(mm <= (qMeses*-1)){
                    mm = mm+12; //add 12 meses no mes
                    tmp_anos = tmp_anos-1 //remove 1 ano
                }

                mm = mm + qMeses;
            }else{
                mm = mm + qMeses;
                if(mm>12){
                    tmp_anos = parseInt(mm/12); //add anos direto
                    mm = mm-(tmp_anos*12); // pegar resto do mes
                    if(mm=='00'){mm='12'; tmp_anos--;} //se zerar, Ã© pq chegou em dez
                }
            }

            var _aaaa = aaaa + tmp_anos;
            var data_teste = this.pad(_aaaa,4)+'-'+this.pad(mm,2)+'-'+this.pad(dd,2);

            var diasTirados=0;
            if(this.validaData(data_teste)){ //existe?
                return data_teste;
            }else{ //tira um dia, tenta de novo
                dd--; diasTirados++; //31=>30
                data_teste = this.pad(_aaaa,4)+'-'+this.pad(mm,2)+'-'+this.pad(dd,2);
                if(this.validaData(data_teste)){ //tira um dia, tenta de novo
                    dd--; diasTirados++; //30=>29
                    data_teste = this.pad(_aaaa,4)+'-'+this.pad(mm,2)+'-'+this.pad(dd,2);
                    if(this.validaData(data_teste)){ //tira um dia, tenta de novo
                        dd--; diasTirados++; //29=>28
                        data_teste = this.pad(_aaaa,4)+'-'+this.pad(mm,2)+'-'+this.pad(dd,2);
                        if(this.validaData(data_teste)){ //vix
                            console.warn('Falha ao remover meses de '+dtBase+'. Data '+data_teste+' não existe. Failsafe ativo: 01/01/1990');
                            return '1990-01-01';
                        }
                    }
                }
            }
            var data_final = this.addDias(data_teste,diasTirados);
            return data_final;
        }
    };

    /**
    *    Funcao retorna do dia da semana de uma data
    *    @parameter mixed dd/mm/yyyy
    *    @returns mixed
    */
    static getDiaSemana(d){
        if(!this.validaData(d)){ return 'Erro'; }
        var x = this.strToData(d).getDay();
        return DataEn.dayNames[x];
    };

    /**
    *    Funcao para saber se eh dia util
    *    @parameter mixed dd/mm/yyyy
    *    @returns boolean (true para dias uteis, false para fds e invalidos)
    */
    static diaUtil(d){
        var e = '  ';
        e = this.getDiaSemana(d);
        var du = ['Se','Te','Qu'];
        if( du.indexOf(e.substr(0,2)) != -1 ){
            return true;
        }else{
            return false;
        }
    }

    /*
    *
    *  @parameter javascript Date object
    *  @parameter javascript Date object
    *  @returns int com a diferenca a-b
    */
    static dateDiffInDays(a, b) {
      // Discard the time and time-zone information.
      var utc1 = DataEn.UTC(a.getFullYear(), a.getMonth(), a.getDate());
      var utc2 = DataEn.UTC(b.getFullYear(), b.getMonth(), b.getDate());
      return Math.floor((utc1 - utc2) / DataEn.ms_per_day);
    }

    /**
    *    Funcao para saber diferenca de dias
    *    @parameter mixed dd/mm/yyyy
    *    @parameter mixed dd/mm/yyyy
    *    @returns int com a diferenca d1-d2
    */
    static diffDeDias(d1,d2){
        var a = this.strToData(d1);
        var b = this.strToData(d2);
        var difference = this.dateDiffInDays(a, b);
        return difference;
    }

    /**
    *    Funcao para saber se data venceu
    *    @parameter mixed dd/mm/yyyy
    *    @returns boolean
    */
    static dataVenceu(d){
        var d = this.diffDeDias(d,DataEn.getHoje());
        if(d<0) return true;
        return false;
    }

    /**
     * diferenca de tempo em segundos
     * @param  {[type]} dt [description]
     * @return {[type]}    [description]
     */
    static getQuantoTempoPassou(dt){
        /* em segundos */
        dt = this.normalizaData(dt);
        return parseInt( (new Date()-this.strToData(dt))/1000 );
    }

    /**
     * Tempo de Humman Read de segundos para tempos e tempos
     * @param  int secs (diferença entre 2 objs data /1000) ou tempo em segundos
     * @return mixed quanto tempo passou
     */
    static time_elapsed_B(secs){
        if(secs === 0) return 'agora';
        if(!secs){
            console.warn('time_elapsed_B() call w/o params');
            return '...';
        }

        var bit = {
            ' ano'       : secs / 31556926 % 12,
            ' semana'    : secs / 604800 % 52,
            ' dia'       : secs / 86400 % 7,
            ' hora'      : secs / 3600 % 24,
            ' minuto'    : secs / 60 % 60,
            ' segundo'   : secs % 60
        };

        ret = [];
        $.each(bit,function(k,v){
            var v2 = parseInt(v);
            if(v2 > 1) ret.push( v2 + k + 's');
            if(v2 == 1) ret.push(v2 + k);
        });

        if(ret.length > 1){
            ret.splice(ret.length-1,0,'e');
        }

        return 'a ' + ret.join(' ');
    }

    static diffDeHoras(h1,h2){
        var hh1 = h1.split(':');
        var hh2 = h2.split(':');

        var hh = parseInt(hh1[0]) - parseInt(hh2[0]);
        var mm = parseInt(hh1[1]) - parseInt(hh2[1]);
        var ss = parseInt(hh1[2]) - parseInt(hh2[2]);

        if(ss < 0){
            ss = ss + 60;
            mm = mm -1;
        }

        if(mm < 0){
            mm = mm + 60;
            hh = hh -1;
        }

        if(hh<0){
            console.warn('ERRO: diffDeHoras(h1,h2)',h1,h2,'failsafe 00:00:01');
            return '00:00:01';
        }

        return this.pad(hh,2)+':'+this.pad(mm,2)+':'+this.pad(ss,2);
    }

    static dataPadraoBR(dt){
        dt = this.normalizaData(dt);
        if(!dt) return null;
        var hora = null;
        if(dt.length > 10){
            hora = dt.substr(11,8);
        }
        var data = dt.substr(11,8);

        var arr = dt.split('-');
        arr = arr.reverse();
        return arr.join('/')+(hora ? " "+hora : '');
    }
}
