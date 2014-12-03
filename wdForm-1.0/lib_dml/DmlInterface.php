<?php
/**
 * Interface para as classes de DML Data Manipulation Language
 * # INSERT
 * # UPDATE
*/

interface DmlInterface{
    
    public function insert();
    public function update( $id );
    public function getErro();  
    public function populateForm( $id );
    
}