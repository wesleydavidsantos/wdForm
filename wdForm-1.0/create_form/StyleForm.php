<?php
/**
 * Classe responsável por realizar a leitura do XML contendo o modelo do Formulário e formatar os INPUTS para serem apresentados ao usuário final
*/
class StyleForm{
    
    /**
     * Armazena a intância do INPUT
     * @type HtmlInput 
    */
    private $input;
    
    /**
     * Armazena o input estilizado para apresentação ao usuário final
     * @type String
    */
    private $inputStyle;
    
    /**
     * Armazena o arquivo XML com o estilo do formulário
     * @type XML
    */
    private $model;
    
    /**
     * Construtor
     * Inicializa os principais atributos
     * @param $path_model > Contém o caminho do modelo de estilo do formulário  
    */
    public function __construct( $path_model ){
        $this->model = new SimpleXMLElement( file_get_contents( $path_model ) );
    }
    
    /**
     * Armazena o INPUT que será usado
     * @param $input > Objeto contendo o input
    */
    public function setInput( HtmlInput $input ){
        $this->input = $input;
        $this->inputStyle = ''; # Inicializa o atributo que irá conter o input estilizado
        return $this;
    }
    
    /**
     * Seta o INPUT estilizado e formatado para apresentação ao usuário final
     * @Obs Como o INPUT pode ser criado por partes, então o método concatena as entradas
     * @param $input_style String > Contém o input formatado 
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

        # Cria o INPUT a ser apresentado ao usuário final
        $this->createInput();
             
        return $this->inputStyle;
        
    }
    
    /**
     * Método que seta atributos que devem conter nos inputs
     * @param $input HtmlInput
    */
    private function setAttributes(){


        # Procura atributos locais que devem ser adicionados ao INPUT
        if( isset( $this->model->inputs->{$this->input->type}->attributes ) ){
            
            # Se o atributo já existir, então o novo atributo é concatenado com o atributo anterior
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
            
            
            # Verifica se o estilo é permitido para o tipo passado
            if( isset($this->model->inputs->attributesAll->except) ){
                
                $except = explode( ',', (string) $this->model->inputs->attributesAll->except );
                
                # Verifica se o tipo do INPUT caiu na excessão
                if( in_array( $this->input->type, $except ) ) return '';
                
            }

            # Adiciona os novos atributos ao INPUT, se o atributo já existir, então o novo atributo é concatenado com o atributo anterior
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
     * Estiliza cada INPUT, alguns INPUTS contém INPUTS internos (Ex. RADIO, CHECKBOX), desta forma o INPUT pode ser formatado de 2 formas
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
     * Método usado para formatar os campos dinâmico do estilo do formulário
     * @param $style String > Estilo a ser usado no INPUT
     * @param $input > Input a ser estilizado 
     * @return String 
    */
    private function formateInput( $style, $subInput='' ){
       
        # Retorna os parâmetros dinâmicos a serem usados
        $param_style = $this->getParamStyle( $style );  

        foreach( $param_style as $attribute ){

            
            # Verifica se é uma subClasse
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
     * Método que formata o estilo dinâmico do formulário
     * @param $attribute > Atributo a ser alterado
     * @param $value > Valor a ser colocado no lugar do atributo
     * @param $style > Estilo original que será alterado
     * @return String
    */
    private function formateStyle( $attribute, $value, $style ){
        # Retorna os valores do estilo atualizados
        return str_replace( '%'.$attribute.'%', $value, $style );
        
    }
    
    /**
     * Método usado para retonar os campos dinâmicos encontrados dentro do Style do Formulário
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
     * Método que busca por input interno, usado para personalizar INPUTS com itens internos
     * @Ex Radio, Checkbox
     * @return String
    */
    private function getSubType( $type ){
        
        return isset( $this->model->inputs->$type->input ) ? (string) $this->model->inputs->$type->input : '';
        
    }
    
    /**
     * Procura pelos estilos globais que devem ser usados em todos inputs, excetos nos que se encontram dentro da tag <except></except>
     * @param $style > Contém o estilo local da input que deverá ser adicionado ao estilo global
     * 
    */
    private function getStyleInputAll( $style ){
        
        if( empty( $style ) ) return '';
        
        if( isset( $this->model->inputs->inputAll ) && !empty( $this->model->inputs->inputAll ) ){
            # Verifica se o estilo é permitido para o tipo passado
            if( isset($this->model->inputs->inputAll->except) ){
                $except = explode( ',', (string) $this->model->inputs->inputAll->except );
                if( in_array( $this->input->type, $except ) ) return $style;
            }
                
            return str_replace( '%content%', $style, (string) $this->model->inputs->inputAll );
        }
        
        return $style;
        
    }
    
    /**
     * Retorna o formulário estilizado e formatado para apresentar ao usuáiro final
     * @return String HTML 
    */
    public function getFormIt( $form ){
        
        # Estilo que engloba o formulário
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