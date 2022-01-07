<?php
/*!
* @Autor MV@URBS https://orcid.org/0000-0003-2770-0624
* @version 0.0.0.3 [2021-dez-14]
* @copyleft GPLv3
*/

if(!function_exists('ldap_connect')){
    echo "Não tem LDAP instalado no PHP"; die;
}

/**
*
*   $ldap->logar($usuario,$senha)
*
*
*   $ldap->getGrupos()
*
*/

if (!class_exists('LDAP_CON')) {
    class LDAP_CON {

        var $bkp_username = 'helpdesk';
        var $bkp_senha = 'helpdesk';

        var $ldap_server = "172.16.250.5";
        var $dominio = "@URBS"; //Dominio local ou global
        var $ldap_porta = "389";
        var $ldap_base_dn = 'dc=urbs,dc=curitiba,dc=pr,dc=gov,dc=br';
        var $ldapcon;

        var $logged = null;
        var $user = null;
        var $erro = null;

        private function  __construct($username,$password,$server,$domain,$baseDN){
            if(!$username or !$password or !$server){
                echo "Faltam dados de conexão para logar no LDAP";
                die;
            }

            $this->bkp_username = $username;
            $this->bkp_senha = $password;

            if(strpos($server,':') > -1){
                $arr = explode(':',$server);
                $this->ldap_server = $arr[0];
                $this->ldap_porta = $arr[1];
            }else{
                $this->ldap_server = $server;
            }

            $this->dominio = $domain;
            $this->ldap_base_dn = $baseDN;


            $this->ldapcon = ldap_connect($this->ldap_server, $this->ldap_porta);
            ldap_set_option($this->ldapcon, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($this->ldapcon, LDAP_OPT_REFERRALS, 0); // We need this for doing an LDAP search.

            if (!$this->ldapcon){
                trigger_error("Erro ao conectar ao servidor usando a extensão LDAP");
                $this->erro = "Servidor não deixou conectar";
            }
        }

        public static function singleton(){
            if (!isset(self::$instance)) {
                $c = __CLASS__;
                self::$instance = new $c;
            }

            return self::$instance;
        }

        public function  __destruct(){
            ldap_unbind($this->ldapcon);
        }

        //Get standard users and contacts
        //$search_filter = '(|(objectCategory=person)(objectCategory=contact)(dc=mavalerio))';


        public function logar($usuario,$senha){
            if(!$usuario) return false;
            if(!$senha) return false;

            $this->logged = false;
            $this->user = null;
            $this->erro = null;

            $search_filter = "samaccountname=$usuario";

            $userAndDomain = $usuario.$this->dominio;
            //tenta logar com o usuario e senha
            $bind = @ldap_bind($this->ldapcon, $userAndDomain, $senha);

            if($bind){
                //deu certo, trazer informacoes do usuario
                $result = ldap_search($this->ldapcon, $this->ldap_base_dn, $search_filter);
                $info = ldap_get_entries($this->ldapcon, $result);

                $this->logged = true;
                $this->user = $info[0];

                return true;

                //echo "<pre>";
                //print_r($info[0]['memberof']); die;
            }else{
                $userAndDomain = $this->bkp_username.$this->dominio;

                $bind = @ldap_bind($this->ldapcon, $userAndDomain, $this->bkp_senha);
                if ($bind) {
                    $result = ldap_search($this->ldapcon, $this->ldap_base_dn, $search_filter);

                    if (ldap_count_entries($this->ldapcon, $result) == 0){
                        $this->erro = "Usuario $usuario nao encontrado";
                        //echo "The entire directory was searched for (uid=" . $usuario . ") but no entry was found.<br />";
                    }else if (ldap_count_entries($this->ldapcon, $result) == 1){
                        $this->erro = "Senha errada";
                        //echo "The entire directory was searched for (uid=" . $usuario . ") and one entry was found.<br />";
                    }
                }else{
                    $this->erro = "Erro na biblioteca LDAP: ";
                    if (ldap_get_option($this->ldapcon, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error)) {
                        $this->erro .= $extended_error;
                        //echo "Error Binding to LDAP: $extended_error";
                    } else {
                        $this->erro .= "No info";
                        //echo "Error Binding to LDAP: No additional information is available.";
                    }
                }
            }
            return false;
        }

        public function getGrupos(){
            if(!$this->logged) return false;
            if($this->user['grupos']) return $this->user['grupos'];

            $info = $this->user['memberof'];
            unset($info['count']);

            $grupos = array();
            foreach($info as $strGroup){
                $_str = explode(',',$strGroup);
                foreach($_str as $item){
                    if(substr($item,0,3) == 'CN='){
                        $grupos[] = substr($item,3);
                    }
                }
            }
            $this->user['grupos'] = $grupos;
            return $grupos;
        }
    }
}