<?php
/**
 * @tableName admin
*/
class dbCidade extends ActiveRecord\Model{
    
    /**
     * Informa a o nome da tabela 
     * 
     * @access Public static
     * @var String
    */ 
    public static $table_name = 'cidade';

    /**
     * Informa a chave primaria da tabela 
     * 
     * @access Public static
     * @var String
    */ 
    public static $primary_key = 'cidade_id';

    public function getId(){ return $this->cidade_id; }
    public function getName(){ return $this->nome; }
     
}