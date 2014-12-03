# wdForm #

Version 1.0

por Wesley David Santos

Twitter: <a href="http://www.twitter.com/wesley_dav">@wesley_dav</a>
Linkedin: <a href="http://br.linkedin.com/in/wesleydavidsantos">wesleydavidsantos</a>


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


		> Observação - O atributo que você esta buscando deve ser antes da atribuição a outro atributo



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


## Limpar valore do formulário ##

	- Você pode limpar os valores do formulário de 2 formas

		> clearAllValues();
			- Limpa os valores de todos os campos

		> clearValue( 'nome', 'email', 'senha' );
			- Limpa o valor dos inputs informados via parâmetro

## Excluir um INPUT ##
	
	- Para excluir um input é só informar como parâmetro o nome do INPUT

	Exemplo
		unsetInput( 'nome', 'email' );

		- Você pode passar quantos parâmetro desejar


## Verificar erros submit ##
	
	- Você pode verificar se o formulário gerou erro atráves do método
	
		$objWdForm->existErro(); // Retorna um boolean


	- Existem várias formas de recuperação dos erros gerados.

	> Primeira forma
		- Você pode buscar o erro de cada INPUT em separado

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

	- No momento existe apenas o framework PhpActiveRecord, você pode adicionar novos frameworks de forma simples, basta usar o atual como modelo.
	- PhpActiveRecord - http://www.phpactiverecord.org/


## Observações Importantes ##

	- O nome dos INPUTS não podem ser criados usando o underline

	> Modo correto
		- $objwdForm->nome = array("required", "label|Nome Completo");

	> Modo errado 
		- $objwdForm->nome_usuario = array("required", "label|Nome Completo");


## Vídeos de demostração ##

<a href="">wdForm - Introdução</a>
<a href="">wdForm - Criando os primeiros inputs</a>
<a href="">wdForm - Cadastrar formulário no banco de dados</a>
<a href="">wdForm - Preencher e update no banco de dados</a>
<a href="">wdForm - Detalhando a estrutura do framework</a>



## Se você gostou e quer dar uma força doações são bem vindas ;) ##

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - A maneira fácil e segura de enviar pagamentos online!">
<img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
</form>


<!-- INICIO FORMULARIO BOTAO PAGSEGURO -->
<form action="https://pagseguro.uol.com.br/checkout/v2/donation.html" method="post">
<!-- NÃO EDITE OS COMANDOS DAS LINHAS ABAIXO -->
<input type="hidden" name="currency" value="BRL" />
<input type="hidden" name="receiverEmail" value="wds574@gmail.com" />
<input type="image" src="https://p.simg.uol.com.br/out/pagseguro/i/botoes/doacoes/120x53-doar.gif" name="submit" alt="Pague com PagSeguro - é rápido, grátis e seguro!" />
</form>
<!-- FINAL FORMULARIO BOTAO PAGSEGURO -->
