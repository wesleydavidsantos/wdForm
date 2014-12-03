<?php
/**
 * Classe responsável por tratar os inputs básicos 
 * Ex.: TEXT - BUTTON - HIDDEN, etc
*/
class BasicInput extends InputAbstract implements InputInterface {
    
    /**
     * Retorna o INPUT no formato de HTML
     * 
     * @author Wesley David Santos
     * @link http://www.classmain.com
     * @param string $input
     * @return Html
     */
    public function __toString(){
        
        return '<input ' . $this->mountAttributes() . ' />';
    }
    
    /**
     * Método que retorna todos o input BASIC
     * @return String HTML
    */
    public function getObjSubInput(){
        return $this->__toString();
    }
    
}