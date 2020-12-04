<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Service\Encerrador;
use PHPUnit\Framework\TestCase;

class LeilaoDaoMock extends LeilaoDao
{
    private $leiloes = [];

    public function salva(Leilao $leilao): void
    {
        $this->leiloes[] = $leilao;
    }

    public function recuperarFinalizados(): array
    {
        return array_filter($this->leiloes, function(Leilao $leilao) {
            return $leilao->estaFinalizado();
        });
    }

    public function recuperarNaoFinalizados(): array
    {
        return array_filter($this->leiloes, function(Leilao $leilao) {
            return !$leilao->estaFinalizado();
        });
    }

    public function atualiza(Leilao $leilao) {}


}

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

        $leilaoDaoMock = new LeilaoDaoMock();
        $leilaoDaoMock->salva($fiat147);
        $leilaoDaoMock->salva($variant);

        $encerrador = new Encerrador($leilaoDaoMock);
        $encerrador->encerra();

        /** @var Leilao[] $leiloesFinalizados */
        $leiloesFinalizados = $leilaoDaoMock->recuperarFinalizados();
        self::assertCount(2, $leiloesFinalizados);
        self::assertEquals('Fiat 147 0km', $leiloesFinalizados[0]->recuperarDescricao());
        self::assertEquals('Variante 1972 0km', $leiloesFinalizados[1]->recuperarDescricao());
        self::assertTrue($leiloesFinalizados[0]->estaFinalizado());
        self::assertTrue($leiloesFinalizados[1]->estaFinalizado());
    }
}