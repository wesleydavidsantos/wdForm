<?php
/**
 * Armazena o atributo especial Required que informa quando um input é obrigatório
*/
class RequiredUpload implements RequiredInterface{

    /**
     * Mensagens default de erro
     * @var [type]
     */
    private $default_msg_erro;

    public function __construct( Input $input ){

        $this->default_msg_erro = get_msg_default_class('RequiredUpload');
        
        $config = get_config_wdForm( 'upload' );
        
        $files = Upload::getFiles( $input->name );
        
        # Verifica se tem limite mínimo de arquivos para upload 
        if( is_numeric( $input->countrequired ) ){
            if( sizeof( $files ) < $input->countrequired ){
                $input->setErro( sprintf( $this->default_msg_erro['countrequired'], '<strong>'.$input->countrequired.'</strong> ' . $input->label ) );
            }
        }
        
        
        if( sizeof( $files ) > 0 ){
            
            # Verifica se o usuário informou o diretório para upload dos arquivos
            if( $input->dir == '' ){
                $input->setErro( $this->default_msg_erro['dir'] );
            }

            # Formatos válidos informados pelo usuário
            foreach( $files as $f ){
    
                if( in_array( $f['type'], get_config_wdForm( 'uploadBadExtensions' ) ) ){
                    
                    $input->setErro( sprintf( $this->default_msg_erro['bad_extensions'], '<strong>'.$f['name'].'</strong>' ) );
    
                }else{
                    
                    #Verifica se o usuário passou os tipos de arquivos válidos para upload, senão verifica os tipos default
                    if( isset( $input->checked ) ) 
                        $types_valid = $input->checked;
                    else
                        $types_valid = $config['type_valid'];
                    
                    
                    if( sizeof( $types_valid ) > 0 && !in_array( $f['type'], $types_valid ) ){ 
    
                        $input->setErro( sprintf( $this->default_msg_erro['types_valid'], '<strong>'.$f['name'].'</strong>' ) );
    
                    }else{
                        
                        # Tamanho máximo permitido
                        $maxsize = $input->maxsize;
                        
                        if( !empty( $maxsize ) ){ 
                            $maxsize = $this->formatBytes( $input->maxsize );
                        }else{
                            $maxsize = $this->formatBytes( $config['maxsize'] );
                        }
                        
                        
                        if( $f['size'] > $maxsize ) 
                            $input->setErro( sprintf( $this->default_msg_erro['maxsize'], '<strong>'.$f['name'].'</strong>' ) );


                        # Tamanho mínimo permitido
                        $minsize = $input->minsize;
                        
                        if( !empty( $minsize ) ){ 
                            $minsize = $this->formatBytes( $input->minsize );
                        }else{
                            $minsize = $this->formatBytes( $config['minsize'] );
                        }
                       
                        if( $f['size'] < $minsize ){ 
                            $input->setErro( sprintf( $this->default_msg_erro['minsize'], '<strong>'.$f['name'].'</strong>' ) );
                        }
    
                    }               
    
                }
    
            }
                
        }
            
    }
    
    private function formatBytes( $maxsize ){

        try{
            
            $letters = preg_split ( '/[0-9]/', $maxsize );
        	$maxsize = array(
        			     	  'size'   => str_replace( $letters, '', $maxsize ),
        				      'unit' => strtoupper( join( '', $letters ) )
        	                ); 
            
            $unit = array("B", "KB", "MB", "GB", "TB");
            
            if( !in_array( $maxsize['unit'], $unit ) ) throw new Exception ('Exception: Parâmetro não encontrado para calculo de tamanho de upload.');
    
            $unit = array_flip ( $unit );
            $exp = $unit[ $maxsize['unit'] ];
            
            return pow(1024, $exp) * $maxsize['size'];
            
        }catch( Exception $e ){
            echo $e->getMessage();
        }
        
        
    }
    
}