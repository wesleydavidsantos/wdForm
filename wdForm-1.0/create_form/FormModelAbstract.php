<?php
/**
 * Classe abstrata obrigatória nos modelos de FORM
*/
abstract class FormModelAbstract{

    /**
     * Armazena todas as informações do formulário
    */
    private $form;

    /**
     * Armazena os nomes dos inputs
    */
    private $list_name_input;
    
    /**
     * Armazena o HTML do formulário gerado 
     * @type Array
    */
    private $form_html;
    
    /**
     * Armazena os inputs do tipo HIDDEN
    */
    private $inputTypeHidden;

    /**
     * Ao ser instanciada inicializa os atributos da classe
    */
    public function __construct( wdForm $form ){

        $this->form = $form;
        
        $this->form_html = array();
        
        $this->inputTypeHidden = array();

        $inputs = $this->form->getAllInputs();

        foreach( $inputs as $name_input => $value ){

            # Tipos inválidos na estilização do formulário
            if( in_array( $name_input, array('form', 'validform', 'button') ) ) continue;
            
            # Registra os INPUTS do tipo HIDDEN
            $type = $name_input . '_type';    
            if( $this->form->$type == 'hidden' ){
                $this->inputTypeHidden[] = $name_input;
                continue;
            }

            $this->list_name_input[] = $name_input;

        }

    }
    
    /**
     * Retorna os INPUTS do tipo HIDDEN
    */
    private function getInputHidden(){
        
        if( sizeof( $this->inputTypeHidden ) > 0 ){
                        
            foreach( $this->inputTypeHidden as $input ){
                $this->addInput( $this->getInput( $input ) );
            }
            
        }
        
    }
    
    /**
     * Metodo que retorna a lista de nomes dos inputs
     * @return Array
    */
    protected function listNamesInput(){

        return $this->list_name_input;

    }

    /**
     * Retorna o input de acordo com o parâmetro
     * @var $name_input > Nome do input a ser chamado
     * @return String
    */
    protected function getInput( $name_input ){

        $input = $name_input.'_input';
        return $this->form->$input;

    }

    /**
     * Retorna o input do Button
     * @return String HTML
    */
    protected function getButton(){

        return $this->form->button_input;

    }

    /**
     * Retorna o input para validação do formulário
     * @return String HTML
    */
    private function getInputValidForm(){

        return $this->form->validform_input;

    }

    /**
     * Método usado para adicionar os HTML dos inputs a serem apresentados
     * @param $html > Recebe o HTML do input 
    */
    protected function addInput( $html ){
        $this->form_html[] = $html;
    }
    
    /**
     * Método que retorna o formulário criado
     * @param $form > Inputs gerados
     * @return String HTML
    */
    public function createForm(){
        
        # Adiciona os INPUTS do tipo hidden
        $this->getInputHidden();
        
        # Transforma em string os inputs adicionados
        $html_form = implode( '', $this->form_html );
        
        # Inicializa o atributo
        $this->form_html = array();

        # Gera o formulário
        return $html_form;

    }
    
    /**
     * Método que retorna o INPUT do FORM <form>
     * @return String
    */
    public function getInputForm(){
        
        return $this->getInput('form');
        
    }
    
    abstract protected function mountForm();
    abstract public function getForm();

}