<?php
/**
 * Classe responsável por tratar os inputs básicos 
 * Ex.: TEXT - BUTTON - HIDDEN, etc
*/
class TextAreaInput extends InputAbstract implements InputInterface {
    
    /**
     * Retorna o INPUT no formato de HTML
     * 
     * @author Wersley David Santos
     * @link http://www.classmain.com
     * @param string $input
     * @return Html
     */
    public function __toString(){
        $remove_attributes = array('value', "label");
        return '<textarea  ' . $this->mountAttributes($remove_attributes) . '>'.$this->input->value.'</textarea>';
    }
    
    /**
     * Método que retorna todos o input TEXTAREA
     * @return String HTML
    */
    public function getObjSubInput(){
        return $this->__toString();
    }
    
}