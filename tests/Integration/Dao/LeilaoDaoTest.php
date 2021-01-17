<?php

namespace Alura\Leilao\Integration\Dao;

use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Dao\Leilao as LeilaoDao;
use PHPUnit\Framework\TestCase;

class LeilaoDaoTest extends TestCase
{

    /** @var \PDO */
    private static $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = new \PDO('sqlite::memory:');
        self::$pdo->exec('
            create table leiloes (
                id INTEGER primary key,
                descricao TEXT,
                finalizado BOOL,
                dataInicio TEXT
            );');
    }

    protected function setUp(): void
    {
        self::$pdo->beginTransaction();
    }

    /**
     * @dataProvider leiloes
     */
    public function testDeveBuscarLeiloesNaoFinalizados(array $leiloes)
    {
        // qrrange - given
        $leilaoDao = new LeilaoDao(self::$pdo);
//        $leilaoNaoFinalizado = new Leilao("Variante 0km");
//        $leilaoFinalizado = new Leilao("Fiat 147 0Km");
//        $leilaoFinalizado->finaliza();
        foreach ($leiloes as $leilao) {
            $leilaoDao->salva($leilao);
        }

        // act - when
//        $leilaoDao->salva($leilaoNaoFinalizado);
//        $leilaoDao->salva($leilaoFinalizado);
        $leiloes = $leilaoDao->recuperarNaoFinalizados();

        // assert - then
        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame("Variante 0Km", $leiloes[0]->recuperarDescricao());
    }

    /**
     * @dataProvider leiloes
     */
    public function testDeveBuscarLeiloesFinalizados(array $leiloes)
    {
        // qrrange - given
        $leilaoDao = new LeilaoDao(self::$pdo);
//        $leilaoNaoFinalizado = new Leilao("Variante 0km");
//        $leilaoFinalizado = new Leilao("Fiat 123");
//        $leilaoFinalizado->finaliza();
        foreach ($leiloes as $leilao) {
            $leilaoDao->salva($leilao);
        }

        // act - when
//        $leilaoDao->salva($leilaoNaoFinalizado);
//        $leilaoDao->salva($leilaoFinalizado);
        $leiloes = $leilaoDao->recuperarFinalizados();

        // assert - then
        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame("Fiat 147 0Km", $leiloes[0]->recuperarDescricao());
    }

    public function testInsercaoEBuscaDevemFuncionar()
    {
        // qrrange - given
        $leilao = new Leilao("Variante 0Km");
        $leilaoDao = new LeilaoDao(self::$pdo);

        // act - when
        $leilaoDao->salva($leilao);
        $leiloes = $leilaoDao->recuperarNaoFinalizados();

        // assert - then
        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame("Variante 0Km", $leiloes[0]->recuperarDescricao());
    }

    protected function tearDown(): void
    {
        self::$pdo->rollBack();
    }

    public function leiloes()
    {
        $naoFinalizado = new Leilao('Variante 0Km');
        $finalizado = new Leilao('Fiat 147 0Km');
        $finalizado->finaliza();
        return [
            [[$naoFinalizado, $finalizado]]     // parametro 1, lembra de
        ];
    }

    public function testAoAtualizarLeilaoStatusDeveSerAlterado()
    {
        $leilao = new Leilao('Brasília Amarela');
        $leilaoDao = new LeilaoDao(self::$pdo);
        $leilao = $leilaoDao->salva($leilao);

        $leiloes = $leilaoDao->recuperarNaoFinalizados();
        self::assertCount(1, $leiloes);
        self::assertSame('Brasília Amarela', $leiloes[0]->recuperarDescricao());
        self::assertFalse($leiloes[0]->estaFinalizado());

        $leilao->finaliza();
        $leilaoDao->atualiza($leilao);

        $leiloes = $leilaoDao->recuperarFinalizados();
        self::assertCount(1, $leiloes);
        self::assertSame('Brasília Amarela', $leiloes[0]->recuperarDescricao());
        self::assertTrue($leiloes[0]->estaFinalizado());
    }

}