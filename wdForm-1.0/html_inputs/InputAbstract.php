<?php
/**
 * Classe respons�vel por validar e instanciar o tipo correto de INPUT
*/
abstract class InputAbstract{

    /**
     * Armazena os dados do input
    */
    protected $input;
    
    /**
     * Informa os tipos de atributos que n�o podem conter no HTML input
    */
    protected $attributes_remove;
    
    /**
     * Recebe o HTML do input formatado
    */
    protected $input_htm;

    /**
     * Recebe a instancia de um Input
     * @param $input Type|Input > Inst�ncia da classe Input
     * @param $attributes_remove Type|Array > Atributos que devem ser removidos do INPUT HTML a ser gerado
    */
    public function __construct( Input $input ){
        $this->input = $input;
        $this->attributes_remove = array();
    }
    
    public function __set( $action, $value ){
        $this->input->$action = $value;
        return $this;
    }
    
    /**
     * Adiciona de forma din�mica os attributos que devem ser removidos do input
     * @param String
    */
    public function setAttributeRemove( $attribute ){
        $this->attributes_remove[] = $attribute;
    }
    
    /**
     * Realiza a valida��o dos novos INPUTS
    */
    public function valid(){
        $this->input->valid();
    }
    
    /**
     * Retorna o valor do Action
     * @return Mixed
    */
    public function __get( $action ){
        return $this->input->$action;        
    }
    
    
    /**
     * M�todo respons�vel por validar os atributos e atualizar os atributos din�micos
     * Os atributos din�micos s�o reconhecidos pelo car�cter '%' no inicio, desta forma o valor
     * � substituido pelo valor que cont�m no outro atributo.
     * @Ex 
     * 
     * $form->name = 'email';
     * $form->id = '%name';
     * 
     * Ao executar o id fica com o seguinte valor que consta no atributo 'name'
     * 
     * $form->id == 'email';
     * 
    */
    private function checkAttributes(){

        try {

            $list_attributes = $this->input->getNamesAttributes();

            foreach( $list_attributes as $attribute ){
               
               $name_attribute = $this->input->$attribute; 
                
               if( !is_array($name_attribute) && strpos( $name_attribute, '%' ) !== false ){

                    $attribute_required = str_replace( '%', '', $name_attribute ); 

                    $this->input->$attribute = $this->input->$attribute_required;

               }

            }
            
        }catch( Exception $e ){
            echo $e->getMessage();
        }

    } 
 
    /**
     * M�todo respons�vel por retornar
     * @param $attributes_remove Type|Array > Nome dos atributos que n�o devem constar nos INPUTS HTML
    */
    public function mountAttributes( $attributes_remove=array() ){

        try {

            $new_input = array();
            $list_attributes = $this->input->getNamesAttributes();
            $attributes_remove = array_merge( $attributes_remove, $this->attributes_remove, get_config_wdForm( 'attributes_remove' ) );
            $this->checkAttributes();

            foreach( $list_attributes as $attribute ){

                if( in_array( $attribute, $attributes_remove ) ) continue;

                $new_input[] = $attribute . '="' . $this->input->$attribute . '"';

            }

            return implode( ' ', $new_input );            

        }catch( Exception $e ){
            echo "Erro ao montar os atributos. <br /> Class ". get_class( $this ) ." <br />" . $e->getMessage();
        } 

    }

    abstract public function getObjSubInput();
    abstract public function __toString();

}