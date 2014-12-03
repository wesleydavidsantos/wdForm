<?php
/**
 * Classe abstract Input, armazena as informações de únicas e obrigatórias de cada tipo de input
*/
class Input{
    
    /**
     * Mensagens default de erro
     * @var [type]
     */
    private $default_msg_erro;

    /**
     * Armazena todos os atributos de um INPUT
     * @type Array
    */
    private $attributes = array();
    
    /**
     * Armazena as mensagens de erro geradas
    */
    private $erros;
    
    /**
     * Recebe o ação e o valor que ela contém
     * @param $action a ser cadastrada
     * @package $value valor que irá constar dentro de $action
    */
    public function __construct( $action, $value ){

        $this->default_msg_erro = get_msg_default_class('input');
        $this->$action = $value;
        $this->erros = new MsgErro();   

    }
    
    /**
     * Método usado para armazenar as mensagens de erro
    */
    public function setErro( $msg ){
        $this->erros->setMsg( $msg ); 
    }
    
    /**
     * Retorna os erros encontrados no INPUT
     * @return Mixed String | Array
    */
    public function getErro(){
        return $this->erros;
    }
    
    /**
     * Método responsável por verificar se o INPUT é obrigatório e se sim retornar a mensagem de erro gerada
     * @return
    */
    private function required(){
        
        # Verifica se a requisição required existe no input
        if( isset( $this->attributes['required'] ) ){
            # Required existe então válida INPUT 
            new RequiredBasic( $this );
        }
        
        # Verifica se a requisição required existe no input
        if( isset( $this->attributes['object'] ) ){
            # Required existe então válida INPUT 
            new RequiredObject( $this );
        }
        
        # Verifica se existem arquivos para Upload e se eles são válidos
        if( $this->type == 'file' ){
            # File existe então válida INPUT 
            new RequiredUpload( $this );
        }
      
    }
    
    /**
     * Método responsável por formatar os valores informados
    */
    private function formatValue(){
        
        # Os inputs SELECT - CHECKBOX - RADIO não precisam de formatação, pois são inputs de escolha sem necessidade de ter interferência do usuário no valor
        if( !in_array( $this->type, array( 'select', 'checkbox', 'radio' ) ) ){
            
            if( is_array( $this->format ) ){
                
                if( sizeof( $this->format ) > 0 ){
                    
                    foreach( $this->format as $format ){
                        
                        $function = 'wd_'.$format;
                        if( function_exists( $function ) ) 
                            $this->value = $function( $this->value );
                        else
                            throw new Exception ('Exeption: Funcão para formatação não encontrada');
                        
                    }
                    
                }
                
            }
        
        }
        
    }

    /**
     * Verifica se um valor é válido, usado especificamente para INPUTS de escolha. SELECT - CHECKBOX - RADIO
     * @author Wesley David Santos
     * @link   http://www.classmain.com
     * @param $value_chosen - Valor escolhido pelo usuário
     * @return [type] [description]
     */
    private function valueIsValidInputChoice(){

        # Verifica o tipo de input para pegar o valor a ser validado
        switch ( $this->type ) {
                    case 'checkbox':
                    case 'radio':
                                    $value_chosen = $this->checked;   
                    break;

                    case 'select':
                                    $value_chosen = $this->selected;
                    break;

                    default: return;
        }

        # Pega a lista de valores válidos para uso
        $values_valid = $this->value;

        if( is_array( $values_valid ) ){

            $list_values = array();

            foreach ($values_valid as $v ) {
                
                if( strpos($v, '|') !== false ){
                    $v = explode('|', $v);
                    $list_values[] = $v[1];
                }else{
                    $list_values[] = $v;
                }

            }

            if( is_array( $value_chosen ) ){
                foreach ($value_chosen as $vc) {
                    if( !in_array( $vc , $list_values) ) $this->setErro( sprintf( $this->default_msg_erro['valueIsValidInputChoice'], $this->label ) );
                }
            }else{
                if( !in_array( $value_chosen , $list_values) ) $this->setErro( sprintf( $this->default_msg_erro['valueIsValidInputChoice'], $this->label ) );
            }

        }

    }
    
    /**
     * Método responsável por validar os valores informados
    */
    private function validateValue(){
        
        if( is_array( $this->validate ) ){
            
            if( sizeof( $this->validate ) > 0 ){

                # Verifica o tipo de input para pegar o valor a ser validado
                switch ( $this->type ) {

                    case 'form':
                    case 'submit':
                    case 'button':break;

                    case 'checkbox':
                    case 'radio':
                                    $value = $this->checked;    
                    break;

                    case 'select':
                                    $value = $this->selected;                                    
                    break;
                    
                    default: $value = $this->value;
                }

                foreach( $this->validate as $key => $validate ){

                    # Recebe a mensagem de erro padrão em cada loop
                    $msg_erro =sprintf( $this->default_msg_erro['validateValue'], $this->label );

                    # Verifica se existe uma mensagem de erro especial a ser usada
                    if( is_string( $key ) ){
                        $msg_erro = $validate;
                        $validate = $key;
                    }
                    
                    $function = 'wd_'.$validate;
                    if( function_exists( $function ) ){ 

                        # Se o valor for um array cada posição do array é validada
                        if( is_array( $value ) ){
                            
                            foreach ($value as $v ) {
                                
                                if( ! $function( $v ) ){
                                    $this->setErro( $msg_erro );
                                }

                            }

                        }else{

                            if( ! $function( $value ) ){
                                $this->setErro( $msg_erro );
                            }

                        }

                    }else{
                         throw new Exception ('Exeption: Funcão para validação não encontrada - ' . $function);
                    }
                    
                }
                
            }
            
        }
        
    }
    
    /**
     * Método responsável por validar os valores do INPUT
     * @return Boolean 
     * @op True > Input válidado com sucesso
     * @op False > Input contém erros
    */
    public function valid(){
        
        # Primeiro verifica se o formulário foi submetido
        if( Submit::$checkRequestSubmit ){

            # Verifica se o valores são validos para os INPUTS de escolha
            $this->valueIsValidInputChoice();

            if( $this->value != '' ){
                
                # Valida os valores dos INPUTS
                $this->validateValue();
               
                # Formata os valores dos INPUTS
                if( ! $this->erros->checked() ) $this->formatValue();
            }
            
            # Verifica os campos obrigatórios
            $this->required();
            
            # Informa se foram encontrados erros
            return !$this->erros->checked();
        
        }
        
    }

    /**
     * Recebe os atributos de cada input
     * @var $action > Ação do atributo
     * @var $value > Valor a ser adicionado a action
    */
    public function __set( $action, $value ){
        
        # Valor default para o atributo unique
        if( $action == 'unique' ) $value = TRUE;
        
        if( !isset( $this->attributes[ $action ] ) ){ 
            $this->attributes[ $action ] = new Attributes( $action, $value );
        }else{
            $this->attributes[ $action ]->$action = $value;
        }

    }

    /**
     * Retorna o valor de qualquer action
    */
    public function __get( $action ){
    
        try {
            
            if( $action == 'erro' ){
              
                return $this->getErro();
    
            }else{
                
                if( isset( $this->attributes[ $action ] ) ){
       
                    return $this->attributes[ $action ]->getValue();            
    
                }else{
    
                    /**
                     * A action value é especial, se ela não existir é considerado vazio
                    */
                    if( $action == 'value' )
                        return '';
                        
                        //throw new Exception("Exception: Action <strong>".$action."</strong> não encontrado dentro da classe " . get_class($this) . "<br />" );
    
                }
            
            }
            
            return '';

        } catch(Exception $e) {
            echo $e->getMessage();
        } 

    }

    /**
     * Método que retorna os nomes dos atributos
     * @return Array
    */
    public function getNamesAttributes(){
 
        $list_name = array();
        if( sizeof( $this->attributes ) > 0 ){

            foreach( $this->attributes as $name => $value ) $list_name[] = $name;

        }

        return $list_name;

    }

}