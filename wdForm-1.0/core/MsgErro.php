<?php
interface MsgErroInterface{
    
    public function __construct();
    public function setMsg( $msg );
    public function getErro();
    public function __toString();
        
}


/**
 * Classe responsável por armazenar as mensagens de erro
*/
class MsgErro implements MsgErroInterface{

    /**
     * Armazena a mensagem de erro
    */
    private $msg;
    
    /**
     * Inicializa os atributos da classe
    */
    public function __construct(){
        
        $this->msg = array();
            
    }
    
    /**
     * Informa se o INPUT contem erros
     * @return Boolean
    */
    public function checked(){
        return sizeof( $this->msg ) > 0 ? true : false;
    }
    
    /**
     * Cadastra as mensagens de erro encontradas
     * @param $msg String
    */
    public function setMsg( $msg ){
        
        if( $msg == '' ) throw new Exception ('Exeption: Mensagem de erro não informada. MsgErro ');
            
        if( !in_array( $msg, $this->msg ) ) $this->msg[] = $msg;
            
        return $this;
        
    }

    /**
     * Retorna as mensagens de erro encontradas no INPUT
     * @return Array
    */
    public function getErro(){
        return $this->msg;
    }
    
    /**
     * Retorna o erro em formato de String
     * @return String
    */
    public function __toString(){
        return implode( '<br />', $this->getErro() );
    }
    
}