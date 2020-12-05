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