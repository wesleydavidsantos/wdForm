<?php
/**
 * Classe responsável por tratar o input do tipo FORM
*/
class RadioInput extends InputAbstract implements InputInterface {
    
    /**
     * Armazena os valores do Radio
     * @type Radio
    */
    private $radio;
    
    public $attributes_remove;
    
    /**
     * Construct necessário para chamar o método para montar os OPTIONS do SELECT
    */
    public function __construct( $input ){
        
        parent::__construct( $input );
        
        $this->attributes_remove = array( "checked", "value", "label", "placeholder" );
        
        $this->mount();
            
    }
    
    /**
     * Método usado para criar cada option com seus devidos valores
     * @return Void
    */
    private function mount(){
        
        try{
           
            $radio = $this->input->value;
       
            # Informa qual OPTION foi selecionado
            $checked = $this->input->checked;
            
            if( sizeof( $radio ) == 0 ){
            
                throw new Exception( 'Exception: Nenhum campo RADIO informado <strong>'.$this->input->name.'</strong>' );
            
            }else{
     
                foreach( $radio as $ra ){
                    
                    if( strpos( $ra, '|' ) !== false ){
                        
                        $inf_radio = explode( '|', $ra );
                        
                        $label = $inf_radio[0];
                        $value = $inf_radio[1];
                            
                    }else{
                        
                        $value = $ra;
                        $label = $ra;
                        
                    } 

                    $param['value'] = $value;
                    $param['label'] = $label;
                    if( $value == $checked ) $param['checked'] = '';
                    
                    $subType = new SubType( $this, $param, array('label')  );
                    $subType->setTypeStyleInput( 'input' );
                    $this->radio[] = $subType;
                    
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
   
        return implode( '', $this->radio );
        
    }
    
    /**
     * Método que retorna todos os objetos usados para criar o HTML do input RADIO
     * @return Object Radio
    */
    public function getObjSubInput(){
        return $this->radio;
    }

}

