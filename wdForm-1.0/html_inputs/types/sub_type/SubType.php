<?php
/**
 * Classe Radio responsável por armazenar cada input de escolha do usuário
*/
class SubType{
    
    /**
     * Input PAI
     * @type Input
    */
    private $input;

    /**
     * Verifica se existe um label para o INPUT
     * @var String
     */
    private $label_input;
    
    /**
     * Informa os atributos básicos para cada Type
     * @type Array
    */
    private $new_attributes;
    
    /**
     * Informa os atributos que não devem fazer parte do INPUT
     * @type Array
    */
    private $remove_option;
    
    /**
     * Informa qual o estilo para apresentar o INPUT
    */
    private $typeStyleInput;
        
    /**
     * Construtor
     * @param $input > Contém a clase PAI do INPUT
     * @param $new_attributes > Novos atributos que devem ser adicionados a classe
     * @param $remove_input > Atributos que não devem ser adicionados ao INPUT
    */
    public function __construct( InputInterface $input, $new_attributes=array(), $remove_input=array() ){

        $this->input = $input;
        $this->new_attributes = array();
        $this->label_input = '';
        $this->remove_option = $remove_input;
        
        if( sizeof( $new_attributes ) > 0 ){
            foreach( $new_attributes as $name => $value ){
                $this->new_attributes[ $name ] = $value; 
            }
        }
        
    } 
    
    /**
     * Informa o tipo de estilo de input a ser apresentado
     * @Ex input | option 
    */
    public function setTypeStyleInput( $type ){
        
        if( $type == 'input' || $type == 'option' ) $this->typeStyleInput = $type;
        
    }
    
    private function getNewAttributes(){
        
        $list_attributes = array();
        foreach( $this->new_attributes as $name => $value ){

            # Pega o valor do label especifico para o INPUT, usado preferêncialmente em CHECKBOX e RADIO
            if( in_array( $name, $this->remove_option ) ){
                $this->label_input = $value;
                continue;
            }
            
            if( $value == '' )
                $list_attributes[] = $name;
            else
                $list_attributes[] = $name . '="' . $value . '"';
        }
            
        return implode( ' ', $list_attributes );
         
    }
    
    public function __set( $action, $value ){
        $this->input->$action = $value; 
    }
    
    /**
     * Retorna os atributos dinâmicos da classe
     * Primeiro verifica se é um atributo desta classe, senão encontrar procura na classe PAI senão retorna vazio
     * @return String
    */
    public function __get( $type ){
        
        if( $type == 'input' ){
            
            return $this->__toString();
        }else{
            
            if( isset( $this->new_attributes[ $type ] ) )
                return $this->new_attributes[ $type ];
            else
                return isset( $this->input->$type ) ? $this->input->$type : '';     
            
        }
        
        return '';
        
    }
    
    /**
     * Retorna o OPTION no formato HTML
    */
    public function __toString(){
        
        if( $this->typeStyleInput == 'option' ){
            return "<option ".$this->getNewAttributes().">" . $this->label . "</option>";
        }else{

            if( empty( $this->label_input ) )
                return "<input " . $this->input->mountAttributes( $this->input->attributes_remove ) . $this->getNewAttributes() . " /> " . $this->label_input;
            else
                return "<input " . $this->input->mountAttributes( $this->input->attributes_remove ) . $this->getNewAttributes() . " /> ";

        }
            
    }
}