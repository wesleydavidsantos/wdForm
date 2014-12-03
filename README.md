# wdForm #

Version 1.0

por Wesley David Santos

## Introdução ##
Um breve resumo do que wdForm é:

- wdForm é um framework para criação de formulários de forma prática e simples.
- O wdForm foi desenvolvido com o foco em possibilitar a fácil criação e validação de formulários
- O wdForm funciona seguindo a estrutura de camadas:

	> Formulário
		> Inputs
			> Atributos
				> Tipos ou Ações


- Com o wdForm você pode criar qualquer tipo de formulário sem a necessidade de escrever uma linha de HTML ou CSS
- O wdForm possibilita de forma simples validar ou formatar qualquer INPUT 
- Com o wdForm facilita a validação e UPLOAD de arquivos 
- Você pode inserir ou alterar informações do banco de dados de forma simples, prática e rápida.


## Requisitos mínimos ##

- PHP 5.3+


## Características ##

- Simples criação input
- Funções de validação
- Funções de formatação
- Funções de validação via object
- Método simples de cadastrar o formulário no banco de dados
- Método simples de retornar um formulário preenchido com informações do banco de dados
- Método simples de alterar informações no banco de dados


## Instalação ##

A configuração é muito fácil e simples. Necesário somente requisitar a classe wdForm.php


## Como usar ##

- Você pode criar um formulário de 3 formas diferentes:

	> Primeira forma de criar um formulário:

		$objWdForm = new wdForm();
		$objwdForm->nome = array("required", "label|Nome Completo");
		$objwdForm->email = array("required", "type|email", "label|E-mail", "validade"=>array("email"));


	> Segunda forma de criar um formulário:

		$form = array(
						"nome"=>array("required", "label|Nome Completo"),
						"email"=>array("required|Informe seu e-mail", "type|email", "label|E-mail", "validade"=>array("email")),
					 );

	 	$objWdForm = new wdForm( $form );


 	> Terceira forma de criar um formulário:
	
		$objWdForm = new wdForm();
		$objwdForm->nome_required = '';
		$objwdForm->nome_label = 'Nome Completo';
	
		$objwdForm->email_required = "Informe seu e-mail";
		$objwdForm->email_type = "email";
		$objwdForm->email_label = "E-mail";
		$objwdForm->email_validate = array("email");


## Criando os INPUTS ##

	$objWdForm = new wdForm();

	<h3>TEXT</h3>
		- O input text é o único input que não tem a necessidade de informar o type.

		Exemplo
			$objWdForm->nome = array("label|Nome Completo");
								OU
			$objWdForm->nome = array("type|text", "label|Nome Completo");


	<h3>PASSWORD</h3>
			$objWdForm->senha = array("type|password");

	<h3>SELECT</h3>
			$objWdForm->cidade = array("type|select", "value"=>array("Belo Horizonte|bh", "São Paulo|sp", "Rio de Janeiro|rj"), "selected|bh");
											OU
			$objWdForm->cidade = array("type|select", "value"=>array("Belo Horizonte", "São Paulo", "Rio de Janeiro"), "selected|Belo Horizonte");
											OU
			$objWdForm->cidade = array("type|select", "value"=>mountArrayWdForm( Cidades::all() )); // Objeto retornado com registros do banco de dados
											OU
			$objWdForm->cidade = array("type|select", "value"=>mountArrayWdForm( array("Belo Horizonte", "São Paulo", "Rio de Janeiro"), "array" )); // Array contendo as informações


	<h3>RADIO</h3>
			$objWdForm->genero = array("type|radio", "value"=>array("Masculino|m", "Feminino|f"), "checked|m");
											OU
			$objWdForm->genero = array("type|radio", "value"=>array("Masculino", "Feminino"), "checked|Masculino");
			
			> Também pode ser utilizado o mountArrayWdForm()

	<h3>CHECKBOX</h3>
			$objWdForm->interesses = array("type|checkbox", "value"=>array("Carro|carro", "Moto|moto", "Bike|bike"), "checked|bike");
											OU
			$objWdForm->interesses = array("type|checkbox", "value"=>array("Carro", "Mototo", "Bike"), "checked|Bike");

			> Também pode ser utilizado o mountArrayWdForm()
	
	<h3>TEXTAREA</h3>
			$objWdForm->descricao = array("type|textarea");

	<h3>FILE</h3>
			$objWdForm->foto = array("type|file", "count|5", "countrequired|3");



## Gerando o formulário ##


	echo $objwdForm->createForm();
				OU
	echo $objwdForm->createForm('Basic.xml'); // Passar como parâmetro o XML do layout
				OU
	echo $objwdForm->nome_input; // Recebe o INPUT pronto para ser usado onde desejar
	echo $objwdForm->email_input; // Recebe o INPUT pronto para ser usado onde desejar


## Validar o SUBMIT ##
	
	// Verifica se o SUBMIT foi requisitado
	if( $objwdForm->checkSubmit() ){
		// Realiza ações 
	}


## Retornar valores do formulário ##
	
	- Você pode requisitar o valor de qualquer atributo do formulário, basta informa o nome do input e o valor e o atributo que deseja

	Exemplo
		- echo $objwdForm->nome_label; // Nome Completo
		- echo $objwdForm->nome; // Retorna o valor informado pelo usuário

		Retornar valores de Inputs especiais SELECT - RADIO - CHECKBOX - FILE

			- echo $objwdForm->cidade_selected; // INPUT SELECT
			- echo $objwdForm->interesses_checked; // INPUT RADIO ou CHECKBOX
			- echo $objwdForm->foto_listfiles; // Retorna o nome dos arquivos, somente após realizado o UPLOAD


## Gravar formulário no Banco de Dados ##

	- Para gravar um formulário no banco de dados é simples, mas você precisa ter uma classe MODEL


	Exemplo

		## INSERT ##

		$objWdForm = new wdForm();
		$objWdForm->db->setModel('dbUser');
		$objwdForm->nome = array("required", "label|Nome Completo");
		$objwdForm->email = array("required", "type|email", "label|E-mail", "validade"=>array("email"));
		
		// Verifica se o SUBMIT foi requisitado
		if( $objwdForm->checkSubmit() ){
			
			# Grava os dados no banco de dados
			if( $objwdForm->db->insert() ){
				// Sucesso
			}else{
				// Erro
			}

		}

		## UPDATE ##

		$objWdForm = new wdForm();
		$objWdForm->db->setModel('dbUser');
		$objwdForm->nome = array("required", "label|Nome Completo");
		$objwdForm->email = array("required", "type|email", "label|E-mail", "validade"=>array("email"));
		
		// Verifica se o SUBMIT foi requisitado
		if( $objwdForm->checkSubmit() ){
			
			# Grava os dados no banco de dados
			if( $objwdForm->db->update( 1 ) ){ // 1 == ID do registro
				// Sucesso
			}else{
				// Erro
			}

		}else{

			// Preenche o formulário com as informações vindas do banco de dados
			$objwdForm->db->populateForm( 1 ); // 1 == ID do registro

		}
		

## Upload de arquivos ##

	- Você pode realizar o upload de quantos arquivos desejar

	Exemplo

		$objWdForm = new wdForm();
		$objwdForm->foto = array("type|file", "label|Cadastre suas fotos", "count|5", "countrequired|3", "maxsize|10mb", "minsize|500kb");
		$objwdForm->anexo = array("type|file", "label|Cadastre os anexos", "count|3", 'type_valid'=>array('file/pdf'));

		// Verifica se o SUBMIT foi requisitado
		if( $objwdForm->checkSubmit() ){

			// Retorna um array contendo as informações dos arquivos
			$upload = $objwdForm->uploadFiles();

			foreach( $upload['foto'] as $foto ){
				echo $foto->getName(); // Nome do arquivo
				var_dump( $foto->getAllInf() ); // Informações do UPLOAD
			}


		}


	Observações

		O input FILE possue algumas funções interessantes, que são:

		> count - criar um array de input file possibilitando assim o upload de vários arquivos com apenas um INPUT
		> countrequired - quantidade mínima de arquivos que devem ser anexados
		> maxsize - tamanho máximo de cada arquivo
		> minsize - tamanho mínimo de cada arquivo
		> type_valid - ARRAY informa os tipos válidos para upload

		Todos essas ações existem de forma default e podem ser configuradas dentro do arquivo config.php



## Observações Importantes ##

	- O nome dos INPUTS não podem ser criados usando o underline

	> Modo correto
		- $objwdForm->nome = array("required", "label|Nome Completo");

	> Modo errado 
		- $objwdForm->nome_usuario = array("required", "label|Nome Completo");






