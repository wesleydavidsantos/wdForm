<?php

require_once 'FormModelAbstract.php';
require_once 'FormModelInterface.php';
require_once 'StyleForm.php';

/**
 * Classe utilizada para criar um formulário padrão
*/
class CreateForm extends FormModelAbstract implements FormModelInterface{
    
    /**
     * Armazena o modelo de Formulário a ser usado
    */
    private $path_model;
    
    /**
     * Armazena a intância do objeto contendo o modelo do formulário a ser usado
     * @type ReadModelForm
    */
    private $style_form;
    
    /**
     * Informa o model a ser usado
     * @param $model_form > Nome do arquivo contendo o XML do estilo do formulário
    */
    public function setModelForm( $model_form ){
        $this->path_model = get_config_wdForm( 'path_models' ) . $model_form;       
        $this->style_form = new StyleForm( $this->path_model ); 
    }
    
    /**
     * Envia os INPUTS para estilizar
    */
    protected function mountForm(){
        
        try{
            
            $list_input = $this->listNamesInput();
         
            if( sizeof( $list_input ) > 0 ){
                foreach( $list_input as $input ){
                    
                    $this->style_form->setInput( $this->getInput( $input ) );
                    
                    $this->addInput( $this->style_form->getInput() );
                    
                }
            }
           
        }catch( Exception $e ){
            echo 'Exception: Erro na classe <strong>CreateForm</strong> método <strong>mountForm</strong>. ' . $e->getMessage();
        }
        
    }
    
    /**
     * Retorna o estilo do botão
    */
    public function getStyleButton(){
        
        $this->style_form->setInput( $this->getButton() );
      
        return $this->style_form->getInput();
        
    }
    
    /**
     * Método responsável por retornar todo Formulário formatado para o usuário final
     * @return Formulário HTML
    */
    public function getForm(){

       # Monta os INPUTS para apresentação final ao usuário 
       $this->mountForm();
              
       return $this->style_form->getFormIt( $this );

    }

}

