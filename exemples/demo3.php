<?php 
	require_once "../wdForm-1.0/wdForm.php";
	require_once "php-activerecord/ActiveRecord.php";
	require_once "php-activerecord/config_php_activerecord.php";

	$objForm = new wdForm();
	$objForm->db->setModel("dbUsuario");
	$objForm->nome = array("label|Nome Completo");
	$objForm->emailuser = array("unique", "label|E-mail", "placeholder|Informe seu e-mail");
	$objForm->senha = array("type|password");
	$objForm->sexo = array("type|radio", "value"=>array("Masculino|m", "Feminino|f"));
	$objForm->interesses = array("type|checkbox", "value"=>array("Carro|carro", "Moto|moto", "Bike|bike"));
	$objForm->cidade = array("type|select", "value"=>mountArrayWdForm( dbCidade::all() ));
	$objForm->foto = array("type|file", "count|5", "countrequired|3", "dir|upload");
	
	if( $objForm->checkSubmit() ){

		$upload = $objForm->uploadFiles();	
		$objForm->unsetInput( 'foto' );
		
		if( $objForm->db->update( 5 ) ){

			$usuario = $objForm->db->getObj();

			foreach ($upload['foto'] as $foto) {

				$f = new dbFoto();
				$f->setFkUsuarioId( $usuario->getId() );
				$f->setNome( $foto->getName() );
				$f->save();

			}

		}

	}else{

		$objForm->db->populateForm( 5 );

	}


	$form = $objForm->createForm("Basic.xml");

?>

<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta charset="UTF-8">
	<title>Formulário wdForm</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/bootstrap.js"></script>
</head>

<body>
	
	<h1>Criando um formulário de forma simples</h1>
	
	<?php 
		if( !$objForm->existErro() ){ 
			echo $form;
		}
	?>

	<?php echo $objForm->getAllErros(); ?>

</body>
</html>