<?php
/**
 * Armazena o atributo especial Required que informa quando um input é obrigatório
*/
class RequiredBasic implements RequiredInterface{

    /**
     * Mensagens default de erro
     * @var [type]
     */
    private $default_msg_erro;
    
    public function __construct( Input $input ){

        $this->default_msg_erro = get_msg_default_class('RequiredBasic');
        
        $msg = '';
        
        switch( $input->type ){
            
            case 'text': if( $input->value == '' ){
                            # Verifica se o usuário informou a mensagem de erro, senão usa a padrão do sistema
                            if( $input->required == '' )
                                $msg = sprintf( $this->default_msg_erro, $input->label );
                            else
                                $msg = $input->required;
                         }
            break;
            
            case 'select': if( $input->selected == '' ){
                                # Verifica se o usuário informou a mensagem de erro, senão usa a padrão do sistema
                                if( $input->required == '' )
                                    $msg = sprintf( $this->default_msg_erro, $input->label );
                                else
                                    $msg = $input->required;
                            }
            break;
            
            case 'radio':
            case 'checkbox': if( $input->checked == '' ){
                                # Verifica se o usuário informou a mensagem de erro, senão usa a padrão do sistema
                                if( $input->required == '' )
                                    $msg = sprintf( $this->default_msg_erro, $input->label );
                                else
                                    $msg = $input->required;
                                 
                             }
            break;    
            
        }
        
        if( $msg != '' ) $input->setErro( $msg );
        
    }
    
}