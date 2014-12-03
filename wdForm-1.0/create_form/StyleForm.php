<?php
/**
 * Classe respons�vel por realizar a leitura do XML contendo o modelo do Formul�rio e formatar os INPUTS para serem apresentados ao usu�rio final
*/
class StyleForm{
    
    /**
     * Armazena a int�ncia do INPUT
     * @type HtmlInput 
    */
    private $input;
    
    /**
     * Armazena o input estilizado para apresenta��o ao usu�rio final
     * @type String
    */
    private $inputStyle;
    
    /**
     * Armazena o arquivo XML com o estilo do formul�rio
     * @type XML
    */
    private $model;
    
    /**
     * Construtor
     * Inicializa os principais atributos
     * @param $path_model > Cont�m o caminho do modelo de estilo do formul�rio  
    */
    public function __construct( $path_model ){
        $this->model = new SimpleXMLElement( file_get_contents( $path_model ) );
    }
    
    /**
     * Armazena o INPUT que ser� usado
     * @param $input > Objeto contendo o input
    */
    public function setInput( HtmlInput $input ){
        $this->input = $input;
        $this->inputStyle = ''; # Inicializa o atributo que ir� conter o input estilizado
        return $this;
    }
    
    /**
     * Seta o INPUT estilizado e formatado para apresenta��o ao usu�rio final
     * @Obs Como o INPUT pode ser criado por partes, ent�o o m�todo concatena as entradas
     * @param $input_style String > Cont�m o input formatado 
    */
    private function setInputStyle( $input_style ){
        $this->inputStyle .= $input_style;
    }
    
    /**
     * Retorna o INPUT estilizado
     * @return String
    */
    public function getInput(){
        
        # Adiciona os atributos globais ao INPUT
        $this->setAttributes();

        # Cria o INPUT a ser apresentado ao usu�rio final
        $this->createInput();
             
        return $this->inputStyle;
        
    }
    
    /**
     * M�todo que seta atributos que devem conter nos inputs
     * @param $input HtmlInput
    */
    private function setAttributes(){


        # Procura atributos locais que devem ser adicionados ao INPUT
        if( isset( $this->model->inputs->{$this->input->type}->attributes ) ){
            
            # Se o atributo j� existir, ent�o o novo atributo � concatenado com o atributo anterior
            foreach( $this->model->inputs->{$this->input->type}->attributes->children() as $attribute => $value ){
              
                if( $attribute == 'except' ) continue;

                if( $this->input->$attribute != '' )
                    $this->input->$attribute = $this->input->$attribute . ' ' . (string)$value;
                else
                    $this->input->$attribute = (string)$value;
                   
            }
        }
        
        # Procura atributos globais a serem adicionados ao INPUT
        if( isset( $this->model->inputs->attributesAll ) && sizeof( $this->model->inputs->attributesAll ) > 0 ){
            
            
            # Verifica se o estilo � permitido para o tipo passado
            if( isset($this->model->inputs->attributesAll->except) ){
                
                $except = explode( ',', (string) $this->model->inputs->attributesAll->except );
                
                # Verifica se o tipo do INPUT caiu na excess�o
                if( in_array( $this->input->type, $except ) ) return '';
                
            }

            # Adiciona os novos atributos ao INPUT, se o atributo j� existir, ent�o o novo atributo � concatenado com o atributo anterior
            foreach( $this->model->inputs->attributesAll->children() as $attribute => $value ){
              
                if( $attribute == 'except' ) continue;
              
                if( $this->input->$attribute != '' )
                    $this->input->$attribute = $this->input->$attribute . ' ' . (string)$value;
                else
                    $this->input->$attribute = (string)$value;
                   
            }
            
        }
        
        # Valida os novos atributos adicionados
        $this->input->valid();
        
    }
    
    /**
     * Estiliza cada INPUT, alguns INPUTS cont�m INPUTS internos (Ex. RADIO, CHECKBOX), desta forma o INPUT pode ser formatado de 2 formas
    */
    private function createInput(){
     
        # Verifica se existe inputs internos dentro do INPUT principal 
        if( is_array( $this->input->getObjSubInput() ) ){
            
            $listSubInput = $this->input->getObjSubInput();
            
            # Recebe os estilos dos SubInputs
            $styleSubInput = '';
            
            # Pecorre a lista com os SubInputs
            foreach( $listSubInput as $subInput ){
                
                # Pega o estilo dos subInputs
                $styleSubInput .= $this->formateInput( $this->getSubType( $this->input->type ), $subInput );
                
            }
          
            # Adiciona o estilo principal do tipo de INPUT
            $this->input->content = $styleSubInput;
            $this->inputStyle = $this->formateInput( $this->getType( $this->input->type ) );
               
        }else{
        
            # Adiciona o estilo do INPUT    
            $this->inputStyle = $this->formateInput( $this->getType( $this->input->type ) );

        }
        
    }
    
    /**
     * M�todo usado para formatar os campos din�mico do estilo do formul�rio
     * @param $style String > Estilo a ser usado no INPUT
     * @param $input > Input a ser estilizado 
     * @return String 
    */
    private function formateInput( $style, $subInput='' ){
       
        # Retorna os par�metros din�micos a serem usados
        $param_style = $this->getParamStyle( $style );  

        foreach( $param_style as $attribute ){

            
            # Verifica se � uma subClasse
            if( $attribute == 'input' && $subInput instanceof SubType ){
                $value = $subInput->__toString();
                
            }else{
                
                if( $attribute == 'input' ){
                    $value = $this->input->__toString();
                }else{
                    $value = isset( $subInput->$attribute ) ? $subInput->$attribute : $this->input->$attribute;
                    
                    # Informa os attributos que devem ser removidos do INPUT
                    $this->input->setAttributeRemove( $attribute );
                }
                
            }

            $style = $this->formateStyle( $attribute, $value, $style );
            
        }
        
        return $style;
        
    }
    
    /**
     * M�todo que formata o estilo din�mico do formul�rio
     * @param $attribute > Atributo a ser alterado
     * @param $value > Valor a ser colocado no lugar do atributo
     * @param $style > Estilo original que ser� alterado
     * @return String
    */
    private function formateStyle( $attribute, $value, $style ){
        # Retorna os valores do estilo atualizados
        return str_replace( '%'.$attribute.'%', $value, $style );
        
    }
    
    /**
     * M�todo usado para retonar os campos din�micos encontrados dentro do Style do Formul�rio
     * @param $style > Estilo a ser procurado
     * @return Mixed
    */
    private function getParamStyle( $style ){
        
        preg_match_all('/%[a-z]+%/U', $style, $matches); 
        
        $attributes = array();
        
        foreach( $matches as $list ){
            foreach( $list as $attr ){
                $attributes[] = str_replace( '%', '', $attr );
            }
        }
                
        return $attributes;
        
    }
    
    /**
     * Retorna o estilo para cada tipo de input
     * @return String
    */
    private function getType( $type ){

        $style = isset( $this->model->inputs->$type ) ? (string) $this->model->inputs->$type : '';
        
        return $this->getStyleInputAll( $style );
        
    }
    
    /**
     * M�todo que busca por input interno, usado para personalizar INPUTS com itens internos
     * @Ex Radio, Checkbox
     * @return String
    */
    private function getSubType( $type ){
        
        return isset( $this->model->inputs->$type->input ) ? (string) $this->model->inputs->$type->input : '';
        
    }
    
    /**
     * Procura pelos estilos globais que devem ser usados em todos inputs, excetos nos que se encontram dentro da tag <except></except>
     * @param $style > Cont�m o estilo local da input que dever� ser adicionado ao estilo global
     * 
    */
    private function getStyleInputAll( $style ){
        
        if( empty( $style ) ) return '';
        
        if( isset( $this->model->inputs->inputAll ) && !empty( $this->model->inputs->inputAll ) ){
            # Verifica se o estilo � permitido para o tipo passado
            if( isset($this->model->inputs->inputAll->except) ){
                $except = explode( ',', (string) $this->model->inputs->inputAll->except );
                if( in_array( $this->input->type, $except ) ) return $style;
            }
                
            return str_replace( '%content%', $style, (string) $this->model->inputs->inputAll );
        }
        
        return $style;
        
    }
    
    /**
     * Retorna o formul�rio estilizado e formatado para apresentar ao usu�iro final
     * @return String HTML 
    */
    public function getFormIt( $form ){
        
        # Estilo que engloba o formul�rio
        $style_form = isset( $this->model->content ) ? (string) $this->model->content : '';
        
        # Adiciona o INPUT <form>
        $style_form = $this->formateStyle( 'wdForm-form', $form->getInputForm(), $style_form );
        
        # Adiciona todos os inputs cadastrados
        $style_form = $this->formateStyle( 'wdForm-inputs', $form->createForm(), $style_form );
        
        # Verifica se existe um campo para apresentar o Button
        if( strpos( $style_form, '%wdForm-button%' ) ){
            $style_form = $this->formateStyle( 'wdForm-button', $form->getStyleButton(), $style_form  );
        }
        
        return $style_form;
        
    }
        
}