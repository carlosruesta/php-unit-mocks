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