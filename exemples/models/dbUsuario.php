<?php
/**
 * @tableName admin
*/
class dbUsuario extends ActiveRecord\Model{
    
    /**
     * Informa a o nome da tabela 
     * 
     * @access Public static
     * @var String
    */ 
    public static $table_name = 'usuario';

    /**
     * Informa a chave primaria da tabela 
     * 
     * @access Public static
     * @var String
    */ 
    public static $primary_key = 'usuario_id';

    public function getId(){ return $this->usuario_id; }

    public function getNome(){ return $this->nome; }
    public function setNome( $param ){ $this->nome = $param; return $this; }

    public function getEmail(){ return $this->email; }
    public function setEmail( $param ){ $this->email = $param; return $this; }

    public function getSenha(){ return $this->senha; }
    public function setSenha( $param ){ $this->senha = $param; return $this; }

    public function getSexo(){ return $this->sexo; }
    public function setSexo( $param ){ $this->sexo = $param; return $this; }

    public function getInteresses(){ return explode(',', $this->interesses); }
    public function setInteresses( $param ){ $this->interesses = implode(',', $param); return $this; }
    
    public function getCidade(){ return $this->cidade; }
    public function setCidade( $param ){ $this->cidade = $param; return $this; }    

}