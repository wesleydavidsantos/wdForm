<?php

require_once 'DmlInterface.php';
require_once 'DmlAbstract.php';

/**
 * Classe principal para realização de instruções DML
 * # INSERT
 * # UPDATE
*/
class wdDB{
    
    /**
     * Armazena a objeto criado para executar a DML
    */
    private $obj;
    
    /**
     * Armazena o modelo de banco de dados a ser usado
     * @type MODEL
    */
    private $model;
    
    /**
     * Armazena o FORM a ser usado
     * @type wdForm
    */
    private $form;
    
    /**
     * Construtor
     * @param $form wdForm
    */
    public function __construct( wdForm $form ){
        
        $this->form = $form;
        
        # @default
        $this->setFramework( 'PhpActiveRecord' );
    }
    
    /**
     * Classe Model do banco de dados
    */
    public function setModel( $model ){ 
        $this->obj->setModel( $model );
        return $this; 
    }
    
    /**
     * Framework a ser usado
    */
    public function setFramework( $framework ){ 
        
        # Caminho até o arquivo do framework
        $file = WDFORM_INCLUDE_PATH . DIRECTORY_SEPARATOR . 'lib_dml' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . $framework . '.php';
        
        # Verifica se o arquivo do framework existe
        if( !file_exists( $file ) ){
             throw new Exception ( 'Não foi encontrado o arquivo do framework responsável pelo INSERT e UPDATE ' );
        }
                    
        require_once $file;
                                
        $this->obj = new $framework( $this->form );        
        
        return $this;  
        
    }
    
    /**
     * Insere novos registros
     * @return Boolean
    */
    public function insert(){
        return $this->obj->insert();
    }
    
    /**
     * Altera um registro
     * @return Boolean
    */
    public function update( $id ){
        return $this->obj->update( $id );
    }
    
    /**
     * Retorna o OBJETO usado pelo DML
     * @return Object $framework
    */
    public function getObj(){
        return $this->obj->getObj();
    }
    
    /**
     * Método responsável por retornar os erros encontrados na instrução DML
     * @return String
    */
    public function getErro(){
        
        $list_erro = $this->obj->getErro();
        
        if( sizeof( $list_erro ) > 0 ){
            
            if( sizeof( $list_erro ) > 1 ){
                $erro = '<ul><li>' . implode( '<li>', $list_erro ) . '</li></ul>';
            }else{
                if( sizeof( $list_erro ) ==  1 ) $erro = '<ul><li>' . implode( '', $list_erro ) . '</li></ul>';
            }
            
            return $erro;
            
        }
        
        return '';
        
    }
    
    /**
     * Popular um formulário com os dados do banco de dados
     * @param Identificador único do registro 
    */
    public function populateForm( $id ){
        $this->obj->populateForm( $id );
    }
    
}