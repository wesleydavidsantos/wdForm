<?php
/*********************************************************************
 * Arquivo contendo funções para validação de valores dos INPUTS *****
 * Os.: Todas as functions devem retornar valores BOOLEAN        *****
 ********************************************************************* 
*/


/**
* Verifica se um valor é INTEGER
* @return Boolean
*/
if ( ! function_exists('wd_integer')){   
	function wd_integer( $param ){
	    return ( filter_var($param, FILTER_VALIDATE_INT) === false ) ? false : true; 
	}
}

/**
* Verifica se uma senha é forte
* @author da ER > LeandroLRB
* @link https://groups.google.com/forum/#!topic/listaphp/4-RCsMu4NrE
* @return Boolean
*/
if ( ! function_exists('wd_password')){   
    function wd_password( $param ){
        if (preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/",  $param)) return true;
        return false;
    }
}

/**
* Verifica se um valor é INTEGER
* @return Boolean
*/
if ( ! function_exists('wd_float')){   
	function wd_float( $param ){
	    return ( filter_var($param, FILTER_VALIDATE_FLOAT) === false ) ? false : true; 
	}
}

/**
* Verifica se um valor é DOUBLE
* @return Boolean
*/
if ( ! function_exists('wd_double')){   
	function wd_double( $param ){
	    return wd_float( $param ); 
	}
}

/**
* Verifica se um valor é NUMERIC
* @return Boolean
*/
if ( ! function_exists('wd_numeric')){   
	function wd_numeric( $param ){
		return is_numeric( $param );
	}
}

/**
* Verifica se um valor é BOOLEAN
* @return Boolean
*/
if ( ! function_exists('wd_boolean')){   
	function wd_boolean( $param ){
		return ( filter_var($param, FILTER_VALIDATE_BOOLEAN) === false ) ? false : true; 
	}
}

/**
* Verifica se um valor é E-MAIL
* @return Boolean
*/
if ( ! function_exists('wd_email')){   
	function wd_email( $param ){
		return ( filter_var($param, FILTER_VALIDATE_EMAIL) === false ) ? false : true; 
	}
}

/**
* Verifica se um valor é IP
* @return Boolean
*/
if ( ! function_exists('wd_ip')){   
	function wd_ip( $param ){
		return ( filter_var($param, FILTER_VALIDATE_IP) === false ) ? false : true; 
	}
}

/**
* Verifica se um valor é URL
* @return Boolean
*/
if ( ! function_exists('wd_url')){   
	function wd_url( $param ){
		return ( filter_var($param, FILTER_VALIDATE_URL) === false ) ? false : true; 
	}
}

/**
 * Função utilizado para verificar se um site existe
 * @param $param > URL do site
 * @return Boolean
*/
if ( ! function_exists('wd_urlAtive')){  
    function wd_urlAtive( $param ){
        
        if(!filter_var($param, FILTER_VALIDATE_URL))
            return false;
        
        $ch = curl_init($param);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($ch);
        curl_close($ch);
        return $r ? true : false;
        
    }
}

/**
 * Função utilizada para verificar se uma data é válida
 * @param $param > Data
 * @return Boolean
*/
if ( ! function_exists('wd_date')){  
    function wd_date( $param ){
        
        $param = str_replace("/", "-", $param);
    
        if( date('Y-m-d', strtotime( $param )) == $param || date('d-m-Y', strtotime( $param )) == $param )  
            return TRUE;
        
        return FALSE;
        
    }
}

/**
 * Função utilizada para válidação de CPF
 * @param $cpf > Número do CPF
 * @return Boolean
*/
if ( ! function_exists('wd_cpf')){  
    function wd_cpf($cpf)
    {
        $cpf = str_replace('.', '', $cpf);
        $cpf = str_replace('-', '', $cpf);
        
        $cpf = preg_replace('/[^0-9]/','',$cpf);
 
        if(strlen($cpf) != 11 || preg_match('/^([0-9])\1+$/', $cpf))
        {
            return false;
        }
 
        // 9 primeiros digitos do cpf
        $digit = substr($cpf, 0, 9);
 
        // calculo dos 2 digitos verificadores
        for($j=10; $j <= 11; $j++)
        {
            $sum = 0;
            for($i=0; $i< $j-1; $i++)
            {
                $sum += ($j-$i) * ((int) $digit[$i]);
            }
 
            $summod11 = $sum % 11;
            $digit[$j-1] = $summod11 < 2 ? 0 : 11 - $summod11;
        }
        
        return $digit[9] == ((int)$cpf[9]) && $digit[10] == ((int)$cpf[10]);
    }
}

/**
 * Função utilizada para válidação de CNPJ
 * @param $cnpj > Número do CNPJ
 * @return Boolean
*/
if ( ! function_exists('wd_cnpj')){  
    
    function wd_cnpj($cnpj){
      $cnpj = str_pad(str_replace(array('.','-','/'),'',$cnpj),14,'0',STR_PAD_LEFT);
      if (strlen($cnpj) != 14){
        return false;
      }else{
        for($t = 12; $t < 14; $t++){
          for($d = 0, $p = $t - 7, $c = 0; $c < $t; $c++){
            $d += $cnpj{$c} * $p;
            $p  = ($p < 3) ? 9 : --$p;
          }
          $d = ((10 * $d) % 11) % 10;
          if($cnpj{$c} != $d){
            return false;
          }
        }
        return true;
      }
    }
}

/**
 * Verifica se CEP é válido
 * @param	string
 * @return	bool
 */
if ( ! function_exists('wd_cep')){  
    function wd_cep($cep){
    
        $cep = str_replace('.', '', $cep);
        $cep = str_replace('-', '', $cep);
    
        $url = 'http://republicavirtual.com.br/web_cep.php?cep='.urlencode($cep).'&formato=query_string';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 0);
    
        $resultado = curl_exec($ch);
        curl_close($ch);
    
        if( ! $resultado)
            $resultado = "&resultado=0&resultado_txt=erro+ao+buscar+cep";
    
        $resultado = urldecode($resultado);
        $resultado = utf8_encode($resultado);
        parse_str( $resultado, $retorno);
    
        if($retorno['resultado'] == 1 || $retorno['resultado'] == 2)
            return TRUE;
        else
            return FALSE;
    }
}
 
/**
 * Validação simples de telefone
 * @param	string
 * @return	bool
 */
if ( ! function_exists('wd_phone')){       
    function wd_phone($fone){
        $fone = preg_replace('/[^0-9]/','',$fone);
        $fone = (string) $fone;
 
        if( strlen($fone) >= 10)
            return TRUE;
        else
            return FALSE;
    }
}


/**
 * Verifica se é decimal, mas com virgula no lugar de .
 * @param	string
 * @return	bool
 */
if ( ! function_exists('wd_decimal_br')){      
	function wd_decimal_br($param){
        return (bool) preg_match('/^[\-+]?[0-9]+\,[0-9]+$/', $param);
	}
}

/**
 * Verifica se existe caracter especial no texto
*/
if( ! function_exists( 'wd_notCharSpecial' ) ){
    function wd_notCharSpecial( $param ){
        return ( $param == wd_removeCharSpecial( $param ) ) ? TRUE : FALSE;
    }
}


/**
 * Verifica se o site possui algum aviso de Malware
*/
if( ! function_exists( 'wd_urlMalware' ) ){
    function wd_urlMalware( $param ){
        # Verifica se o site tem alerta de Malware
        $valida_malware = @file_get_contents( 'http://www.google.com.br/interstitial?url=' . $url );
        @preg_match( "/<title>([a-z 0-9]*)<\/title>/si", $valida_malware, $match );
        return strip_tags(@$match[ 1 ]) == 'Alerta de Malware' ? true : false;       
    }
}