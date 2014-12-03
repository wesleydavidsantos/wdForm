<?php
/**
 * Classe abstrata respons�vel por definir o Framework a ser utilizado para cadastrar os fomul�rios no banco de dados
*/
abstract class DmlAbstract{
    
    /**
     * Mensagens default de erro
     * @var [type]
     */
    private $default_msg_erro;

    /**
     * Armazena a inst�ncia do formul�rio
     * @type wdForm
    */
    protected $form;
    
    /**
     * Armazena o nome da classe model a ser usada
     * @type String Model
    */
    protected $model;

    /**
     * Armazena o objeto do Model
     * @type Object Model
    */
    protected $obj_active_record;
    
    /**
     * Armazena erros que possam ocorrer dentro da pr�pria classe
     * @type Array
    */
    private $erro_inner;
    
    /**
     * Armazena o ID do registro para a��o de UPDATE
    */
    private $id;
    
    /**
     * Cadastra os dados do formul�rio no banco de dados
     * 
     * @obs Os atributos publicos da classe model s�o usados como par�metros para cadastro no banco de dados
     * 
     * @param $form > Cont�m a classe com a int�ncia do formul�rio
    */
    public function __construct( wdForm $form ){

        $this->default_msg_erro = get_msg_default_class('DmlAbstract');
        
        # Inicializa os atributos
        $this->form = $form;
        $this->erro_inner = array();
        $this->id = '';
       
    }

        
    /**
     * Retorna o Objeto de conex�o com o banco de dados
     * @return ActiveRecord\Model
    */
    public function getObj(){
        return $this->obj_active_record;
    }
    
    /**
     * Classe Model do banco de dados
    */
    public function setModel( $model ){ 
        $this->model = $model;
        return $this; 
    }
    
    /**
     * Informa os tratamentos de erros que ocorrem dentro da pr�pria classe
     * @param $msg > Mensagem de erro
    */
    private function setErroInner( $msg ){
        $this->erro_inner[] = $msg;
    } 
    
    /**
     * Verifica se foram encontrados erros
     * @return Boolean
    */
    protected function checkErroInner(){
        return sizeof( $this->erro_inner ) > 0 ? TRUE : FALSE; 
    } 
    
    /**
     * Retorna os tratamentos de erros que ocorrem dentro da pr�pria classe
     * @return Array
    */
    protected function getErroInner(){
        return $this->erro_inner;
    }

    /**
     * Formata as colunas para acesso via GET e SET
     * @author Wesley David Santos
     * @link   http://www.classmain.com
     * @param  [type] $column [description]
     * @return [type] [description]
     */
    private function formatColumn( $column ){

        if( strpos($column, '_') !== false ){
            $column = explode('_', $column);

            $input = array();
            foreach ($column as $c) {
                $input[] = ucfirst( $c );
            }
            
            $column = implode('', $input);
        }else{
            $column = ucfirst( $column );
        }

        return $column;

    }

    /**
     * Seta informa��es nas colunas
     * @author Wesley David Santos
     * @link   http://www.classmain.com
     * @param  [type] $column - Nome da coluna
     * @param  [type] $value - Valor a ser adicionado
     */
    protected function setColumn( $column, $value ){
        
        $set_column = 'set' . $this->formatColumn( $column );

        if( method_exists($this->obj_active_record, $set_column) ){ 
            $this->obj_active_record->$set_column( $value );
        }else{
            $this->obj_active_record->$column = $value;
        }

    }

    /**
     * Retorna as informa��es das colunas
     * @param type $column - Nome da coluna
     * @return type
     */
    protected function getColumn( $column ){

        $get_column = 'get' . $this->formatColumn( $column );

        if( method_exists($this->obj_active_record, $get_column) ){
            return $this->obj_active_record->$get_column();
        }else{
            return $this->obj_active_record->$column;
        }

        

    }

    /**
     * Adiciona um valor no form, usado pelo 'populateForm'
     * @author Wesley David Santos
     * @link   http://www.classmain.com
     * @param  [string] $input - Nome da coluna do banco de dados que ser� usada
     */
    protected function setValueForm( $column ){

        # Nome da coluna do banco de dados que ser� usada
        $input = str_replace('_', '', $column);

        if( !is_null( $this->form->$input ) ){

            # Alguns tipos tem formas diferentes de pegar o valor escolhido pelo usu�rio
            $type = $input.'_type';
            switch( $this->form->$type ){
                case 'select': $input .= '_selected';
                break;
                
                case 'checkbox': 
                case 'radio':
                                $input .= '_checked';
                break;

                case 'file': $input .= '_listfiles';
                break;
            }

            $this->form->$input = $this->getColumn( $column );

        }       

    }

    /**
     * Informa que um registro n�o � �nico como esta sendo necess�rio
     * @author Wesley David Santos
     * @link   http://www.classmain.com
     * @param $label - Nome do campo que deve ser �nico
     */
    protected function setErroUnique( $label ){
        $this->setErroInner( sprintf( $this->default_msg_erro['unique'], $label ) );
    }

    /**
     * Informa que um registro n�o foi encontrado no banco de dados, usado em UPDATE
     * @author Wesley David Santos
     * @link   http://www.classmain.com
     */
    protected function setErroNotFound(){
        $this->setErroInner( $this->default_msg_erro['not_found'] );
    }

    /**
     * Informa que o c�digo n�merico de um registro � inv�lido
     * @author Wesley David Santos
     * @link   http://www.classmain.com
     */
    protected function setErroInvalidCode(){
        $this->setErroInner( $this->default_msg_erro['invalid_code'] );
    }
    
    
    abstract public function insert();
    abstract public function update( $id );
    abstract protected function unique( $column, $value );
    abstract public function getErro();
    
}