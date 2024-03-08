<?php
/*! ---UTF-8---
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.2 [2022-out-07]
* @copyleft
*/

/*
https://stackoverflow.com/questions/3422759/php-aes-encrypt-decrypt/46872528#46872528

If you don't want to use a heavy dependency for something solvable in 15 lines of code, use the built in OpenSSL functions. Most PHP installations come with OpenSSL, which provides fast, compatible and secure AES encryption in PHP. Well, it's secure as long as you're following the best practices.

The following code:

uses AES256 in CBC mode
is compatible with other AES implementations, but not mcrypt, since mcrypt uses PKCS#5 instead of PKCS#7.
generates a key from the provided password using SHA256
generates a hmac hash of the encrypted data for integrity check
generates a random IV for each message
prepends the IV (16 bytes) and the hash (32 bytes) to the ciphertext
should be pretty secure
IV is a public information and needs to be random for each message. The hash ensures that the data hasn't been tampered with.


Usage:

$encrypted = encrypt('Plaintext string.', 'password'); // this yields a binary string

echo decrypt($encrypted, 'password');
// decrypt($encrypted, 'wrong password') === null


edited Oct 5, 2019 at 9:43
answered Oct 22, 2017 at 9:31 Blade

2022-10-18
criptografar deixa as coisas muito grandes, tive que comprimir os dados usando GZ-LIB
                rich 364    poor 364    rich 2557   poor 2557   rich 10228  poor 10228
gzdeflate       564         500         3484        3420        13724       13660
none            556         492         3480        3416        13720       13656
gzdeflate-bin   421         373         2613        2565        10293       10245
none-bin        416         368         2608        2560        10288       10240

                rich 364    rich 2557
gzdeflate       out 564     out 3484
none            out 556     out 3480
gzcompress      out 572     out 3492
gzencode        out 588     out 3508

puts, compactar e transformar em caracter de volta faz ficar maior que não compactar
pra isso funcionar precisa mandar o dado em binário pro banco
isso vai dar BO na hora de gerar backup SQL plaintext

2022-10-19
criar metodo de criptografia mais leve que não usa iv aleatório nem hash
** Para gerar um novo IV: echo base64_encode(openssl_random_pseudo_bytes(16));
não muda progressivamente, é sempre 64 chars.

pelos testes, usar o poor só se for alguma coisa muuuuito pequena,
e compactação só funcina para mais de 15k char

//TODO: posso fazer um verificador de tamanho, aí se for grande compacta

*/


class SimpleCrip{

    private $criptMethod = "AES-256-CBC";
    private $compressMethod = 'deflate'; //deflate é o menos pior
    private $compressLevel = 6; //6- default
    private $poorIV = "fAlMLPeEFsQsYGTI/37WPw==";
    public $compressoes = array(
        'c' => 'compress',
        'd' => 'deflate',
        'e' => 'encode',
        'n' => 'none'
    );
    public $critpografias = array(
        'r' => 'rich',
        'p' => 'poor'
    );

    /**
     * __construct
     * @param string $poorIV  base64_encoded binary from ""echo base64_encode(openssl_random_pseudo_bytes(16));""
     */
    public function __construct($poorIV=null){
        if($poorIV){
            $this->poorIV = $poorIV;
        }else{
            trigger_error("Biblioteca SimpleCrip: modo inseguro, defina um IV.", E_USER_NOTICE);
        }
    }

    /**
     * set altera as configurações de compactação
     * @param string $cMode   [compress deflate encode none] default=none
     * @param int $cLevel  compress level
     */
    public function set($cMode = null, $cLevel = null) {
        if ($cMode) { $this->compressMethod = $cMode; }
        if ($cLevel) { $this->compressLevel = $cLevel; }
    }

    public function encrypt($plaintext, $password, $returnBinary=false, $mode='rich') {
        // 1 - compressao
        $compress = $this->compress($plaintext);

        if ($mode == 'rich') {
            $enc = $this->richEncrypt($compress, $password, $returnBinary);
            $enc = substr($this->compressMethod, 0, 1) . 'r-' . $enc;
        } else {
            $enc = $this->poorEncrypt($compress, $password, $returnBinary);
            $enc = substr($this->compressMethod, 0, 1) . 'p-' . $enc;
        }
        return $enc;
    }
    public function decrypt($ivHashCiphertext, $password, $inputBinary=false, $mode='r') {
        if(substr($ivHashCiphertext,2,1) == '-'){
            $metodoCompressao = substr($ivHashCiphertext, 0, 1);
            $metodoCripto = substr($ivHashCiphertext, 1, 1);
            $ivHashCiphertext = substr($ivHashCiphertext,3);
            //echo "Compactado com $metodoCompressao em criptografado com $metodoCripto";
        }else{
            $metodoCompressao = 'd';
            $metodoCripto = $mode;
        }

        $this->set($this->compressoes[$metodoCompressao]);
        if($metodoCripto == 'r'){
            return $this->richDecrypt($ivHashCiphertext, $password, $inputBinary);
        }else if($metodoCripto == 'p'){
            return $this->poorDecrypt($ivHashCiphertext, $password, $inputBinary);
        }else{
            echo "Compactado com $metodoCompressao em criptografado com $metodoCripto";
            debug('FALHA');
        }
    }

    private function richEncrypt($plaintext, $password, $returnBinary=false) {
        // 2 - gerar chaves
        $key = hash('sha256', $password, true);
        $iv = openssl_random_pseudo_bytes(16);
        // 3 - criptar
        $ciphertext = openssl_encrypt($plaintext, $this->criptMethod, $key, OPENSSL_RAW_DATA, $iv);
        // 4 - verificador de integridade
        $hash = hash_hmac('sha256', $ciphertext . $iv, $key, true);

        // $bloco = base64_encode($iv . $hash . $ciphertext); //ORIGINAL
        // 5 - pegar o bloco raw
        $bloco = $iv . $hash . $ciphertext;
        // 6 - base64 ou binario
        if($returnBinary) return $bloco;
        $res = base64_encode($bloco);

        return $res;
    }
    private function poorEncrypt($plaintext, $password, $returnBinary = false) {
        // 2 - gerar chaves
        $key = hash('sha256', $password, true);
        $iv = base64_decode($this->poorIV);
        // 3 - criptar
        $ciphertext = openssl_encrypt($plaintext, $this->criptMethod, $key, OPENSSL_RAW_DATA, $iv);
        //pra economizar não vai ter teste de hash
        //$hash = hash_hmac('sha256', $ciphertext . $iv, $key, true);

        // $bloco = base64_encode($iv . $hash . $ciphertext); //ORIGINAL
        // 5 - pegar o bloco raw
        $bloco = $ciphertext;
        // 3 - base64 ou binario
        if($returnBinary) return $bloco;
        $res = base64_encode($bloco);

        return $res;
    }

    private function richDecrypt($ivHashCiphertext, $password, $inputBinary=false) {
        // 1 - des base 64 ou binario direto
        if($inputBinary){
            $res = $ivHashCiphertext;
        }else{
            $res = base64_decode($ivHashCiphertext);
        }

        $ivHashCiphertext = $res;
        //$ivHashCiphertext = base64_decode($ivHashCiphertext); //ORIGINAL
        $iv = substr($ivHashCiphertext, 0, 16);
        $hash = substr($ivHashCiphertext, 16, 32);
        $ciphertext = substr($ivHashCiphertext, 48);
        $key = hash('sha256', $password, true);

        if (!hash_equals(hash_hmac('sha256', $ciphertext . $iv, $key, true), $hash)) return 'CRIP FAIL';

        $uncript = openssl_decrypt($ciphertext, $this->criptMethod, $key, OPENSSL_RAW_DATA, $iv);
        $uncompress = $this->uncompress($uncript);
        return $uncompress;
    }
    private function poorDecrypt($ivHashCiphertext, $password, $inputBinary=false) {
        // 1 - des base 64
        if($inputBinary){
            $res = $ivHashCiphertext;
        }else{
            $res = base64_decode($ivHashCiphertext);
        }
        // 3 -
        $ivHashCiphertext = $res;

        $iv = base64_decode($this->poorIV);
        $ciphertext = $ivHashCiphertext;
        $key = hash('sha256', $password, true);

        //if (!hash_equals(hash_hmac('sha256', $ciphertext . $iv, $key, true), $hash)) return null;

        $uncript = openssl_decrypt($ciphertext, $this->criptMethod, $key, OPENSSL_RAW_DATA, $iv);
        // 2 - descomprimir
        $uncompress = $this->uncompress($uncript);
        return $uncompress;
    }

    public function compress($data){
        if(is_null($data)) return '';
        switch ($this->compressMethod) {
            case 'compress': $compress = gzcompress($data,$this->compressLevel); break;
            case 'deflate': $compress = gzdeflate($data,$this->compressLevel); break;
            case 'encode': $compress = gzencode($data,$this->compressLevel); break;
            case 'none'  : $compress = $data; break;
        }
        return $compress;
    }
    private function uncompress($data){
        if(is_null($data)) return '';
        if($data == '') return '';
        if($data == '""') return '';
        if($data == "''") return '';
        if($data == "null") return '';
        switch ($this->compressMethod) {
            case 'compress': $uncompress = gzuncompress($data); break;
            case 'deflate': $uncompress = gzinflate($data); break;
            case 'encode': $uncompress = gzdecode($data); break;
            case 'none': $uncompress = $data; break;
        }

        if(error_get_last()){
            trigger_error("Erro de criptografia, tentando descompactar: ".print_r($data,true), E_USER_WARNING);
            error_clear_last();
            $uncompress = $data;
        }
        return $uncompress;
    }

    public function doubleSha1($value,$salt=null){
        if(!$salt){
            trigger_error("Fazer Double Sha1 sem Salt é inseguro!" , E_USER_WARNING);
        }
        return sha1(sha1($value).$salt);
    }
}
