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
 * Armazena as configurações padrão para upload de arquivos
*/         
$wdForm_config['upload'] = array('maxsize'=>'2mb', 'minsize'=>'10kb', 'type_valid'=>array('image/jpeg', 'image/jpg', 'image/png') );

/**
* Extensões de upload inválidas
*/
$wdForm_config['uploadBadExtensions'] = array('file/php', 'file/php3', 'file/phtml', 'file/exe', 'file/cfm', 'file/shtml', 'file/asp', 'file/pl', 'file/cgi', 'file/sh', 'file/vbs', 'file/jsp');

/**
 * Informa o diretório onde se encontra os modelos de wdForm
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
                                             'RequiredBasic'=>'Campo %s obrigatório',
                                             'RequiredObject'=>'Campo %s inválido',
                                             'RequiredUpload'=>array(
                                                                       'countrequired'=>'Selecione no mínimo %s',
                                                                       'dir'=>'Diretório para upload não informado.',
                                                                       'bad_extensions'=>'Extensão de arquivo inválido %s',
                                                                       'types_valid'=>'Tipo de arquivo inválido %s',
                                                                       'maxsize'=>'Tamanho máximo de arquivo inválido %s',
                                                                       'minsize'=>'Tamanho mínimo de arquivo inválido %s',
                                                                     ),
                                             'input'=>array(
                                                            'valueIsValidInputChoice'=>'%s com valor inválido',
                                                            'validateValue'=>'%s com valor inválido',

                                                           ),
                                             'DmlAbstract'=>array(
                                                                  'unique'=>'Este %s já foi cadastrado anteriormente',
                                                                  'not_found'=>'Registro não encontrado no banco de dados',
                                                                  'invalid_code'=>'Código do registro inválido'
                                                                 ),
                                           );