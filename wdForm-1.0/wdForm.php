<?php
/**
 * Define o diretorio absolute de wdForm
 * @var string
 */
 if( !defined( 'WDFORM_INCLUDE_PATH' ) ) define('WDFORM_INCLUDE_PATH', dirname(__FILE__));


/**
* Carrega o arquivo de configuração config.php
* @access	private
* @param    $replace > Chave do config a ser retornada
* @return	array
*/
if ( ! function_exists('get_config_wdForm'))
{   
	function &get_config_wdForm( $replace )
	{
		# Verifica se o arquivo de configuração existe
        if ( !file_exists($file_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php')){
			exit( 'Arquivo de configuração do <strong>wdForm</strong> não encontrado' );
		}

        # Requisita o arquivo de configuração
		require($file_path);

		# Verifica se o array com as configurações esta correto
		if ( !isset($wdForm_config) OR !is_array($wdForm_config)){
			exit('Seu arquivo de configuração não foi formatado corretamente.');
		}
        
        # Retorna as configurações desejadas
        if( !isset( $wdForm_config[ $replace ] ) ){
            exit( 'Chave informada não encontrada no config.php <strong>'.$replace.'</strong>' );
        }
        
        return $wdForm_config[ $replace ];
        
	}
}

/**
* Retorna as mensgens default de cada classe
* @access private
* @param  $name > Chave do config a ser retornada
* @return array
*/
if ( ! function_exists('get_msg_default_class'))
{   
  function &get_msg_default_class( $name )
  {
      $config = get_config_wdForm( 'msg_default_class' );

      if( isset( $config[ $name ] ) ){
          return $config[ $name ];
      }else{
          throw new Exception( 'Tipo de mensagem default de uma classe não encontrado no Config.php . <strong>'.$name.'</strong>' );
      }
  }
}


/**
 * Carrega as principais classes do sistema
*/
require_once 'core/Input.php';
require_once 'core/Submit.php';
require_once 'core/Upload.php';
require_once 'core/MsgErro.php';
require_once 'attributes/Attributes.php';
require_once 'html_inputs/HtmlInput.php';
require_once 'create_form/CreateForm.php';
require_once 'lib_dml/wdDB.php';
require_once 'helpers/assets.php';
require_once 'helpers/format.php';
require_once 'helpers/validate.php';


/**
 * Armazena a classe principal para criação do formulário
*/
class wdForm{

    /**
     * Armazena os inputs criados
     * @type Array
    */
    private $inputs;

    /**
     * Armazena o nome do input que esta sendo acessado no momento
     * @type String
    */
    private $input_current;

    /**
     * Informa as actions obrigatórias em cada INPUT, valores com % indica que o conteúdo será de outro atributo do mesmo input
    */
    private $inputs_required = array( 'type'=>'text', 'label'=>'%name', 'placeholder'=>'%label' );
    
    /**
     * Verifica se o SUBMIT foi requisitado e validado corretamente
     * so será TRUE quando não for encontrado erros no formulário
     * @type Boolean
    */
    private $validSendSubmit;

    /**
     * Verifica se a checagem do submit foi executada
     * @var Boolean
     */
    private $isRunCheckSubmit;
    
    /**
     * Caracter usado para unir a ação e o seu valor.
     * @Ex array('name|wesley');
     * @type Char
     * @default | (pipe)
    */
    public $join = '|';
    
    /**
     * Informa se a chave de validação do formulário foi inserida de forma manual pelo usuário
     * @type Boolean
    */
    private $keyValidFormManual;
    
    /**
     * Armazena o objeto da classe wdDB responsável por cadastro de dados no banco de dados
     * @type wdDB
    */
    public $db;
    
    /**
     * Ao ser instanciada a classe cria os inputs primários
     * @var $form > Recebe uma lista de inputs via array
    */
    public function __construct( Array $form = array() ){        
       
        
        # Cria o padrão do FORM
        $this->form = array( 'type'.$this->join.'form', 'action'.$this->join.'#', 'method'.$this->join.'post' ); # Informa o tipo de formulário
        $this->button = array( 'type'.$this->join.'submit', 'value'.$this->join.'Enviar', 'class'.$this->join.'right' ); # Apresenta o Botão do formulário
       
        if( sizeof( $form ) > 0 ){

            foreach( $form as $action => $value ){
                $this->$action = $value;
            }

        }
        
        $this->validSendSubmit = false;
        $this->isRunCheckSubmit = false;
        
        # Cria um nome para o formulário, se existir mais de um formulário na página é necessário informar um nome único para evitar SUBMIT errado 
        $this->setNameKeyValidForm( 'my-form' );
        
        # Cria o objeto usado para realizar ações de DML
        $this->db = new wdDB( $this );
    }
    
    /**
     * Informe uma chave única para seu form, desta forma não corre nenhum risco de executar FORM diferentes dentro de uma mesma página
     * @return $this
    */
    public function setNameKeyValidForm( $key ){
        $this->keyValidFormManual = true;
        $this->setInputValidForm( $key );
        return $this;
    }
    
    /**
     * Cria o INPUT de validação do formulário
    */
    private function setInputValidForm( $key ){
        $this->validform = array('type|hidden', 'name|'.$key, 'value|validForm', 'label|'); # Input de validação do formulário
    }
    
    /**
     * Método que retorna todos os inputs cadastrados
    */
    public function getAllInputs(){
        return $this->inputs;
    }
    
    public function getAllNamesInput(){
        
        return array_keys($this->inputs);
    }

    /**
     * Informa o nome do Input. O nome do input é a chave do array desta forma o torna único
     * @var $name_input > Nome dado ao input. String
    */
    private function setNameInput( $name_input ){

        if( !isset( $this->inputs[ $name_input ] ) )$this->inputs[ strtolower( $name_input ) ] = new Input( 'name', $name_input );

        $this->input_current = $this->inputs[ $name_input ];

        return $this;    

    }

    /**
     * Retorna o valor de um atributo do input atual
     * @var $name_attr > Nome dado ao input. String
    */
    private function getAttrInputCurrent( $name_attr ){

        return $this->input_current->$name_attr;

    }

    /**
     * Método que retorna as informações de um determinado input
     * @var $name_input > Nome do input a ser retornado
     * @return Array
    */
    private function getInput( $name_input ){

        try {
            
            # Verifica se o SUBMIT foi verificado, processo obrigatório para criação de INPUTS de forma correta
            if( $this->keyValidFormManual && !$this->validSendSubmit ) $this->checkSubmit();
            
            if( isset( $this->inputs[ $name_input ] ) ){

                return new HtmlInput( $this->inputs[ $name_input ] );
                
            }else{

                throw new Exception( "Exception: getInput(), Nome de input não encontrado <strong>".$name_input."</strong> <br /> - Isso pode ocorrer se o método 'checkSubmit' ou 'CreateForm' ainda não foi usado.<br /> Classe " . get_class($this) . "<br />" );

            }

        }catch( Exception $e ){
            echo $e->getMessage();
        }

    }

    /**
     * Apaga os valores de todos os INPUTS
     * @author Wesley David Santos
     * @link   http://www.classmain.com
     * @return [type] [description]
     */
    public function clearAllValues(){

        $inputs = $this->getAllNamesInput();

        foreach ($inputs as $name) {
            
            # Verifica o tipo de input para pegar o valor a ser validado
            switch ( $this->inputs[ $name ]->type) {

                case 'form':
                case 'submit':
                case 'button':break;
                
                case 'checkbox':
                case 'radio':
                                $this->inputs[ $name ]->checked = '';
                break;

                case 'select':
                                $this->inputs[ $name ]->selected = '';
                break;
                
                default: $value = $this->inputs[ $name ]->value = '';
            }

        }
    }

    /**
     * Apaga os valor de um determinado INPUT
     * @author Wesley David Santos
     * @link   http://www.classmain.com
     * @return [type] [description]
     */
    public function clearValue(){

        $numargs = func_num_args();
        $arg_list = func_get_args();
        
        for ($i = 0; $i < $numargs; $i++){
            
            $name_input = $arg_list[$i];
        
            # Verifica o tipo de input para pegar o valor a ser validado
            switch ( $this->inputs[ $name_input ]->type) {

                case 'checkbox':
                case 'radio':
                                $this->inputs[ $name_input ]->checked = '';
                break;

                case 'select':
                                $this->inputs[ $name_input ]->selected = '';
                break;
                
                default: $value = $this->inputs[ $name_input ]->value = '';
            }

        }

    }
    
    /**
     * Método responsável por excluir um determinado INPUT do formulário
     * @return $this
    */
    public function unsetInput(){
        
        $numargs = func_num_args();
        $arg_list = func_get_args();
        
        for ($i = 0; $i < $numargs; $i++){
            unset( $this->inputs[ $arg_list[$i] ] );
        }
            
        return $this;
        
    }

    /**
     * Método usado para criar um determinado formulário de acordo com o modelo repassado via parametro
     * @var $model_form > Modelo de formulário a ser usado
     * @type CreateFormInterface
     * @return HTML
    */
    public function createForm( $model_form = 'NoStyle.xml' ){

        try{

            # Atualiza as informações dos INPUTS
            $this->checkSubmit();

            $form = new CreateForm( $this );
            $form->setModelForm( $model_form );

            return $form->getForm();    

        }catch( Exception $e ){
            echo "Exception: createForm() - Modelo de FORM. Classe" . get_class( $this ).' - ' . $e->getMessage().'<br />';
        }

    }

    private function setAttributeInput($action, $value){
    
        # Verifica se o formulário possui anexo de arquivos, se sim muda automaticamente o tipo de enctype 
        if( $action == 'type' && $value == 'file' ) $this->inputs[ 'form' ]->enctype = 'multipart/form-data';

        $this->input_current->$action = $value;

        return $this;

    }

    /**
     * Formata valores especiais informados para um determinado atributo
     * @author Wesley David Santos
     * @link   http://www.classmain.com
     * @param  [type] $value [description]
     * @return [type] [description]
     */
    private function formartValueSpecial( $value ){

      # Verifica se o o value deve possuir valores de outro atributo, usado muito quando funções javascript
      if( !is_array( $value ) && !empty( $value ) ){
        preg_match_all('/%[a-z_0-9]+%/U', $value, $matches);
        if( sizeof( $matches ) > 0 ){

            foreach( $matches as $list ){
                                        
                foreach( $list as $attr ){

                    $name_input_request = str_replace( '%', '', $attr );

                    # Verifica se o valor deve vim de outro input
                    if( strpos( $name_input_request, '_') !== false ){

                      $value_request = $this->$name_input_request;

                    }else{
                      # O valor vem do próprio input que esta sendo usado
                      $value_request = $this->getAttrInputCurrent( $name_input_request );
                    }

                    # Atribui as novas informações ao value
                    $value = str_replace( $attr, $value_request, $value );

                }
            }

        }

      }

      return $value;

    }

    /**
     * Recebe os atributos de cada input
     * @var $action > Ação do atributo. String
     * @var $value > Valor a ser adicionado ao atributo. Mixed
     * 
     * @obs Existem 3 formas de repassar a informação do input
     * #1 - $form->input_action = value;
     * #2 - $form->input = array('action|value');
     * #3 - $form->input = value; // Executa o campo action value
    */
    public function __set( $action, $value ){

        /*
          #1 - O $action pode ser informado contendo o nome do input e da ação juntos, mas, separados pelo UNDERLINE ($form->input_action),
          desta forma devemos atribuir cada valor ao seu respectivo dono.

          #2 - Se o $action conter somente o nome do input, então, a ação esta sendo informada junto com o $value e no formato de ARRAY sendo
          separados pelo JOIN, desta forma devemos atribuir cada valor ao seu respectivo dono.

          #3 - Se o $action conter somente o nome do input e o $value não conter a separação do JOIN então é atribuida a ação default VALUE  
        */
       
        # Validação forma #1
        if( strpos( $action, '_' ) !== false ){

            $input = explode( '_', $action );
            $name_input = $input[0];
            $action = $input[1];

            # Verifica se o valor informado é alguma ação especial
            $value = $this->formartValueSpecial( $value );

            $this->setNameInput( $name_input )->setAttributeInput( $action, $value );

        }else{

            $name_input = $action;

            # Validação forma #2    
            if( is_array( $value ) ){

                $list_action = $value;

                foreach( $list_action as $action => $a ){
                    
                    if( is_array( $a ) ){
                        
                        $value = $a;
                    
                    }else{
                        
                        if( strpos( $a, $this->join ) !== false ){
    
                            $inf = explode( $this->join, $a );
                            $action = $inf[0];
                            $value = $inf[1];
    
                        }else{
    
                            $action = $a;
                            $value = '';
    
                        }
                        
                    }

                    # Verifica se o valor informado é alguma ação especial
                    $value = $this->formartValueSpecial( $value );

                    $this->setNameInput( $name_input )->setAttributeInput( $action, $value );

                }

            }else{

                # Validação forma #3
                $this->setNameInput( $name_input )->setAttributeInput( 'value', $value );

            }

        }        

    }

    /**
     * Método responsável por retornar o value do action de um determinado input
     * @var $action > Deve conter o nome do INPUT e do action, separados por UNDERLINE
     * @obs Se o nome do action não for repassado, então é considerado o retorno do valor do INPUT
     * 
     * @return Mixed
     * @erro @return NULL
    */
    public function __get( $action ){

        if( strpos( $action, '_' ) !== false ){

            $input = explode( '_', $action );
            $name_input = $input[0];
            $action = $input[1];

        }else{

            # Verifica se o INPUT existe
            if( !isset( $this->inputs[ $action ] ) ) return null;

            $name_input = $action;
            $action = "value";

        }

        try {

            if( isset( $this->inputs[ $name_input ] ) ){
                
                # Se o TYPE não existe então deve ser informado um default sendo ele um TEXT, pois o programador pode ter registrado o INPUT após a checagem do "checkSubmit" 
                if( $this->inputs[ $name_input ]->type == '' ) $this->inputs[ $name_input ]->type = 'text';

                /**
                 * Verifica se é para retornar o HTML do input
                */
                if( $action == 'input' ){
                    
                    # Se for o form então retorna junto o input de validação
                    if( $name_input == 'form' ){
                        return $this->getInput( $name_input ) . $this->getInput( 'validform' );
                    }else{
                        return $this->getInput( $name_input );
                    }

                }else{

                    return $this->inputs[ $name_input ]->$action;    

                }

            }else{
                return null;
                //throw new Exception( "Exception: Nome de input não encontrado <strong>".$name_input."</strong> Classe " . get_class($this). "<br />" );
            }

        }catch( Exception $e ){
            echo $e->getMessage();
        }    

    }
    
    /**
     * Método responsável por verificar se o formulário foi encaminhado via SUBMIT
     * @return Boolean
    */
    public function checkSubmit(){
        
        if( !$this->validSendSubmit && !$this->isRunCheckSubmit ){
            $this->isRunCheckSubmit = true;
            $this->submit();
        }
        
        return $this->validSendSubmit;
            
    }
    
    /**
     * Verifica se foi gerado erros no submit
     * @return Boolean
     */
    public function existErro(){

        if( $this->getAllErros() != '' ) return true;

        return false;

    }
    
    /**
     * Retorna os erros dos Métodos getErrosInput() e getErrosDml() concatenados
     * @Obs Necessário utilizar o wdDB
     * @return String
    */
    public function getAllErros(){
        return $this->getErrosInput() . $this->getErrosDml();
    }
    
    /**
     * Retorna os erros gerados pelos INPUTS
     * @return String
    */
    public function getErrosInput(){
        
        $list_erro = array();
        
        $list_input = $this->getAllInputs();
     
        if( sizeof( $list_input ) > 0 ){
            foreach( $list_input as $input ){
                $erro = $input->getErro();
                
                if( $erro != '' ) $list_erro[] = $erro;       
            }
            
            if( sizeof( $list_erro ) > 1 ){
                $erro = '<ul><li>' . implode( '<li>', $list_erro ) . '</li></ul>';
            }else{
                if( sizeof( $list_erro ) ==  1 ) $erro = '<ul><li>' . implode( '', $list_erro ) . '</li></ul>';
            }
            
            return $erro;
        }
        
        return '';
        
    }
    
    /**
     * Retorna os erros gerados pela classe wdDB
     * @return String
    */
    public function getErrosDml(){        
        return $this->db->getErro();    
    }
    
    /**
     * Método usado para validar os campos INPUTS e verificar o SUBMIT do formulário 
     * @return Boolean
    */
    private function submit(){

        try {
            
            
            if( sizeof( $this->inputs ) > 0 ){

                foreach( $this->inputs as $name_input => $input ){

                    if( in_array( $name_input, array( 'form', 'button' ) ) ) continue;

                    $list_action = $input->getNamesAttributes();

                    # Pecorre os atributos do input e verifica se algum tem que ser alterado
                    foreach( $list_action as $name ){
                        
                        # Verifica se no INPUT algum atributo tem valor que pertence a outro atributo
                        if( !is_array( $input->$name ) ){
                            
                            preg_match_all('/%[a-z]+%/U', $input->$name, $matches);
                            if( sizeof( $matches ) > 0 ){

                                foreach( $matches as $list ){
                                                            
                                    foreach( $list as $attr ){

                                        $action_default = $name_input . '_' . str_replace( '%', '', $attr );
                                        $input->$name = str_replace( $attr, $this->$action_default, $input->$name );
                                        
                                    }
                                }
    
                            }
                            
                        }
                        
                    }       
                    
                    # Verifica se algum INPUT required não foi informado
                    foreach( $this->inputs_required as $input_required => $value ){

                        if( in_array( $input_required, $list_action ) ) continue;

                        # Verifica se o valor é o conteúdo de outro campo
                        if( strpos( $value, '%' ) !== false ){

                            $action_default = $name_input . '_' . str_replace( '%', '', $value );
                            $value = $this->$action_default;

                        }

                        if( $input_required == 'label' ) $value = ucfirst( $value );

                        $this->$name_input = array( $input_required."|".$value );

                    }

                }
                
                # Verifica se o submit foi solicitado e se tudo esta certo
                $submit = new Submit( $this );
                $this->validSendSubmit = $submit->check();
            
            }else{
                throw new Exception("Exception: Não foram criados campos INPUT. Class " . get_class($this). "<br />" );
            }

        }catch( Exception $e ){
            echo $e->getMessage();
        }

    }
    
    /**
     * Realiza o UPLOAD de todos os arquivos anexados
     * @return Array
    */
    public function uploadFiles(){
        
        # Primeiro verifica se o SUBMIT foi válidado com sucesso
        if( $this->checkSubmit() ){
            $upload = new Upload( $this );
            return $upload->uploadFiles();
        }
        
        return array();
        
    }

}