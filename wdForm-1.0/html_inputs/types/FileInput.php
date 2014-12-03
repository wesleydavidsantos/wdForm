<?php
/**
 * Classe responsável por tratar os inputs do tipo FILE
*/
class FileInput extends InputAbstract implements InputInterface {
    
    /**
     * Armazena os INPUTS FILE existentes
    */
    private $file;
    
    /**
     * Informa os atributos que deverão ser retirados do INPUT na hora de gerar o HTML
    */
    public $attributes_remove;
    
    /**
     * Construct necessário para chamar o método para montar os OPTIONS do SELECT
    */
    public function __construct( $input ){
        
        parent::__construct( $input );
        
        # Adiciona o colchetes para aceitar vários selecões no checkbox
        $this->input->name = $this->input->name . '[]';
        
        $this->attributes_remove = array( "count", "maxsize", "minsize", "dir", "checked", "label", "placeholder", "countrequired", "listfiles" );
        
        $this->mount();
            
    }
    
    /**
     * Método usado para criar cada file com seus devidos valores
     * @return Void
    */
    private function mount(){
        
        try{
            
            $remove = array( "checked", "dir", "maxsize", "minsize", "count" );
            $param['attributes'] = $this->mountAttributes( $remove );
            
            $count = $this->input->count;
            if( !is_numeric( $count ) || $count < 1 ) $count = 1;
             
            for( $x=0; $x < $count; $x++ ) $this->file[] = new SubType( $this );
            
        }catch( Exception $e ){
            echo $e->getMessage();
        }
        
    } 
    
    /**
     * Retorna o INPUT no formato de HTML
     * 
     * @author Wersley David Santos
     * @link http://www.classmain.com
     * @param string $input
     * @return Html
     */
    public function __toString(){
        
        return implode( '', $this->file );
    }
    
    /**
     * Método que retorna todos os objetos usados para criar o HTML do input FILE
     * @return Object File
    */
    public function getObjSubInput(){
        return $this->file;
    }
    
}