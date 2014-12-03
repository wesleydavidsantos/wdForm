<?php

require_once 'AttributesAbstract.php';
require_once 'types_required/RequiredInterface.php';


/**
 * Classe que armazena os atributos de cada input
*/
class Attributes extends AttributesAbstract {

    /**
     * Armazena o nome da a��o
     * @type String
    */
    private $action;
    
    /**
     * Armazena o valor da a��o
     * @type String
    */
    private $value;
    
    
    /**
     * Recebe a a��o e o seu valor
    */
    public function __construct( $action, $value ){
        $this->action = $action;
        $this->value = $value;
    } 
    
    /**
     * Verifica o tipo de a��o e gera o Object relacionado a a��o
    */
    public function __set( $action, $value ){
        
        $this->action = $action;
        $this->value = $value;

    }
    
    /**
     * Retorna o valor de uma a��o
    */
    public function getValue(){
        return $this->value;
    } 

}