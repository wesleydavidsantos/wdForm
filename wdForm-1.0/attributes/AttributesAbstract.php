<?php
/**
 * Classe abstrata que organiza a classe de atributos
*/
abstract class AttributesAbstract{

    abstract public function __construct( $action, $value );
    abstract public function __set( $action, $value );
    abstract public function getValue();

}