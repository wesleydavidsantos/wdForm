<?php
/**
 * Classe responsável por tratar o input do tipo FORM
*/
class FormInput extends InputAbstract implements InputInterface {
    
    /**
     * Retorna o INPUT no formato de HTML
     * 
     * @author Wersley David Santos
     * @link http://www.classmain.com
     * @param string $input
     * @return Html
     */
    public function __toString(){
        return '<form ' . $this->mountAttributes( array('type') ) . '>';
    }
    
    /**
     * Método que retorna todos o input FORM
     * @return String HTML
    */
    public function getObjSubInput(){
        return $this->__toString();
    }
    
}