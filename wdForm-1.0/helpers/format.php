<?php
/*********************************************************************
 * Arquivo contendo fun��es para formatar os valores dos INPUTS ******
 * Os.: Todas as functions devem retornar o valor alterado       *****
 ********************************************************************* 
*/
/**
 * Fun��o respons�vel por remover acentua��es e caracteres especiais
 * @return String strtolower
*/
if ( ! function_exists('wd_removeCharSpecial')){   
    function wd_removeCharSpecial ( $param ) {
 	      
        $arr = array('.', '-','�','`','�','^','~','$','!',',',';',':','?','[','@','#','%','&','*','(',')','_','+','{','}','<','>','/','=','�','�','�','�','�','�','�','�','�',']',"'",'�');
    	
        $str = str_replace($arr, '', $param);
    
    	$acentos = array(
    		'A' => '/�|�|�|�|�|�/', 
    		'a' => '/�|�|�|�|�|�/', 
    		'C' => '/�/', 
    		'c' => '/�/', 
    		'E' => '/�|�|�|�/', 
    		'e' => '/�|�|�|�/', 
    		'I' => '/�|�|�|�/', 
    		'i' => '/�|�|�|�/', 
    		'N' => '/�/', 
    		'n' => '/�/', 
    		'O' => '/�|�|�|�|�/', 
    		'o' => '/�|�|�|�|�/', 
    		'U' => '/�|�|�|�/', 
    		'u' => '/�|�|�|�/', 
    		'Y' => '/�/', 
    		'y' => '/�|�/', 
    		'a.' => '/�/', 
    		'-' => '/ |"|�|�|�/', 
    		'o.' => '/�/'
    	);
    	$res = preg_replace( $acentos, array_keys($acentos), utf8_decode( $str ) );
    
    	$res = str_replace('----', '-', $res);
    	$res = str_replace('---', '-', $res);
    	$res = str_replace('--', '-', $res);
    
    	$exp = strrev($res);
    	$exp = substr($exp, 0, 1);
    	if ($exp == '-') $res = substr($res, 0, (strlen($res)-1));
    	
    	return strtolower($res);
        
    }
}

/**
 * Fun��o utilizado para retirar TAG'S HTML
 * @param $param > Remove todas as tag's HTML
*/
if ( ! function_exists('wd_removeHTML')){ 
    function wd_removeHTML( $param ){
        return @strip_tags( $param );
    }
}

/**
 * Fun��o utilizada para retirar transformar aspas em valores HTML
 * @param $param > Texto a ser alterado
*/
if ( ! function_exists('wd_validAspa')){ 
    function wd_validAspa( $param ){
        
        $post = trim($param);
        $post = str_replace("\'", "&#39;", $post);
        $post = str_replace('\"', '&#34;', $post);
        $post = str_replace("'", "&#39;", $post);
        $post = str_replace('"', '&#34;', $post);
     
        return $post;
                
    }
}

/**
 * Fun��o utilizada para remover JavaScript de textos
 * @param $param > String conte�do a ser modificado
 * @return String
*/
if ( ! function_exists('wd_removeJS')){ 
    function wd_removeJS( $param ){
        
       $javascript = '/<script[^>]*?javascript{1}[^>]*?>.*?<\/script>/si';
       $noscript = '';
       return preg_replace($javascript, $noscript, $param);
        
    }
}

/**
 * Retorna a data no formato para Mysql
*/
if ( !function_exists('wd_dtMysql')){ 
    function wd_dtMysql( $param ){
        $param = str_replace("/", "-", $param);
        return date("Y-m-d",strtotime($param));
    }
}

/**
 * Retorna data e hora no formato Brasileiro
*/
if ( ! function_exists('wd_dtBR')){ 
    function wd_dtBR( $param ){        
        $param = str_replace("-", "/", $param);
        return date("d/m/Y",strtotime($param));
        
    }
}

/**
 * M�todo que retorna o valor desejado para moeda desejada
 * setlocale(LC_MONETARY, ''); Define em que formato dever� ser exibido o valor
*/
if ( ! function_exists('wd_money')){ 
    function wd_money($param){
        
        if( strpos($param, ',') !== false ) $param = str_replace(',', '.', $param);

        setlocale(LC_MONETARY, 'en_US.UTF-8');
        return money_format('%i', $param);
        
    }
}

/**
 * M�todo que retorna o valor desejado para moeda desejada
 * setlocale(LC_MONETARY, ''); Define em que formato dever� ser exibido o valor
*/
if ( ! function_exists('wd_real')){ 
    function wd_real($param){
        
        setlocale(LC_MONETARY, 'pt_BR.UTF-8');
        return money_format('%.2n', $param);
        
    }
}