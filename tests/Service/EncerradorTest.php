<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Service\Encerrador;
use PHPUnit\Framework\TestCase;

//class LeilaoDaoMock extends LeilaoDao
//{
//    private $leiloes = [];
//
//    public function salva(Leilao $leilao): void
//    {
//        $this->leiloes[] = $leilao;
//    }
//
//    public function recuperarFinalizados(): array
//    {
//        return array_filter($this->leiloes, function(Leilao $leilao) {
//            return $leilao->estaFinalizado();
//        });
//    }
//
//    public function recuperarNaoFinalizados(): array
//    {
//        return array_filter($this->leiloes, function(Leilao $leilao) {
//            return !$leilao->estaFinalizado();
//        });
//    }
//
//    public function atualiza(Leilao $leilao) {}
//
//
//}

class EncerradorTest extends TestCase
{
    public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados()
    {
        $fiat147 = new Leilao(
            'Fiat 147 0km',
            new \DateTimeImmutable('8 days ago')
        );
        $variant = new Leilao(
            'Variante 1972 0km',
            new \DateTimeImmutable('10 days ago')
        );

        $leilaoDaoMock = $this->createMock(LeilaoDao::class);
        $leilaoDaoMock->method('recuperarNaoFinalizados')->willReturn([
            $fiat147, $variant]);

        /** Garantir que o método seja chamado 2 vezes e com os parametros corretos **/
        $leilaoDaoMock->expects($this->exactly(2))->method('atualiza')
        ->withConsecutive([$fiat147], [$variant]);

        /** Outra forma de instanciar um mock seria utilizando o método mockBuilder do PHPUnit
         *  Neste será possível personalizar muito o mock para se comportar do jeito que eu quero
         *  Por exemplo,
         *      - configurar os parametros do construtor: setConstructorArgs
         *      - desabilitar o construtor ou outros métodos: disableConstructor
         */
//        $leilaoDaoMockByBuilder = $this->getMockBuilder(LeilaoDao::class)
//            ->setConstructorArgs([new \PDO('sqllite::memory:')])
//            ->getMock();


        $encerrador = new Encerrador($leilaoDaoMock);
        $encerrador->encerra();

        /** @var Leilao[] $leiloesFinalizados */
        $leiloesFinalizados = [$fiat147, $variant];
        self::assertCount(2, $leiloesFinalizados);
        self::assertTrue($leiloesFinalizados[0]->estaFinalizado());
        self::assertTrue($leiloesFinalizados[1]->estaFinalizado());
    }
}