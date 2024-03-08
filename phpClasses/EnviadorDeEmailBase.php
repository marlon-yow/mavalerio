<?php
/*! ---UTF-8---
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.3 [2023-jan-13]
* @copyleft
*/
namespace mavalerio\phpClasses;

    include_once VENDOR.'phpMailer-6.3.0/PHPMailer.php';
    include_once VENDOR.'phpMailer-6.3.0/SMTP.php';
    include_once VENDOR.'phpMailer-6.3.0/OAuth.php';
    include_once VENDOR.'phpMailer-6.3.0/POP3.php';
    include_once VENDOR.'phpMailer-6.3.0/Exception.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    if(!NOME_APRESENTACAO){ echo "NOME_APRESENTACAO não definido;"; die; }
    if(!EMAIL_FROM_LINK){ echo "EMAIL_FROM_LINK não definido;"; die; }
    if(!EMAIL_FROM_NAME){ echo "EMAIL_FROM_NAME não definido;"; die; }
    if(!EMAIL_FROM_EMAIL){ echo "EMAIL_FROM_EMAIL não definido;"; die; }
    if(!RELAY_HOST){ echo "RELAY_HOST não definido;"; die; }
    if(!RELAY_USER){ echo "RELAY_USER não definido;"; die; }
    if(!RELAY_PASS){ echo "RELAY_PASS não definido;"; die; }

if (!class_exists('mavalerio\phpClasses\EnviadorDeEmailBase')) {
class EnviadorDeEmailBase{
    public $DBG = 0;

    private function getHeader(){
        global $path;
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__; if(func_num_args()){ echo "<xmp>" .print_r(func_get_args(),1)."</xmp>";} echo "<br>\n"; }

        $image = $path."imagens/"."fx48.png";
        $type = pathinfo($image, PATHINFO_EXTENSION);
        $data = file_get_contents($image);
        $dataUri = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $headMail = "<html><head/><body>
            <table style='width:90%;border:1px' align='center'>
                <tr>
                    <td>
                        <a href='".EMAIL_FROM_LINK."'>
                            <img src=\"$dataUri\" border='0' style='width:50px' />
                        </a>
                    </td>
                    <td><h2>".NOME_APRESENTACAO."</h2></td>
                </tr>
                <tr>
                    <td colspan='2'>";
        if($this->DBG){ echo $headMail;}
        return $headMail;
    }

    private function getFooter(){
        if($this->DBG){ echo __FILE__.":".__LINE__." <br>\n".__CLASS__."->".__FUNCTION__; if(func_num_args()){ echo "<xmp>" .print_r(func_get_args(),1)."</xmp>";} echo "<br>\n"; }
        $footeMail = "
                        <br>Acesse o sistema aqui: <a href='".EMAIL_FROM_LINK."'>".EMAIL_FROM_NAME."</a>
                        <br><br>
                    </td>
                </tr>
                <tr>
                    <td colspan='2'>
                        Esta é uma mensagem automática, favor não responder.
                        <br>
                        Mensagem enviada em ".date('d/m/Y H:i').".
                    </td>
                </tr>
          </table>
       </body></html>";
       if($this->DBG){ echo $footeMail;}
       return $footeMail;
   }

    public function enviaEmail($destinatario,$titulo,$corpo,$anexo=null){

        $mail = new PHPMailer();
        $mail->IsSMTP(); // mandar via SMTP
        $mail->SMTPDebug = $this->DBG;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Host = RELAY_HOST; //"smtp.gmail.com";
        $mail->Port = 587;
        $mail->Username = RELAY_USER;
	    $mail->Password = RELAY_PASS;
        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->From = EMAIL_FROM_EMAIL;
        $mail->FromName = EMAIL_FROM_NAME;
        $mail->ClearAddresses();

        if($destinatario['EMAIL']){
            $mail->AddAddress( $destinatario['EMAIL'], $destinatario['NOME']);
        }else if(is_array($destinatario) and sizeof($destinatario) ){
            foreach ($destinatario as $key => $dest) {
                if( is_array($dest) ){
                    $mail->AddAddress( $dest['EMAIL'], $dest['NOME'] );
                }else if( $dest ){
                    $mail->AddAddress( $dest );
                }else{
                    return false;
                }
            }
        }else{
            debug($destinatario);
            return false;
        }

        $mail->Subject = NOME_APRESENTACAO." - $titulo";

        $headMail = $this->getHeader();
        $footeMail = $this->getFooter();

        //$mail->Body = $headMail.$corpo.$footeMail;
        $mail->msgHTML($headMail.$corpo.$footeMail);

        if($anexo){
            $uploadfile = tempnam(sys_get_temp_dir(), hash('sha256',$anexo['name']));
            if (copy($anexo['path'], $uploadfile)) {
                $mail->AddAttachment($uploadfile,$anexo['name']);
            }
        }

        $sent = $mail->Send();

        if( in_array($_SERVER['HTTP_USER'],array('Marlon','Developer','Desenvolvedor')) ){
            if (!$sent) debug($mail);
        }
        return $sent;
    }

    public function emailTeste($nome,$email){
        debug("$nome,$email",false,false);

        $destinatario = array(
            'EMAIL' => $email,
            'NOME' => $nome
        );

        $titulo = 'Teste de Envio';

        $corpo = "<h4>Teste:</h4>
                    <br>
                    Olá!<br>
                    Você definiu esse email: $email <br>
                    para ser seu email de contato do sistema ".NOME_APRESENTACAO.".<br>
                    <br>
                    <b>Email de teste</b>
                    ";
        //debug($destinatario);
        return _enviaEmail($destinatario,$titulo,$corpo);
    }
}
}