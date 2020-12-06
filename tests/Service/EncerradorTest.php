<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Service\Encerrador;
use Alura\Leilao\Service\EnviadorEmail;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EncerradorTest extends TestCase
{

    private Encerrador $encerrador;
    private Leilao $leilaoFiat47;
    private Leilao $leilaoVariante;

    /** @var MockObject  */
    private $enviadorEmailMock;

    protected function setUp(): void
    {
        $this->leilaoFiat47 = new Leilao(
            'Fiat 147 0km',
            new \DateTimeImmutable('8 days ago')
        );
        $this->leilaoVariante = new Leilao(
            'Variante 1972 0km',
            new \DateTimeImmutable('10 days ago')
        );

        $leilaoDaoMock = $this->createMock(LeilaoDao::class);
        $leilaoDaoMock->method('recuperarNaoFinalizados')
            ->willReturn([$this->leilaoFiat47, $this->leilaoVariante]);

        /** Garantir que o método seja chamado 2 vezes e com os parametros corretos **/
        $leilaoDaoMock->expects($this->exactly(2))->method('atualiza')
            ->withConsecutive([$this->leilaoFiat47], [$this->leilaoVariante]);

        $this->enviadorEmailMock = $this->createMock(EnviadorEmail::class);
        $this->encerrador = new Encerrador($leilaoDaoMock, $this->enviadorEmailMock);
    }

    public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados()
    {

        $this->encerrador->encerra();

        /** @var Leilao[] $leiloesFinalizados */
        $leiloesFinalizados = [$this->leilaoFiat47, $this->leilaoVariante];
        self::assertCount(2, $leiloesFinalizados);
        self::assertTrue($leiloesFinalizados[0]->estaFinalizado());
        self::assertTrue($leiloesFinalizados[1]->estaFinalizado());
    }

    public function testDeveContinuarOProcessamentoAoEncontrarErroAoEnviarEmail()
    {
        $e = new \DomainException('Erro ao enviar e-mail');
        $this->enviadorEmailMock
            ->expects($this->exactly(2))
            ->method('notificarTerminoLeilao')->willThrowException($e);
        $this->encerrador->encerra();
    }

    public function testSoDeveEnviarLeilaoPorEmailAposFinalizado()
    {
        $this->enviadorEmailMock
            ->expects($this->exactly(2))
            ->method('notificarTerminoLeilao')
            ->willReturnCallback(function (Leilao $leilao) {
                static::assertTrue($leilao->estaFinalizado());
            });

        $this->enviadorEmailMock
            ->expects($this->exactly(2))
            ->method('notificarTerminoLeilao')
            ->with($this->isType('object'));

        $this->encerrador->encerra();
    }
}