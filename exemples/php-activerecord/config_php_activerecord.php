<?php

/**
 * ###########################################################################################
 * #######################--DEFINE DADOS CONEX�O COM BANCO DE DADOS--#########################
 * ########################################################################################### 
*/
	define('BD_HOST', 'localhost');
	define('BD_DATABASE', 'wdformdemo');
	define('BD_USER', 'root');
	define('BD_PASS', '');   



/**
 * ###########################################################################################
 * #######################--Conex�o com o Banco de Dados--#######################
 * ########################################################################################### 
*/
    ActiveRecord\Config::initialize(function($cfg)
    {
        
        $cfg->set_model_directory('models');
        $cfg->set_connections(array('development' =>'mysql://'.BD_USER.':'.BD_PASS.'@'.BD_HOST.'/'.BD_DATABASE.'?charset=utf8'));
        
    });
    
    