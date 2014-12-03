<?php

/**
 * Classe que armazena as informa��es dos arquivos que sofreram upload
*/
class FileUpload{
    
    /**
     * Nome do arquivo
     * @type String
    */
    private $name;
    
    /**
     * Informa��es sobre o arquivos
     * @type Array
    */
    private $inf;
    
    /**
     * Construtor
     * @param $name > Nome do arquivo
     * @param $inf > Informa��es sobre o arquivo
    */
    public function __construct( $name, $inf ){
        $this->name = $name;
        $this->inf = $inf;
    } 
    
    /**
     * Retorna o nome do arquivo
     * @return String
    */
    public function getName(){
        return $this->name;
    }
    
    /**
     * Retorna a informa��o espec�fica desejada pelo usu�rio
     * @return Mixed
    */
    public function __get( $require ){
        
        if( $this->inf[ $require ] ) 
            return $this->inf[ $require ];
            
        return '';  
        
    }
    
    /**
     * Retorna todas as informa��es ao usu�rio
     * @return Array
    */
    public function getAllInf(){
        return $this->inf;
    }
    
}

/**
 * Classe respons�vel por realizar o Upload dos arquivos
*/
class Upload{

    /**
     * Armazena o objeto de FORM
    */
    private $form;

    /**
     * Armazena os INPUTS do tipo FILE
    */
    private $inputsFile;
    
    /**
     * Armazena o nome dos arquivos enviados para upload
     * @type Array
    */
    private $name_files_upload;
    
    /**
     * Armazena os INPUTS com erro
    */
    private $inputsErro;
    
    /**
     * Armazena as mensagens de erros do Upload
     * @Ex Arquivo inv�lido, Arquivo tamanho inv�lido
     * @type MsgErro
    */
    private $erroUpload;
    
    /**
     * Construtor
    */
    public function __construct( wdForm $form ){
        
        $this->form = $form;
        
        $this->files_upload = array();
        
        $this->inputsErro = array();
        
        # Pega todos os INPUTS
        $list_inputs = $this->form->getAllInputs();
        
        # Armazena as mensagens de erro nos uploads
        $this->erroUpload = new MsgErro();
        
        try{
            
            # Faz a sele��o dos INPUTS do tipo FILE
            if( sizeof( $list_inputs ) > 0 ){
                
                foreach( $list_inputs as $name => $input ){
                    # @var $name > Nome do INPUT
                    
                    # Verifica se o INPUT � do tipo FILE
                    if( $input->type == 'file' ){
                        
                        # � obrigat�rio informar o nome do diret�rio para upload dos arquivos
                        if( $input->dir == '' ) throw new Exception( 'Diret�rio para upload n�o informado. Input <strong>'.$name.'</strong><br />' );
                    
                        $this->inputsFile[ $name ] = $input;
                        
                    }
                    
                }
                
            }
            
        }catch( Exception $e ){
               echo 'Exception Upload:' . $e->getMessage();
        }
                    
    }
    
    /**
     * Retorna os INPUTS que gerou erros
     * @Obs Usar ap�s chamar m�todo uploadFiles()
     * @return Array
    */
    public function getInputsErro(){
        return $this->inputsErro;
    }
    
    /**
     * Retorna os erros de Upload
     * @Ex Tipo de arquivo inv�lido, Tamanho de arquivo inv�lido
     * @return Object MsgErro
    */
    public function getErroUpload(){
        return $this->erroUpload;
    }
    
    /**
     * M�todo que realiza o UPLOAD de todos os arquivos anexados
     * @return Array - Nome dos arquivos
    */
    public function uploadFiles(){

        if( sizeof( $this->inputsFile ) > 0 ){
            
            try{
                
                foreach( $this->inputsFile as $name => $input ){
                    # @var $name > Nome do INPUT
                    
                    # Se existir uma barra no final ela � retirada 
                    $dir = rtrim( $input->dir, '/' ) . '/';
    
                    # Verifica se o diret�rio existe, se n�o existir ent�o ele � criado
                    if( !(file_exists( $dir ) && is_dir( $dir ) ) ){
                        mkdir($dir, 0755);
                    }else{
                        # Verifica se o diret�rio tem permiss�o de escrita
                        if(!is_writable($dir)) chmod($dir, 0755);
                    }
    
                    $this->upload( $name, $dir );
        
                }
                
            }catch( Exception $e ){
                echo 'Exception Upload:' . $e->getMessage();
            }
        
        }

        if( sizeof( $this->files_upload ) == 1 ) {
            if( isset( $this->files_upload[0] ) ) return $this->files_upload[0];   
        }
        
        return $this->files_upload;

    }
    
    /**
     * Fun��o que realiza o Upload dos arquivos
     * @param $name - Nome do INPUT file
     * @param $directory - Nome do diret�rio onde ser� armazenado os arquivos
    */
    private function upload( $name, $directory ){

        $files = Upload::getFiles( $name );

        try{
                
            if( sizeof( $files ) > 0 ){
    
                foreach( $files as $f ){
    
                    $type = explode( '/', $f['type'] );
    
                    $name_arq = md5( uniqid(rand(), true) ) . '.' . $type[1]; # Gera um nome de forma aleat�ria
    
                    /* Faz o upload do arquivo para seu respectivo caminho */
                    if( move_uploaded_file( $f["tmp_name"], $directory . $name_arq ) ){
                        if( sizeof( $files ) == 1 )
                            $this->files_upload[$name] = new FileUpload( $name_arq, $f );
                        else
                            $this->files_upload[$name][] = new FileUpload( $name_arq, $f );
                    }else{
                        $this->inputsErro[] = $f;
                        //throw new Exception ( 'Exception Upload: Erro ao realizaro o upload do arquivo <strong></pre>'.print_r($f).'</pre></strong>' );                    
                    }
    
                }
    
            }
            
        }catch ( Exception $e ){
            echo $e->getMessage();
        }

    }
    
    /**
     * M�todo que retorna os dados dos arquivos de upload
     * @param $name - Nome do INPUT file
     * @return Array
    */ 
    public static function getFiles( $name ) {

        $name = str_replace( '[]', '', $name );
        
        if( !isset( $_FILES["$name"] ) ) return array();
        
        $file_post = $_FILES["$name"];
          
        $files = array();
        $file_count = count($file_post['name']);

        if( $file_count > 0 ){

            $file_keys = array_keys($file_post);

            for ($i=0; $i<$file_count; $i++) {

                foreach ($file_keys as $key) {

                    if( $file_post['error'][$i] == 0 ) 
                        $files[$i][$key] = $file_post[$key][$i];

                }

            }

        }
  
        return $files;

    }
    
    /**
     * Verifica se algum file foi enviado
     * @return BOOLEAN
    */
    protected function issetFile(){

        return ( isset( $_FILES ) && sizeof( $_FILES ) > 0 ) ? true : false;

    }

}