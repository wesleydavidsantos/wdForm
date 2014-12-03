<?php
/**
 * Classe de validação para objetos
*/
class RequiredObject implements RequiredInterface{

    /**
     * Mensagens default de erro
     * @var [type]
     */
    private $default_msg_erro;

    public function __construct( Input $input ){

        $this->default_msg_erro = get_msg_default_class('RequiredObject');

        $object = $input->object;

        if( sizeof( $object ) > 0 ){

            foreach( $object as $key => $value ){

                # Verifica se o usuário repassou a mensagem de erro a ser exibida, senão usa a padrão do sistema
                if( is_string( $key ) ){ 
                    $obj = $key;
                    $msg_erro = $value; 
                }else{
                    $obj = $value;
                    $msg_erro = $this->default_msg_erro;
                }

                if( strpos( $obj, '->' ) !== false ){

                    $obj = explode( '->', $obj );
                    $class = $obj[0];
                    $method = $obj[1];
                    
                    unset( $obj );
                    
                    # Instância a classe de validação
                    $obj = new $class();
                    if( !$obj->$method( $input->value ) ){ # Verifica se algum erro foi encontrado    
                                        
                        $input->setErro(  sprintf( $msg_erro, $input->label ) );                        
                    }
                        

                }else{

                    if( strpos( $obj, '::' ) !== false ){
                        $obj = explode( '::', $obj );
                        $class = $obj[0];
                        $method = $obj[1];
                        
                        unset( $obj );
                        
                        if( !$class::$method( $input->value ) ){ # Verifica se algum erro foi encontrado
                        
                            $input->setErro( sprintf( $msg_erro, $input->label ) );
                        }
                        
                    }

                }
                
            }

        }

    }

}