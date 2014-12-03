<?php
/**
 * Classe responsável por verificar e validar os dados do formulário quando o submit for executado
*/
class Submit{

    /**
     * Armazena o objeto de FORM
    */
    private $form;

    /**
     * Informa se um submit foi enviado
     * @type Boolean
    */
    public static $checkRequestSubmit;

    /**
     * Construtor
    */
    public function __construct( wdForm $form ){
        $this->form = $form;
        Submit::$checkRequestSubmit = false;
    }

    /**
     * Método responsável por retornar valores vindos por POST
     * @var $param > Nome do POST
     * @return Mixed
    */
    private function post( $param ){
  
        $value = isset( $_POST[ $param ] ) ? $_POST[ $param ] : '';

        if( !is_array( $value ) ){

            if ( get_magic_quotes_gpc() )
    			$value = htmlspecialchars( stripslashes((string)$value) );
    		else
    			$value = htmlspecialchars( (string)$value );

        }

        return $value;

    }

    public function check(){

        if( isset( $_POST[ $this->form->validform_name ] ) ){

            try{
                
                # Informa que o submit foi requisitado
                Submit::$checkRequestSubmit = true;
    
                $list_names_input = $this->form->getAllNamesInput();
                
                $list_inputs = $this->form->getAllInputs();
                
                # Informa que o submit ocorreu sem problemas
                $submit_valid = true;
            
                foreach( $list_inputs as $name => $input ){
        
                    if( !in_array( $name, $list_names_input ) ) continue; 
               
                    if( $name == 'form' || $name == 'validform' || $name == 'button' || $name == 'submit' ) continue;
                    
                    $type_input = $name.'_type';
                    
                    # Verifica qual o tipo de INPUT pois eles são tratado de formas diferentes
                    switch( $this->form->$type_input ){
                        
                        case 'select': $selected = $name . "_selected";  
                                       $this->form->$selected = $this->post( $name );
                        break;    
                        
                        case 'radio':  
                        case 'checkbox':
                                         $checked = $name . "_checked";  
                                         $this->form->$checked = $this->post( $name );
                                     
                        break;
                        
                        case 'file':
                        break;
                        
                        default: $this->form->$name = $this->post( $name );
                        
                    }
           
                    /**
                     * Verifica se os INPUT'S foram aprovados
                    */
                    if( !$input->valid() ) $submit_valid = false;


                }
                
                return $submit_valid; 

            }catch( Exception $e ){

                echo "Exception: Erro POST " . $e->getMessage() . "<br />";

            }

        }

    }
    

}