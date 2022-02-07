<?php

//debug($_SESSION);
if(!$noSec){
    if(!$_SESSION){
        $fail = true;
        $msg='Sessão encerrada.';
    }else if(!$_SESSION[$appname]['user']){
        $fail = true;
        $msg='Sessão encerrada. Efetue login novamente';
    }else if($_SESSION[$appname]['user']['excluido']){
        $fail = true;
        $msg='Usuário excluído';
    }
}

if(isset($fail)){
    //unset($_SESSION[$appname]);
    $msg=urlencode($msg);
    if(!headers_sent()) header("Location: $caminho/login.php?sc=1&msg=$msg");
    echo "<script>location.href='$caminho/login.php?sc=1&msg=$msg';</script>";
    exit();
}

if(!isset($_SESSION[$appname]['PERM']) and isset($_SESSION[$appname]['user']['permissoes'])){
    $_SESSION[$appname]['PERM'] = $Usuario->getPermissoesByStr($_SESSION[$appname]['user']['permissoes']);
}
/**/
