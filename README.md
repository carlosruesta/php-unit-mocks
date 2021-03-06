# php-unit-mocks

### Aula 01

* **Testes de unidade devem ser idempotentes** 
    * Independente do número de vezes que os executamos, com a mesma entrada, devem gerar a mesma saída.
    * Se executar do mesmo jeito independente do numero de vezes;
    * Vimos que o nosso teste passou na primeira vez que foi rodado, mas logo após, quando o rodamos novamente, ele falhou.
* Implementamos  injeção de dependências
    * Técnica onde passamos as dependências no construtor de um objeto
    * Permite enviar como dependencias objetos falsos
* Criamos o primeiro dublê de testes
    * É um objeto que se parece com outro, para a realização de testes mais isolados
* Como importar classes com alias (utilizando as)
    * Ou seja, dar outro nome à classe, na hora de importá-la com use
    
### Aula 02

* Criar mock usando o método createMock do PHP unit. 
+ O PHP-Unit cria uma classe vazia que contem todas as assinaturas da classe original usando reflexioon;
+ Para garantir que o método seja chamado 2 vezes e com os parametros corretos
    ```$leilaoDaoMock->expects($this->exactly(2))->method('atualiza')->withConsecutive([$fiat147], [$variant])```
+ Outra forma de instanciar um mock seria utilizando o método mockBuilder do PHPUnit.
    + Neste será possível personalizar muito o mock para se comportar do jeito que eu quero. Exemplos:
        + configurar os parametros do construtor: setConstructorArgs
        + desabilitar o construtor ou outros métodos: disableConstructor
    + Exemplo:
        ``` $leilaoDaoMockByBuilder = $this->getMockBuilder(LeilaoDao::class)->setConstructorArgs([new \PDO('sqllite::memory:')])->getMock();```

+ ***Tipos de dublês de teste***. O PHPUnit nos ajuda a criar mocks de forma muito simples, mas aqui está a descrição de alguns outros:
    + Objetos chamados de ***Dummy*** são objetos que, na verdade, nunca são utilizados. Normalmente servem apenas para preencher os requisitos dos parâmetros de algum método.
    + Já os ***Fakes*** são implementações que realmente funcionam, mas normalmente tomam algum tipo de atalho que não os permitem ser utilizados em produção (um banco de dados em memória, por exemplo).
    + Os ***Stubs*** fornecem respostas pré-definidas às chamadas dos métodos pré-definidos durante o teste. Normalmente não respondem ao que não forem explicitamente programados para responder. 
    + Enquanto isso, ***Spies*** são stubs, que também gravam algum tipo de informação, baseado em como foram utilizados. Um bom exemplo é um serviço de e-mail, que guarda quantas mensagens foram enviadas.
    + Já os ***Mocks*** são os que estamos utilizando. São objetos pré-programados, com expectativas das mensagens (métodos e seus parâmetros) que vão receber.
    + Para conferir estas explicações em mais detalhes, aqui está um artigo super completo de um dos maiores gurus do desenvolvimento de software: [https://martinfowler.com/articles/mocksArentStubs.html].
    
### Aula 03

+ Reorganizamos o código comum dos testes no método setUp
+ Implementamos o enviador do e-mail utilizando uma função simples do PHP que envia e-mail
+ Implementamos um log de erro para tratar uma exceção e evitar que o código quebre. Fizemos utilizando uma função simples do PHP: error_log
+ Implementamos um Mock que lança uma exceção, com willThrowException

### Aula 04

+ Verificar quais argumentos foram passados para um método (com with)
+ Capturar argumentos passados para métodos dos nossos mocks e fazer verificações neles (com willReturnCallback)
+ Como definir a implementação completa de um método do nosso mock (com willReturnCallback)

### Aula 05
+ Métodos estáticos dificultam os nossos testes, já que não podemos fazer a injeção de dependências
+ Chamadas ao sistema podem ser difíceis de testar se não utilizarmos injeção de dependências
+ Um código bem escrito é geralmente mais facilmente testável
+ Um código difícil de testar é um bom indicador de um código "mal escrito"


## Curso Testes de Integração com PHP

### Aula 01: Setup - Definições - Primeiros passos da integração com o banco

+ Testes de integração testam além do nosso código
+ Podemos testar a integração entre várias classes/módulos da aplicação
+ Podemos testar a integração com um sistema externo
    + SGBD (banco de dados)
    + API externa
    + Requisições HTTP para o próprio sistema
+ Como realizar testes que interagem com o banco de dados

### Aula 02: Garantindo a integridade

+ Não devemos utilizar o banco de dados de produção para realizar testes
+ Nossos testes devem ser independentes, para isso cada teste deve deixar o banco de dados clean
+ Transações são nossas amigas ao testar o banco de dados. 
    + Devemos realizar todas as operações SQL de um teste dentro de uma transação
+ SQLite fornece um banco de dados em memória que pode auxiliar (e muito) na performance da suíte de testes

### Aula 03: Data Providers e testes intermediarios

+ Como utilizar data providers
+ O conceito de testes (ou asserts) intermediários

### Aula 04: Testes intermediarios e teste de Update

### Aula 05: Testes de API e uso do Postman

+ Levantar o servidor do PHP
    + php -S localhost:8080
+ Uso de um arquivo rest.php que retorna um json com um array de leiloes
+ Ao testar a nossa "API", vimos que era necessário subir o nosso servidor web.
    + Isso não é interessante que a gente dependa o mínimo possível de infraestrutura. 
    + Quando utilizamos um framework (Symfony, por exemplo), já existem ferramentas que realizam requisições e este tipo de testes de integração, sem precisarmos subir um servidor.

+ Como realizar testes funcionais das nossas APIs
+ Como utilizar o Postman para automatizar os nossos testes