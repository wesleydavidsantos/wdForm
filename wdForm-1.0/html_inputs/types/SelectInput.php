<?php
/**
 * Classe responsável por tratar o input do tipo FORM
*/
class SelectInput extends InputAbstract implements InputInterface {
    
    /**
     * Armazena os valores dos OPTIONS do SELECT
    */
    private $options;
    
    /**
     * Construct necessário para chamar o método para montar os OPTIONS do SELECT
    */
    public function __construct( $input ){
        
        parent::__construct( $input );
        $this->mount();
            
    }
    
    /**
     * Método usado para criar cada option com seus devidos valores
     * @return Void
    */
    private function mount(){
        
        try{
            
            $option = $this->input->value;
            
            # Informa qual OPTION foi selecionado
            $selected = $this->input->selected;
            
            if( !is_array( $option ) ){
            
                throw new Exception( 'Exception: Nenhum OPTION repassado para o SELECT <strong>'.$this->input->name.'</strong>' );
            
            }else{
                
                if( sizeof( $option ) > 0  ){
                    
                    foreach( $option as $op ){
                        
                        if( strpos( $op, '|' ) !== false ){
                            
                            $inf_option = explode( '|', $op );
                            
                            $label = $inf_option[0];
                            $value = $inf_option[1];
                                
                        }else{
                            
                            $value = $op;
                            $label = $op;
                            
                        } 
                        
                        $param['value'] = $value;
                        $param['label'] = $label;
                        if( $value == $selected ) $param['selected'] = '';
                        
                        $subType = new SubType( $this, $param );
                        $subType->setTypeStyleInput( 'option' );
                        $this->options[] = $subType;
                        
                        
                        # Excluí todos os parâmetros anteriores
                        unset( $param );
                        
                    }
                    
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
        
        $remove = array( 'selected', 'value', 'type', 'placeholder', 'label' );
        $html[] = "<select ".$this->mountAttributes( $remove )." >";
        $html[] = implode( '', $this->options );
        $html[] = "</select>";
        
        return implode( '', $html );
        
    }
    
    /**
     * Método que retorna todos os objetos usados para criar o HTML do input SELECT
     * @return Object Option
    */
    public function getObjSubInput(){
        return $this->__toString();
    }

}