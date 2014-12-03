<?php 
	require_once "../wdForm-1.0/wdForm.php";

	$objForm = new wdForm();
	$objForm->nome_label = 'Nome Completo';

	if( $objForm->checkSubmit() ){

		echo "<h3>Sucesso</h3>";

		echo $objForm->nome_checked;

		$objForm->db->insert();

	}


	$form = $objForm->createForm( 'Basic.xml' );



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
	
	<?php echo $form; ?>

</body>
</html>