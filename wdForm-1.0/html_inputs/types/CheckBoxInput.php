<?php
/**
 * Classe responsável por tratar o input do tipo FORM
*/
class CheckBoxInput extends InputAbstract implements InputInterface {
    
    /**
     * Armazena os valores dos CHECKBOX
    */
    private $checkbox;
    
    /**
     * Atributos que deverão ser removidos do INPUT
    */
    public $attributes_remove;
    
    /**
     * Construct necessário para chamar o método para montar os OPTIONS do SELECT
    */
    public function __construct( $input ){
        
        parent::__construct( $input );
        
        # Adiciona o colchetes para aceitar vários selecões no checkbox
        $this->input->name = $this->input->name . '[]';
        
        $this->attributes_remove = array( "checked", "value", "label", "placeholder", "required" );
        
        $this->mount();
            
    }
    
    /**
     * Método usado para criar cada option com seus devidos valores
     * @return Void
    */
    private function mount(){
        
        try{
            
            $checkbox = $this->input->value;
            
            # Informa qual OPTION foi selecionado
            $checked = $this->input->checked;
            
            if( sizeof( $checkbox ) == 0 ){
            
                throw new Exception( 'Exception: Nenhum campo CHECKBOX informado <strong>'.$this->input->name.'</strong>' );
            
            }else{
                
                foreach( $checkbox as $check ){
                    
                    if( strpos( $check, '|' ) !== false ){
                        
                        $inf_check = explode( '|', $check );
                        
                        $label = $inf_check[0];
                        $value = $inf_check[1];
                            
                    }else{
                        
                        $value = $check;
                        $label = $check;
                        
                    } 
                    
                    $param['value'] = $value;
                    $param['label'] = $label;
                    
                    if( is_array( $checked ) && in_array( $value, $checked ) ) $param['checked'] = $value;
                    
                    $subType = new SubType( $this, $param, array('label') );
                    $subType->setTypeStyleInput( 'input' );
                    $this->checkbox[] = $subType;
                    
                    # Excluí todos os parâmetros anteriores
                    unset( $param );
                    
                }
        
            }
            
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
   
        return implode( '', $this->checkbox );
        
    }
    
    /**
     * Método que retorna todos os objetos usados para criar o HTML do input CHECKBOX
     * @return Object CheckBox
    */
    public function getObjSubInput(){
        return $this->checkbox;
    }

}
