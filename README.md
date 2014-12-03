# wdForm #

Version 1.0

por Wesley David Santos

Twitter: <a href="http://www.twitter.com/wesley_dav">@wesley_dav</a>
Linkedin: <a href="http://br.linkedin.com/in/wesleydavidsantos">wesleydavidsantos</a>

## Solte sua imaginação ##

	- Imagine um cliente lhe pedindo para criar um formulário com o layout estilizado e que será gravado no banco de dados, sendo que possui os seguintes campos:

		Nome - Tipo texto
		Email - Tipo texto e validar o email
		Username - Tipo texto, sem acentuação ou caracteres especiais e deve ser único no banco de dados
		Gênero - Tipo radiobutton, com os valores Masculino e Feminino
		Interesses - Tipo checkbox, com os valores Carro - Moto - Bike		
		Estado - Tipo select, com o valores Minas Gerais - São Paulo - Rio de Janeiro
		Foto - Tipo file, um total de 5 fotos sendo no mínimo 3 obrigatórias devem estar no formato de JPG ou PNG e com o tamanho mínimo de 500kb e máximo de 2mb

	- E ai conseguiu soltar a imaginação? Então me responda.

	 * Quanto tempo você iria gastar para criar este formulário? 
	 * Quantas linhas de código PHP, HTML e CSS você iria escrever para desenvolver este formulário?

	- E se eu te falar que com apenas 13 linhas de código PHP você conseguiria fazer isso tudo. Isso ser muito bom, então veja a foto abaixo e depois continue sua leitura bem entusiasmado.

	<a href="http://www.classmain.com/wdForm/layout-e-codigo-wdform.jpg"><img src="http://www.classmain.com/wdForm/layout-e-codigo-wdform.jpg" alt=""></a>


## Introdução ##
Um breve resumo do que wdForm é:

- wdForm é um framework desenvolvido com o foco em possibilitar a fácil criação e validação de formulários
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
		$objwdForm->email = array("required", "type|email", "label|E-mail", "validate"=>array("email"));


	> Segunda forma de criar um formulário:

		$form = array(
						"nome"=>array("required", "label|Nome Completo"),
						"email"=>array("required|Informe seu e-mail", "type|email", "label|E-mail", "validate"=>array("email")),
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

	>> TEXT
		- O input text é o único input que não tem a necessidade de informar o type.

		Exemplo
			$objWdForm->nome = array("label|Nome Completo");
								OU
			$objWdForm->nome = array("type|text", "label|Nome Completo");


	>> PASSWORD
			$objWdForm->senha = array("type|password");

	>> SELECT
			$objWdForm->cidade = array("type|select", "value"=>array("Belo Horizonte|bh", "São Paulo|sp", "Rio de Janeiro|rj"), "selected|bh");
											OU
			$objWdForm->cidade = array("type|select", "value"=>array("Belo Horizonte", "São Paulo", "Rio de Janeiro"), "selected|Belo Horizonte");
											OU
			$objWdForm->cidade = array("type|select", "value"=>mountArrayWdForm( Cidades::all() )); // Objeto retornado com registros do banco de dados
											OU
			$objWdForm->cidade = array("type|select", "value"=>mountArrayWdForm( array("Belo Horizonte", "São Paulo", "Rio de Janeiro"), "array" )); // Array contendo as informações


	>> RADIO
			$objWdForm->genero = array("type|radio", "value"=>array("Masculino|m", "Feminino|f"), "checked|m");
											OU
			$objWdForm->genero = array("type|radio", "value"=>array("Masculino", "Feminino"), "checked|Masculino");
			
			> Também pode ser utilizado o mountArrayWdForm()

	>> CHECKBOX
			$objWdForm->interesses = array("type|checkbox", "value"=>array("Carro|carro", "Moto|moto", "Bike|bike"), "checked|bike");
											OU
			$objWdForm->interesses = array("type|checkbox", "value"=>array("Carro", "Mototo", "Bike"), "checked|Bike");

			> Também pode ser utilizado o mountArrayWdForm()
	
	>> TEXTAREA
			$objWdForm->descricao = array("type|textarea");

	>> FILE
			$objWdForm->foto = array("type|file", "count|5", "countrequired|3");


## Atributos especiais ##

	- Você pode criar atributos em que o seu valor pertence a um outro atributo ou a um outro input

	Exemplo
	
		> Atribuição simples de valor

			$objWdForm = new wdForm();
			$objwdForm->senha = array("required", "label|Senha pessoal", "id|%name"); // O 'id' irá possuir o valor do atributo 'name' que é 'senha' 
				OU
			$objwdForm->senha = array("required", "label|Senha pessoal", "id|%label"); // O 'id' irá possuir o valor do atributo 'label' que é 'Senha pessoal' 


		> Atribuição complexa de valor

			$objWdForm = new wdForm();
			$objWdForm->nome = array("label|Nome Completo", "id|%label");
			$objWdForm->itens = array("label|Valor produto", "id|produto");
			$objWdForm->qtd = array("label|Quantidade de itens", "id|qtd", "onkeyup|soma('%itens_id%', '%id%');"); 
			$objWdForm->total = array("label|Total", "id|total");

			> Explicação
				- O primeiro parâmetro da function 'soma' recebe o valor do 'id' do input 'itens'.
				- O segundo parâmetro da function 'soma' recebe o valor do 'id' do próprio input.


		> Observação - O atributo que você esta buscando deve ser criado antes da atribuição a outro atributo



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
	
	- Você pode requisitar o valor de qualquer atributo de um input do formulário, basta informa o nome do input e o nome do atributo que deseja

	Exemplo
		- echo $objwdForm->nome_label; // Nome Completo
		- echo $objwdForm->nome; // Retorna o valor informado pelo usuário

		Retornar valores de Inputs especiais SELECT - RADIO - CHECKBOX - FILE

			- echo $objwdForm->cidade_selected; // INPUT SELECT
			- echo $objwdForm->interesses_checked; // INPUT RADIO ou CHECKBOX
			- echo $objwdForm->foto_listfiles; // INPUT FILE - Retorna o nome dos arquivos, somente após realizado o UPLOAD


## Gravar formulário no Banco de Dados ##

	- Para gravar um formulário no banco de dados é simples, mas você precisa ter uma classe MODEL


	Exemplo

		## INSERT ##

		$objWdForm = new wdForm();
		$objWdForm->db->setModel('dbUser');
		$objwdForm->nome = array("required", "label|Nome Completo");
		$objwdForm->email = array("required", "type|email", "label|E-mail", "validate"=>array("email"));
		
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
		$objwdForm->email = array("required", "type|email", "label|E-mail", "validate"=>array("email"));
		
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
		
		
		## Pegar o objeto usado pelo INSERT ou UPDATE ##

		$objDB = $objwdForm->db->getObj();


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

			foreach( $upload['anexo'] as $foto ){
				echo $foto->getName(); // Nome do arquivo
				var_dump( $foto->getAllInf() ); // Informações do UPLOAD
			}


		}


## Funções de validação ##

	- Você pode validar um campo de varias formas e pode existir varias formas de validação em um único INPUT

	>> UNIQUE <<

		- Usado em formulário que irão inserir ao alterar informações do banco de dados.
		- Quando for realizado a requisição de INSERT ou UPDATE será verificado se o valor já existe cadastrado no banco de dados

		Exemplo

			$objWdForm = new wdForm();
			$objWdForm->cpf = array("label|CPF", "unique");
		


	>> Validate <<
		
		- Usa uma function para validar o valor informado. 
		- Você pode criar novas functions, basta abrir o arquivo 'validate.php' dentro do diretório 'helpers'
		- Você pode adicionar quantas validações precisar.

		$objWdForm = new wdForm();
		$objWdForm->cpf = array("label|CPF", "validate"=>array("cpf")); // Em caso de erro retorna a mensagem de padrão
					OU
		$objWdForm->cpf = array("label|CPF", "validate"=>array("cpf"=>"Informe um cpf válido")); // Em caso de erro retorna a mensagem personalizada


	>> Object <<
		
		- Usa uma objeto qualquer para validar o valor informado. 
		- A açõa realizada deve retornar um valor boolean
		- Você pode adicionar quantas validações precisar.

		$objWdForm = new wdForm();
		$objWdForm->cidade = array("label|CPF", "object"=>array("Cidade->verificaCidade", "Estado::verificaEstado")); // Em caso de erro retorna a mensagem de padrão
					OU
		$objWdForm->cidade = array("label|CPF", "object"=>array("Cidade->verificaCidade"=>"Cidade não existe", "Estado::verificaEstado"=>"Cidade não pertence ao estado")); // Em caso de erro retorna mensagem personalizada

		

	Observações

		O input FILE possui algumas funções interessantes, que são:

		> count - cria um array de input file possibilitando assim o upload de vários arquivos com apenas um INPUT
		> countrequired - quantidade mínima de arquivos que devem ser anexados
		> maxsize - tamanho máximo de cada arquivo
		> minsize - tamanho mínimo de cada arquivo
		> type_valid - ARRAY informa os tipos válidos para upload

		>> Todos essas ações existem de forma default e podem ser configuradas dentro do arquivo config.php


## Funções de formatação ##

	- Você pode formatar um campo de varias formas e pode existir varias formas de formatação em um único INPUT

	Exemplo
		$objWdForm = new wdForm();
		$objWdForm->username = array("label|Nome de Usuário", "format"=>array("removeCharSpecial", "removeHTML")); // Retorna os valores formatados


## Limpar valores do formulário ##

	- Você pode limpar os valores do formulário de 2 formas

		> clearAllValues();
			- Limpa os valores de todos os campos

		> clearValue( 'nome', 'email', 'senha' );
			- Limpa o valor dos inputs informados via parâmetro

## Excluir um INPUT ##
	
	- Para excluir um input é só informar como parâmetro o nome do INPUT

	Exemplo
		unsetInput( 'nome', 'email' );

		- Você pode passar quantos parâmetros desejar


## Verificar erros submit ##
	
	- Você pode verificar se o formulário gerou erro atráves do método
	
		$objWdForm->existErro(); // Retorna um boolean


	- Existem várias formas de recuperação dos erros gerados.

	> Primeira forma
		- Você pode buscar o erro de cada INPUT

			Exemplo

				var_dump( $objWdForm->nome_erro ); // Retorna um objeto do tipo MsgErro

	> Segunda forma

		- Erros gerados pelos INPUTS

			Exemplo

				$objWdForm->getErrosInput(); // Retorna uma lista 'ul'


	> Terceira forma

		- Erros gerados pelas requisições ao banco de dados

			Exemplo

				$objWdForm->getErrosDml(); // Retorna uma lista 'ul',


	> Quarta forma

		- Retorna todos os erros juntos. INPUTS e Requisições ao banco de dados

			Exemplo

				$objWdForm->getAllErros(); // Retorna uma lista 'ul'



## Modelos de Formulário ##

	- Você pode criar vários modelos de layout de formulário e utiliza-los em qualquer instância do wdForm
	- Os modelos de layout estão armazenados dentro do dirétorio 'models-form' 


## Framework do banco de dados ##
	
	- Os arquivos DML estão localizados dentro do diretório 'lib_dml'.
	- No momento existe apenas o framework PhpActiveRecord, você pode adicionar novos frameworks de forma simples, basta usar o atual como modelo.
	- PhpActiveRecord - http://www.phpactiverecord.org/


## Observações Importantes ##

	- O nome dos INPUTS não podem ser criados usando o underline

	> Modo correto
		- $objwdForm->nome = array("required", "label|Nome Completo");

	> Modo errado 
		- $objwdForm->nome_usuario = array("required", "label|Nome Completo");


## Vídeos de demostração ##

<a href="http://youtu.be/GSt_3FoLBq4">wdForm - Introdução</a> <br /><br />
<a href="http://youtu.be/RxI-kGYly1E">wdForm - Criando os primeiros inputs</a> <br /><br />
<a href="http://youtu.be/hOqf7RrZ0_E">wdForm - Cadastrar formulário no banco de dados</a> <br /><br />
<a href="http://youtu.be/QPaVmbGWMLw">wdForm - Preencher e update no banco de dados</a> <br /><br />
<a href="">wdForm - Detalhando a estrutura do framework</a> <br /><br />



## Se você gostou e quer dar uma força doações são bem vindas ;) ##

<a href="http://www.classmain.com/wdForm/paypal.html"><img src="http://www.classmain.com/wdForm/doar-paypal.jpg" alt=""></a><br /><br />
<a href="http://www.classmain.com/wdForm/pagseguro.html"><img src="http://www.classmain.com/wdForm/doar-pagseguro.gif" alt=""></a><br /><br />
<a href='https://pledgie.com/campaigns/27586'><img alt='Click here to lend your support to: wdForm - php and make a donation at pledgie.com !' src='https://pledgie.com/campaigns/27586.png?skin_name=chrome' border='0' ></a> <br /><br />





