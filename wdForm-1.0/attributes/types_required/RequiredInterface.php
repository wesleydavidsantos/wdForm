<?php
require_once 'RequiredObject.php';
require_once 'RequiredBasic.php';
require_once 'RequiredUpload.php';

/**
 * Interface obrigat�ria a todos os tipo de atributos
*/
interface RequiredInterface{

    public function __construct( Input $input );

}