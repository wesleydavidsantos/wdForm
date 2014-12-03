<?php
/**
 * @tableName admin
*/
class dbFoto extends ActiveRecord\Model{
    
    /**
     * Informa a o nome da tabela 
     * 
     * @access Public static
     * @var String
    */ 
    public static $table_name = 'foto';

    /**
     * Informa a chave primaria da tabela 
     * 
     * @access Public static
     * @var String
    */ 
    public static $primary_key = 'foto_id';

    public function getId(){ return $this->foto_id; }

    public function getFkUsuarioId(){ return $this->fk_usuario_id; }
    public function setFkUsuarioId( $param ){ $this->fk_usuario_id = $param; return $this; }

    public function getNome(){ return $this->nome; }
    public function setNome( $param ){ $this->nome = $param; return $this; }
     
}