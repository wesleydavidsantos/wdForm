<?php
/**
 * Função responsável por retorna o array no formato usado para o select, checkbox do wdForm
 * @param $values > Valores repassados para montagem do select, checkbox.
 * @param $type > Como o valor esta sendo repassado Object | Array
 * @obs $values > Mixed - Object | Array
 * 
 * @return Array
*/
function mountArrayWdForm( $values, $type='object' ){

    $list = array();  
    if( sizeof( $values ) > 0 ){
     
        if( $type == 'object' ){
            foreach( $values as $item ){
                
                $list[] =  $item->getName() . '|' . $item->getId();
                
                
            }
        }else{
            foreach( $values as $id => $nome ){
                $list[] =  $nome . '|' . $id;
            }
        }
        
    }
    
    return $list;
    
}