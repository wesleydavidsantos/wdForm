<?php

require_once 'InputAbstract.php';
require_once 'InputInterface.php';
require_once 'types/sub_type/SubType.php';


/**
 * Classe principal responsável por manipular e gerar os INPUTS HTML
 *
 * @author Wesley David Santos
 * @link http://www.classmain.com
 * @package FormIt
 */
class HtmlInput {
    
    /**
     * Armazena a instância do objeto do tipo de qualquer Input criado
    */
    private $obj;
    
    /**
     * Recebe uma instancia da classe Input 
     * 
     * @author Wersley David Santos
     * @link http://www.classmain.com
     * @param Object Input
     * @return Input
     */
    public function __construct( $input ){

        try{
            
            $config = get_config_wdForm( 'types_inputs' );
            
            if( isset( $config[$input->type] ) ){
                
                $class = $config[$input->type];
                
                require_once( 'types/' . $class . '.php' );
                
                $this->obj = new $class( $input );
                
            }else{
                
                throw new Exception( 'Tipo de Input não encontrado no Config.php . <strong>'.$input->type.'</strong>' );
                
            }
            
        }catch( Exception $e ){
            echo $e->getMessage();
        }
    }
    
    /**
     * Realiza a chamada dos métodos usados pelo INPUT
    */
    public function __call($method, $args)
    {
        try{
            if (!method_exists($this->obj, $method)) {
                throw new Exception("Exception classe HtmlInput: Método não existe InputAbstract. [$method]");
            }
            
            return call_user_func_array(
                array($this->obj, $method), $args
            );

        }catch( Exception $e ){
            
            echo $e->getMessage();
            
        }
                    
    }
    
    /**
     * Método mágico que retorna o HTML do INPUT
    */
    public function __toString(){
        return $this->obj->__toString();
    }
    
    public function __get( $attribute ){ 
        return $this->obj->$attribute;
    }
    
    public function __set( $action, $value ){
        $this->obj->$action = $value;
        return $this;
    }
    
}