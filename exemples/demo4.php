<?php 
	require_once "../wdForm-1.0/wdForm.php";
	require_once "obj/User.php";
	require_once "php-activerecord/ActiveRecord.php";
	require_once "php-activerecord/config_php_activerecord.php";

	$objForm = new wdForm();
	$objForm->setNameKeyValidForm("cadastro");
	$objForm->db->setModel("dbUsuario");
	$objForm->nome = array("label|Nome Completo", "id|%label");
	$objForm->valor = array("label|Valor produto", "id|produto");
	$objForm->qtd = array("label|Quantidade de itens", "id|qtd", "onkeyup|soma('%valor_id%', '%id%');");
	$objForm->total = array("label|Total", "id|total");


	if( $objForm->checkSubmit() ){

		echo "<h4>Sucesso</h4>";

		$objForm->unsetInput('nome');

	}

	$objForm->valor = '10000';

?>

<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta charset="UTF-8">
	<title>Formulário wdForm</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/bootstrap.js"></script>

<script>
	function soma( vlr, qtd ){

		var v = parseInt( document.getElementById( vlr ).value );
		var q = parseInt( document.getElementById( qtd ).value );

		document.getElementById( 'total' ).value = v * q;
	}
</script>	
</head>

<body>
	
	<h1>Criando um formulário de forma simples</h1>
	
	<?php //echo $form; ?>

	<?php 
		echo $objForm->nome_input."<br /><br />";
		echo $objForm->valor. "<br /><br />";
	?>

</body>
</html>