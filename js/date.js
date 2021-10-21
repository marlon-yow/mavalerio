/*
 * Date prototype extensions. Doesn't depend on any
 * other code. Doens't overwrite existing methods.
 *
 * Adds dayNames, abbrDayNames, monthNames and abbrMonthNames static properties and isLeapYear,
 * isWeekend, isWeekDay, getDaysInMonth, getDayName, getMonthName, getDayOfYear, getWeekOfYear,
 * setDayOfYear, addYears, addMonths, addDays, addHours, addMinutes, addSeconds methods
 *
 * Copyright (c) 2006 Jörn Zaefferer and Brandon Aaron (brandon.aaron@gmail.com || http://brandonaaron.net)
 *
 * Additional methods and properties added by Kelvin Luck: firstDayOfWeek, dateFormat, zeroTime, asString, fromString -
 * I've added my name to these methods so you know who to blame if they are broken!
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 */

/**
  * Date prototype extensions. Doesn't depend on any
  * other code. Doens't overwrite existing methods.
  *
  * Adds dayNames, abbrDayNames, monthNames and abbrMonthNames static properties and isLeapYear,
  * isWeekend, isWeekDay, getDaysInMonth, getDayName, getMonthName, getDayOfYear, getWeekOfYear,
  * setDayOfYear, addYears, addMonths, addDays, addHours, addMinutes, addSeconds methods
  *
  * Copyright (c) 2006 Jörn Zaefferer and Brandon Aaron (brandon.aaron@gmail.com || http://brandonaaron.net)
  *
  * Additional methods and properties added by Kelvin Luck: firstDayOfWeek, dateFormat, zeroTime, asString, fromString -
  * I've added my name to these methods so you know who to blame if they are broken!
  *
  * Dual licensed under the MIT and GPL licenses:
  *   http://www.opensource.org/licenses/mit-license.php
  *   http://www.gnu.org/licenses/gpl.html
  *
  */

 /**
  * An Array of day names starting with Sunday.
  *
  * @example dayNames[0]
  * @result 'Sunday'
  *
  * @name dayNames
  * @type Array
  * @cat Plugins/Methods/Date
  */
 Date.dayNames = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

 /**
  * An Array of abbreviated day names starting with Sun.
  *
  * @example abbrDayNames[0]
  * @result 'Sun'
  *
  * @name abbrDayNames
  * @type Array
  * @cat Plugins/Methods/Date
  */
 Date.abbrDayNames = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'];

 /**
 * An Array of month names starting with Janurary.
 *
 * @example monthNames[0]
 * @result 'January'
 *
 * @name monthNames
 * @type Array
 * @cat Plugins/Methods/Date
 */
 Date.monthNames = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

 /**
 * An Array of abbreviated month names starting with Jan.
 *
 * @example abbrMonthNames[0]
 * @result 'Jan'
 *
 * @name monthNames
 * @type Array
 * @cat Plugins/Methods/Date
 */
 Date.abbrMonthNames = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

 /**
 * The first day of the week for this locale.
 *
 * @name firstDayOfWeek
 * @type Number
 * @cat Plugins/Methods/Date
 * @author Kelvin Luck
 */
 Date.firstDayOfWeek = 1;

 /**
 * The format that string dates should be represented as (e.g. 'dd/mm/yyyy' for UK, 'mm/dd/yyyy' for US, 'yyyy-mm-dd' for Unicode etc).
 *
 * @name format
 * @type String
 * @cat Plugins/Methods/Date
 * @author Kelvin Luck
 */
 Date.format = 'dd/mm/yyyy';
 //Date.format = 'mm/dd/yyyy';
 //Date.format = 'yyyy-mm-dd';
 //Date.format = 'dd mmm yy';

 /**
 * The first two numbers in the century to be used when decoding a two digit year. Since a two digit year is ambiguous (and date.setYear
 * only works with numbers < 99 and so doesn't allow you to set years after 2000) we need to use this to disambiguate the two digit year codes.
 *
 * @name format
 * @type String
 * @cat Plugins/Methods/Date
 * @author Kelvin Luck
 */
 Date.fullYearStart = '20';

(function() {

     /**
     * Adds a given method under the given name
     * to the Date prototype if it doesn't
     * currently exist.
     *
     * @private
     */
    function add(name, method) {
        if( !Date.prototype[name] ) {
            Date.prototype[name] = method;
        }
    };

     /**
     * Checks if the year is a leap year.
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.isLeapYear();
     * @result true
     *
     * @name isLeapYear
     * @type Boolean
     * @cat Plugins/Methods/Date
     */
    add("isLeapYear", function() {
        var y = this.getFullYear();
        return (y%4==0 && y%100!=0) || y%400==0;
    });

     /**
     * Checks if the day is a weekend day (Sat or Sun).
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.isWeekend();
     * @result false
     *
     * @name isWeekend
     * @type Boolean
     * @cat Plugins/Methods/Date
     */
    add("isWeekend", function() {
        return this.getDay()==0 || this.getDay()==6;
    });

     /**
     * Check if the day is a day of the week (Mon-Fri)
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.isWeekDay();
     * @result false
     *
     * @name isWeekDay
     * @type Boolean
     * @cat Plugins/Methods/Date
     */
    add("isWeekDay", function() {
        return !this.isWeekend();
    });

     /**
     * Gets the number of days in the month.
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.getDaysInMonth();
     * @result 31
     *
     * @name getDaysInMonth
     * @type Number
     * @cat Plugins/Methods/Date
     */
    add("getDaysInMonth", function() {
        return [31,(this.isLeapYear() ? 29:28),31,30,31,30,31,31,30,31,30,31][this.getMonth()];
    });

     /**
     * Gets the name of the day.
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.getDayName();
     * @result 'Saturday'
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.getDayName(true);
     * @result 'Sat'
     *
     * @param abbreviated Boolean When set to true the name will be abbreviated.
     * @name getDayName
     * @type String
     * @cat Plugins/Methods/Date
     */
    add("getDayName", function(abbreviated) {
        return abbreviated ? Date.abbrDayNames[this.getDay()] : Date.dayNames[this.getDay()];
    });

     /**
     * Gets the name of the month.
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.getMonthName();
     * @result 'Janurary'
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.getMonthName(true);
     * @result 'Jan'
     *
     * @param abbreviated Boolean When set to true the name will be abbreviated.
     * @name getDayName
     * @type String
     * @cat Plugins/Methods/Date
     */
    add("getMonthName", function(abbreviated) {
        return abbreviated ? Date.abbrMonthNames[this.getMonth()] : Date.monthNames[this.getMonth()];
    });

     /**
     * Get the number of the day of the year.
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.getDayOfYear();
     * @result 11
     *
     * @name getDayOfYear
     * @type Number
     * @cat Plugins/Methods/Date
     */
    add("getDayOfYear", function() {
        var tmpdtm = new Date("1/1/" + this.getFullYear());
        return Math.floor((this.getTime() - tmpdtm.getTime()) / 86400000);
    });

     /**
     * Get the number of the week of the year.
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.getWeekOfYear();
     * @result 2
     *
     * @name getWeekOfYear
     * @type Number
     * @cat Plugins/Methods/Date
     */
     add("getWeekOfYear", function() {
         return Math.ceil(this.getDayOfYear() / 7);
     });

     /**
     * Set the day of the year.
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.setDayOfYear(1);
     * dtm.toString();
     * @result 'Tue Jan 01 2008 00:00:00'
     *
     * @name setDayOfYear
     * @type Date
     * @cat Plugins/Methods/Date
     */
    add("setDayOfYear", function(day) {
        this.setMonth(0);
        this.setDate(day);
        return this;
    });

     /**
     * Add a number of years to the date object.
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.addYears(1);
     * dtm.toString();
     * @result 'Mon Jan 12 2009 00:00:00'
     *
     * @name addYears
     * @type Date
     * @cat Plugins/Methods/Date
     */
    add("addYears", function(num) {
        this.setFullYear(this.getFullYear() + num);
        return this;
    });

     /**
     * Add a number of months to the date object.
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.addMonths(1);
     * dtm.toString();
     * @result 'Tue Feb 12 2008 00:00:00'
     *
     * @name addMonths
     * @type Date
     * @cat Plugins/Methods/Date
     */
    add("addMonths", function(num) {
        var tmpdtm = this.getDate();
        this.setMonth(this.getMonth() + num);
        if (tmpdtm > this.getDate())
            this.addDays(-this.getDate());
        return this;
    });

    /**
     * Add a number of days to the date object.
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.addDays(1);
     * dtm.toString();
     * @result 'Sun Jan 13 2008 00:00:00'
     *
     * @name addDays
     * @type Date
     * @cat Plugins/Methods/Date
     */
    add("addDays", function(num) {
        //this.setDate(this.getDate() + num);
        this.setTime(this.getTime() + (num*86400000) );
        return this;
    });

     /**
     * Add a number of hours to the date object.
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.addHours(24);
     * dtm.toString();
     * @result 'Sun Jan 13 2008 00:00:00'
     *
     * @name addHours
     * @type Date
     * @cat Plugins/Methods/Date
      */
    add("addHours", function(num) {
        this.setHours(this.getHours() + num);
        return this;
    });

     /**
     * Add a number of minutes to the date object.
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.addMinutes(60);
     * dtm.toString();
     * @result 'Sat Jan 12 2008 01:00:00'
     *
     * @name addMinutes
     * @type Date
     * @cat Plugins/Methods/Date
     */
    add("addMinutes", function(num) {
        this.setMinutes(this.getMinutes() + num);
        return this;
    });

     /**
     * Add a number of seconds to the date object.
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.addSeconds(60);
     * dtm.toString();
     * @result 'Sat Jan 12 2008 00:01:00'
     *
     * @name addSeconds
     * @type Date
     * @cat Plugins/Methods/Date
     */
    add("addSeconds", function(num) {
        this.setSeconds(this.getSeconds() + num);
        return this;
    });

    /**
     * Sets the time component of this Date to zero for cleaner, easier comparison of dates where time is not relevant.
     *
     * @example var dtm = new Date();
     * dtm.zeroTime();
     * dtm.toString();
     * @result 'Sat Jan 12 2008 00:01:00'
     *
     * @name zeroTime
     * @type Date
     * @cat Plugins/Methods/Date
     * @author Kelvin Luck
     */
    add("zeroTime", function() {
        this.setMilliseconds(0);
        this.setSeconds(0);
        this.setMinutes(0);
        this.setHours(0);
        return this;
    });

     /**
     * Returns a string representation of the date object according to Date.format.
     * (Date.toString may be used in other places so I purposefully didn't overwrite it)
     *
     * @example var dtm = new Date("01/12/2008");
     * dtm.asString();
     * @result '12/01/2008' // (where Date.format == 'dd/mm/yyyy'
     *
     * @name asString
     * @type Date
     * @cat Plugins/Methods/Date
     * @author Kelvin Luck
     */
    add("asString", function(format) {
        var r = format || Date.format;
        return r
            .split('yyyy').join(this.getFullYear())
            .split('yy').join((this.getFullYear() + '').substring(2))
            .split('mmmm').join(this.getMonthName(false))
            .split('mmm').join(this.getMonthName(true))
            .split('mm').join(_zeroPad(this.getMonth()+1))
            .split('dd').join(_zeroPad(this.getDate()))
            .split('hh').join(_zeroPad(this.getHours()))
            .split('min').join(_zeroPad(this.getMinutes()))
            .split('ss').join(_zeroPad(this.getSeconds()));
    });

    /**
     * Returns a new date object created from the passed String according to Date.format or false if the attempt to do this results in an invalid date object
     * (We can't simple use Date.parse as it's not aware of locale and I chose not to overwrite it incase it's functionality is being relied on elsewhere)
     *
     * @example var dtm = Date.fromString("12/01/2008");
     * dtm.toString();
     * @result 'Sat Jan 12 2008 00:00:00' // (where Date.format == 'dd/mm/yyyy'
     *
     * @name fromString
     * @type Date
     * @cat Plugins/Methods/Date
     * @author Kelvin Luck
     */
    Date.fromString = function(s, format){
        var f = format || Date.format;
        var d = new Date('01/01/1977');

        var mLength = 0;

        var iM = f.indexOf('mmmm');
        if (iM > -1) {
            for (var i=0; i<Date.monthNames.length; i++) {
                var mStr = s.substr(iM, Date.monthNames[i].length);
                if (Date.monthNames[i] == mStr) {
                    mLength = Date.monthNames[i].length - 4;
                    break;
                }
            }
            d.setMonth(i);
        } else {
            iM = f.indexOf('mmm');
            if (iM > -1) {
                var mStr = s.substr(iM, 3);
                for (var i=0; i<Date.abbrMonthNames.length; i++) {
                    if (Date.abbrMonthNames[i] == mStr) break;
                }
                d.setMonth(i);
            } else {
                d.setMonth(Number(s.substr(f.indexOf('mm'), 2)) - 1);
            }
        }

        var iY = f.indexOf('yyyy');

        if (iY > -1) {
            if (iM < iY){
                iY += mLength;
            }
            d.setFullYear(Number(s.substr(iY, 4)));
        } else {
            if (iM < iY){
                iY += mLength;
            }
            // TODO - this doesn't work very well - are there any rules for what is meant by a two digit year?
            d.setFullYear(Number(Date.fullYearStart + s.substr(f.indexOf('yy'), 2)));
        }
        var iD = f.indexOf('dd');
        if (iM < iD){
            iD += mLength;
        }
        d.setDate(Number(s.substr(iD, 2)));
        if (isNaN(d.getTime())) {
            return false;
        }
        return d;
    };

    // utility method
    var _zeroPad = function(num) {
        var s = '0'+num;
        return s.substring(s.length-2)
        //return ('0'+num).substring(-2); // doesn't work on IE :(
    };

})();

/*LIBS ADICIONAIS UBRS*/
/*!
* @Autor Mavalerio https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.4 [2021-mar-22]
* @copyleft GPLv3
*/

    /**
    *    Função de expanção das funcoes de data
    *   @param char caracter separador de elementos, padrão barra numerica (/)
    *   @param boolean true para inverter data para YYYY/MM/DD, false traz no padrão DD/MM/YYYY
    *   @return mixed data de hoje
    */
    Date.prototype.today = function(sep,invert){
        if(!sep) sep = '/';
        if(!invert) return ((this.getDate() < 10)?"0":"") + this.getDate() +sep+(((this.getMonth()+1) < 10)?"0":"") + (this.getMonth()+1) +sep+ this.getFullYear();
        return this.getFullYear() + sep + (((this.getMonth()+1) < 10)?"0":"") + (this.getMonth()+1) + sep + ((this.getDate() < 10)?"0":"") + this.getDate();
    };

    /**
    *    Função de expanção das funcoes de hora
    *   @return mixed horario hh:mi:ss
    */
    Date.prototype.timeNow = function(){
         return ((this.getHours() < 10)?"0":"") + this.getHours() +":"+ ((this.getMinutes() < 10)?"0":"") + this.getMinutes() +":"+ ((this.getSeconds() < 10)?"0":"") + this.getSeconds();
    };

    /**
    * Função retorna o dia atual
    * @return mixed
    */
    Date.prototype.getDia = function(){ return ((this.getDate() < 10)?"0":"") + this.getDate(); }

    /**
    * Funcao retorna o mes atual
    * @return mixed
    */
    Date.prototype.getMes = function(){ return (((this.getMonth()+1) < 10)?"0":"") + (this.getMonth()+1); }

     /**
     * Funcao retorna ano atual
     * @return mixed
     */
    Date.prototype.getAno = function(){ return this.getUTCFullYear(); }

    /**
    * Função que retorna a hora atual (hh:mm)
    * @param boolean true para segundos (:ss)
    * @returns mixed horaAtual
    */
    function getHora(full){
        var d = new Date;
        if(full){ return d.timeNow(); }
        return d.timeNow().toString().substr(0,5);
    }

    /**
    *    Função que retorna o dia atual (dd/mm/aaaa)
    *     @returns mixed dataAtual
    *   @param char caracter separador de elementos, padrão barra numerica (/)
    *   @param boolean true para inverter data para YYYY/MM/DD, false traz no padrão DD/MM/YYYY
    *   @return mixed
    */
    function getDia(sep,inverter){
        var d = new Date;
        return d.today(sep,inverter);
    }

    /**
    * Função que retorna data de objeto Date
    * @param obj Date()
    * @return mixed data (dd/mm/aaaa)
    */
    function dataToStr(to){
        var mes = to.getMonth()+1;
        return pad(to.getDate(),2)+'/'+pad(mes,2)+'/'+to.getFullYear();
    }

    /**
    * Função que retorna objeto Date de str
    * @param mixed data (dd/mm/aaaa) ou (aaaa/mm/dd) com ou sem horas
    * @returns obj Date()
    */
    function strToData(dt){
        dt = normalizaData(dt);
        if(!dt) return null;

        if(dt.length == 10){
            dt = dt+'T'+'00:00:00';
        }

        var from = new Date(dt);
        return from;
    }

    /**
    *    Funcao transforma horas HH:MM em numero inteiro para efetuar calculos.
    *     @parameter hora int
    *    @returns mixed hora HH:MM
    */
    function hora2inteiro(hora){
        var _arr = hora.split(':');
        var mins = 0;
        if( _arr[1] > 0){
            mins = _arr[1] / 60;
        }
        var inteiro = parseFloat(_arr[0]) + mins;
        if( isNaN(inteiro) ){
            inteiro = 0;
        }
        return inteiro;
    }

    /**
    *    Funcao transforma numero inteiro em horas HH:MM.
    *    @parameter mixed HH:MM
    *    @returns float
    */
    function inteiro2hora(inteiro){
        if(typeof(inteiro) == 'number' ){
            inteiro = inteiro.toString().replace('.',',');
        }
        var _arr = inteiro.split(',');
        var min = parseFloat('0.'+_arr[1]) * 60;
        var min = min.toFixed();
        var hora = pad(_arr[0],2)+':'+pad(min,2);
        return hora;
    }

    /**
    *   Funcao adiciona dias na data
    *   @parameter mixed DD/MM/AAAA
    *   @parameter int Quantidade dias
    *   @returns mixed DD/MM/AAAA
    */
    function addDias(dtBase,qDias){
        if(!dtBase){
            console.warn('FALHA','addDias()','data base em branco, failsafe: 01/01/1990');
            return '01/01/1990';
        }
        if(typeof(dtBase) != 'object'){
            dtBase = strToData(dtBase);
        }

        var to = new Date();
        to = dtBase;
        to.setDate(dtBase.getDate() + qDias);
        return dataToStr(to);
    }

    /**
    *   Funcao adiciona meses na data
    *   @parameter mixed DD/MM/AAAA
    *   @parameter int Quantidade meses
    *   @returns mixed DD/MM/AAAA
    */
    function addMes(dtBase,qMeses){
        var arr_dtBase = dtBase.split('/');
        if(arr_dtBase[0] && arr_dtBase[1] && arr_dtBase[2]){
            var dd = parseInt(arr_dtBase[0]);
            var mm = parseInt(arr_dtBase[1]);
            var aaaa = parseInt(arr_dtBase[2]);
            var tmp_anos = 0;

            if(qMeses < 0){
                console.log('reducao de mês modo beta');
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
                    if(mm=='00'){mm='12'; tmp_anos--;} //se zerar, é pq chegou em dez
                }
            }

            var _aaaa = aaaa + tmp_anos;
            var data_teste = pad(dd,2)+'/'+pad(mm,2)+'/'+pad(_aaaa,4);

            var diasTirados=0;
            if(validaData(data_teste)){ //existe?
                return data_teste;
            }else{ //tira um dia, tenta de novo
                dd--; diasTirados++; //31=>30
                data_teste = pad(dd,2)+'/'+pad(mm,2)+'/'+pad(_aaaa,4);
                if(validaData(data_teste)){ //tira um dia, tenta de novo
                    dd--; diasTirados++; //30=>29
                    data_teste = pad(dd,2)+'/'+pad(mm,2)+'/'+pad(_aaaa,4);
                    if(validaData(data_teste)){ //tira um dia, tenta de novo
                        dd--; diasTirados++; //29=>28
                        data_teste = pad(dd,2)+'/'+pad(mm,2)+'/'+pad(_aaaa,4);
                        if(validaData(data_teste)){ //vix
                            console.log('Falha ao remover meses de '+dtBase+'. Data '+data_teste+' não existe. Failsafe ativo: 01/01/1990');
                            return '01/01/1990';
                        }
                    }
                }
            }
            var data_final = addDias(data_teste,diasTirados);
            return data_final;
        }
    }

    /**
    *   Funcao valida se a data existe
    *   @parameter mixed DD/MM/AAAA
    *   @returns boolean (true para data válida)
    */
    function validaData(dt){
        dt = normalizaData(dt);
        if(!dt) return false;
        try{
            var time = dt.split(' ');
            var arr = time[0].split('-');

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
    }

    /**
    *    Funcao preenche com zeros a esquerda o numero
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

    /**
    *    Funcao strToData retro compativel
    */
    function transformaEmDate(dtBase){
        return strToData(dtBase);
    }

    /**
    *    Funcao retorna do dia da semana de uma data
    *    @parameter mixed dd/mm/yyyy
    *    @returns mixed
    */
    function getDiaSemana(d){
        if(!validaData(d)){ return 'Erro'; }
        var x = strToData(d).getDay();
        return Date.dayNames[x];
    }

    /**
    *    Funcao para saber se eh dia util
    *    @parameter mixed dd/mm/yyyy
    *    @returns boolean (true para dias uteis, false para fds e invalidos)
    */
    function diaUtil(d){
        var e = '  ';
        e = getDiaSemana(d);
        var du = ['Se','Te','Qu'];
        if( du.indexOf(e.substr(0,2)) != -1 ){
            return true;
        }else{
            return false;
        }
    }


    var _MS_PER_DAY = 1000 * 60 * 60 * 24;
    // a and b are javascript Date objects
    function dateDiffInDays(a, b) {
      // Discard the time and time-zone information.
      var utc1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate());
      var utc2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate());
      return Math.floor((utc2 - utc1) / _MS_PER_DAY);
    }

    /**
    *    Funcao para saber diferenca de dias
    *    @parameter mixed dd/mm/yyyy
    *    @parameter mixed dd/mm/yyyy
    *    @returns int com a diferenca d1-d2
    */
    function diffDeDias(d1,d2){
        var a = strToData(d1);
        var b = strToData(d2);
        var difference = dateDiffInDays(a, b);
        return difference;
    }

    /**
    *    Funcao para saber se data venceu
    *    @parameter mixed dd/mm/yyyy
    *    @returns boolean
    */
    function dataVenceu(d){
        var d = diffDeDias(getDia(),d);
        if(d<0) return true;
        return false;
    }

    function getQuantoTempoPassou(dt){
        /* em segundos */
        dt = normalizaData(dt);
        return parseInt( (new Date()-strToData(dt))/1000 );
    }

    /**
     * Tempo de Humman Read de segundos para tempos e tempos
     * @param  int secs (diferença entre 2 objs data /1000) ou tempo em segundos
     * @return mixed quanto tempo passou
     */
    function time_elapsed_B(secs){
        if(secs === 0) return 'agora';
        if(!secs){
            console.log('time_elapsed_B() call w/o params');
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

    /**
     * Normaliza Data
     * @param  mixed dt data em formato dd/mm/YYYY
     * @return mixed    data em formato YYYY-MM-DD
     *
     */
    function normalizaData(dt){
        if(!dt) return null;
        if(dt.indexOf('-') == 4) return dt; /* data EN-US*/
        if(dt.indexOf('/') != 2){
            console.warn('FALHA:','normalizaData() com parâmetro:',dt,'não pôde ser processada');
            return null;
        }

        var times = dt.split(' ');
        times[0] = times[0].split('/').reverse().join('-');

        if(times[1]) return times[0]+' '+times[1]; /*com timestamp*/
        return times[0];
    }

    function diffDeHoras(h1,h2){
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

        return pad(hh,2)+':'+pad(mm,2)+':'+pad(ss,2);
    }
