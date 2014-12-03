<?php

require_once 'AttributesAbstract.php';
require_once 'types_required/RequiredInterface.php';


/**
 * Classe que armazena os atributos de cada input
*/
class Attributes extends AttributesAbstract {

    /**
     * Armazena o nome da ação
     * @type String
    */
    private $action;
    
    /**
     * Armazena o valor da ação
     * @type String
    */
    private $value;
    
    
    /**
     * Recebe a ação e o seu valor
    */
    public function __construct( $action, $value ){
        $this->action = $action;
        $this->value = $value;
    } 
    
    /**
     * Verifica o tipo de ação e gera o Object relacionado a ação
    */
    public function __set( $action, $value ){
        
        $this->action = $action;
        $this->value = $value;

    }
    
    /**
     * Retorna o valor de uma ação
    */
    public function getValue(){
        return $this->value;
    } 

}