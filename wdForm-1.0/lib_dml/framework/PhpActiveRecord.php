<?php
/**
 * @frameword phpactiverecord
 * @link http://www.phpactiverecord.org/
*/
class PhpActiveRecord extends DmlAbstract implements DmlInterface{
    
    /**
     * Armazena os atributos da classe que esta sendo acessada 
     * @type Array
    */
    private $attributes;
    
    /**
     * Nome da chave primária do model
     * @type String
    */
    private $primary_key;
    
    /**
     * Verifica se um campo possui um valor que deve ser único no banco de dados
     * @param $column > Nome da coluna do banco de dados a ser verificada
     * @param $value > Valor a ser considerado como único
     * @return TRUE - Valor único | FALSE - Valor já existe
    */
    protected function unique( $column, $value ){
        
        try{
            
            $model = $this->model;
            $primary_key = $model::$primary_key;
            
            # Método de consulta
            $find = 'find_by_' . $column;
            
            # Realiza a consulta
            $obj = $model::$find( $value );

            if( isset( $obj ) ){
                
                # UPDATE
                if( isset( $this->id ) && is_numeric( $this->id ) ){
                    if( $obj->$primary_key == $this->id )
                        return TRUE;
                    else
                        return FALSE;                    
                }else{
                    return FALSE;
                }    
                
            }
        
            return TRUE;
        
        }catch ( Exception $e ){
            
            echo 'Exception: <strong>PhpActiveRecord</strong> método <strong>validUnique</strong> ' . $e->getMessage();
            
        }
        
    }
    
    /**
     * Executa e preenche o model com os dados do FORM
     * @return Boolean
    */
    private function run(){
        
        if( sizeof( $this->attributes ) > 0 ){
            
            foreach( $this->attributes as $attr => $value ){
            
                $name_input = $input = str_replace( '_', '', $attr );
                
                if( !is_null( $this->form->$input ) ){
                    
                    # Alguns tipos tem formas diferentes de pegar o valor escolhido pelo usuário
                    $type = $input.'_type';
                    switch( $this->form->$type ){
                        case 'select': $input .= '_selected';
                        break;
                        
                        case 'checkbox':
                        case 'radio':
                                        $input .= '_checked';
                        break;
                    }
                    
                    # Verifica se o valor a ser registrado deve ser único
                    $unique = $name_input . '_unique';
                    if( $this->form->$unique && !$this->unique( $attr, $this->form->$input ) ){
                        $label = $name_input . '_label';
                        $this->setErroUnique( $this->form->$label );
                    }else{

                        # Registra o valor no model responsável pela tabela
                        $this->setColumn( $attr, $this->form->$input );

                    }

                }
                
            }
            
        }
           
        # Se não existirem erros o registro é salvo
        if( !$this->checkErroInner() && $this->obj_active_record->is_valid() ){
    
            if( $this->obj_active_record->save( false ) ) return true;
        
        }

        return false;
        
    }
    
    /**
     * Executa o cadastro no banco de dados
     *  @return Boolean
    */
    public function insert(){
        
        try{
            
            $model = $this->model;
                            
            # Pega o nome da coluna da chave primária
            $this->primary_key = $model::$primary_key;
            
            $this->obj_active_record = new $this->model();
            $this->attributes = $this->obj_active_record->attributes();
            
            unset( $this->attributes[ $this->primary_key ] ); 
            
            # Executa e preenche o model com os dados do FORM
            return $this->run();
            
        }catch ( Exception $e ){
            
            echo 'Exception: <strong>PhpActiveRecord</strong> ' . $e->getMessage();
            
        }
            
    }
    
    /**
     * Executa a alteração no banco de dados
     * @param $id > Identificador do item a ser alterado
     * @return Boolean
    */
    public function update( $id ){
        
        try{
            
            # Informa o número do registro
            $this->id = $id;
            
            $model = $this->model;
                
            # Verifica se o arquivo model pertence a PhpActiveRecord  
            //if( $model instanceof ActiveRecord\Model ) throw new Exception ( 'Classe '.$this->model.' não pertence a instancia de PhpActiveRecord' );
                            
            # Pega o nome da coluna da chave primária
            $this->primary_key = $primary_key = $model::$primary_key;
            
            # ID usado para realizar UPDATE
            if( is_numeric( $id ) ){ # Update
                
                # Método de consulta
                $find = 'find_by_' . $primary_key;
                
                # Realiza a consulta
                $this->obj_active_record = $model::$find( $id );
                
                # Verifica se foi encontrado algo
                if( !isset( $this->obj_active_record->$primary_key ) ){    
                    
                    $this->setErroNotFound();  
                    
                    return false;
                                        
                }
                
                $this->attributes = $this->obj_active_record->attributes();
                
                # Executa e preenche o model com os dados do FORM
                return $this->run();
                
            }else{
                $this->setErroInvalidCode();
            }
            
            
        }catch ( Exception $e ){
            
            echo 'Exception: <strong>PhpActiveRecord</strong> ' . $e->getMessage();
            
        }
            
    }
    
    /**
     * Popular um formulário com os dados do banco de dados
    */
    public function populateForm( $id ){
        
        $model = $this->model;
        
        # Método de consulta
        $find = 'find_by_' . $model::$primary_key;
        
        # Realiza a consulta
        $this->obj_active_record = $model::$find( $id );
        
        if( !isset( $this->obj_active_record ) ){    
                    
            $this->setErroNotFound();  

        }else{

            # Busca os atributos do Model
            $this->attributes = $this->obj_active_record->attributes();

            if( sizeof( $this->attributes ) > 0 ){
                 
                foreach( $this->attributes as $column => $value ){
                    
                    $this->setValueForm( $column );
                    
                }
                
            }

        }
                
    }
    
    /**
     * Método responsável por retornar os erros encontrados na instrução DML
     * @return Array
    */
    public function getErro(){

        $list_erro = array();
        
        if( isset( $this->obj_active_record->errors ) ){
            
            if( sizeof( $this->attributes ) > 0 ){
                foreach( $this->attributes as $attr => $value ){
                
                    $erro = $this->obj_active_record->errors->on( $attr );
                    
                    # Verifica se existe mais de um erro para o atributo
                    if( is_array( $erro ) ){
                        foreach( $erro as $e ) $list_erro[] = $e;
                    }else{
                        if( !empty( $erro ) ) $list_erro[] = $erro;
                    }
                            
                }
            }
            
        }
        
        return array_merge( $list_erro, $this->getErroInner() );
            
    }
    
}