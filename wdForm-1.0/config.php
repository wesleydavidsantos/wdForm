<?php
/**
 * Informa os tipo de inputs existentes e suas classes
*/
$wdForm_config['types_inputs'] = array(
                                        'form'     => 'FormInput',
                                        'text'     => 'BasicInput',
                                        'search'   => 'BasicInput',
                                        'email'    => 'BasicInput',
                                        'password' => 'BasicInput',
                                        'button'   => 'BasicInput',
                                        'submit'   => 'BasicInput',
                                        'image'    => 'BasicInput',
                                        'hidden'   => 'BasicInput',
                                        'select'   => 'SelectInput',   
                                        'checkbox' => 'CheckBoxInput',
                                        'radio'    => 'RadioInput',
                                        'textarea' => 'TextAreaInput',
                                        'file'     => 'FileInput',
                                     );
                             
/**
 * Armazena as configura��es padr�o para upload de arquivos
*/         
$wdForm_config['upload'] = array('maxsize'=>'2mb', 'minsize'=>'10kb', 'type_valid'=>array('image/jpeg', 'image/jpg', 'image/png') );

/**
* Extens�es de upload inv�lidas
*/
$wdForm_config['uploadBadExtensions'] = array('file/php', 'file/php3', 'file/phtml', 'file/exe', 'file/cfm', 'file/shtml', 'file/asp', 'file/pl', 'file/cgi', 'file/sh', 'file/vbs', 'file/jsp');

/**
 * Informa o diret�rio onde se encontra os modelos de wdForm
*/
$wdForm_config['path_models'] =  WDFORM_INCLUDE_PATH . DIRECTORY_SEPARATOR .'models-form' . DIRECTORY_SEPARATOR;

/**
 * Atributos que devem ser removidos de todos os INPUTS
*/
$wdForm_config['attributes_remove'] = array('format', 'validate', 'object', 'unique');

/**
 * Mensagens de erro default
 */
$wdForm_config['msg_default_class'] = array(
                                             'RequiredBasic'=>'Campo %s obrigat�rio',
                                             'RequiredObject'=>'Campo %s inv�lido',
                                             'RequiredUpload'=>array(
                                                                       'countrequired'=>'Selecione no m�nimo %s',
                                                                       'dir'=>'Diret�rio para upload n�o informado.',
                                                                       'bad_extensions'=>'Extens�o de arquivo inv�lido %s',
                                                                       'types_valid'=>'Tipo de arquivo inv�lido %s',
                                                                       'maxsize'=>'Tamanho m�ximo de arquivo inv�lido %s',
                                                                       'minsize'=>'Tamanho m�nimo de arquivo inv�lido %s',
                                                                     ),
                                             'input'=>array(
                                                            'valueIsValidInputChoice'=>'%s com valor inv�lido',
                                                            'validateValue'=>'%s com valor inv�lido',

                                                           ),
                                             'DmlAbstract'=>array(
                                                                  'unique'=>'Este %s j� foi cadastrado anteriormente',
                                                                  'not_found'=>'Registro n�o encontrado no banco de dados',
                                                                  'invalid_code'=>'C�digo do registro inv�lido'
                                                                 ),
                                           );