<?php 
	require_once "../wdForm-1.0/wdForm.php";

	$objForm = new wdForm();
	$objForm->nome = array("label|Nome");
	$objForm->genero = array("type|radio", "value"=>array("Masculino|m", "Feminino|f"), "checked|f");
	$objForm->interesses = array("type|checkbox", "value"=>array("Carro", "Moto", "Bike"), "checked"=>array("Bike", "Carro"));
	$objForm->estado = array("type|select", "value"=>array("Minas Gerais|mg", "São Paulo|sp", "Rio de Janeiro|rj"), "selected|sp");
	$objForm->senha = array("type|password");
	$objForm->foto = array("type|file", "dir|upload", "count|5", "countrequired|3", "minsize|100");
	$objForm->descricao = array("type|textarea");

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
	
	<?php echo $form; ?>

</body>
</html>