<?php

namespace Alura\Leilao\Tests\Dao\Integration\Dao;

use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Infra\ConnectionCreator;
use PHPUnit\Framework\TestCase;

class LeilaoDaoTest extends TestCase
{

    
    public function testInsercaoEBuscaDevemFuncionar()
    {
        $leilao = new Leilao("Variante 0km");
        $leilaoDao = new LeilaoDao(ConnectionCreator::getConnection());

        $leilaoDao->salva($leilao);

        $leiloes = $leilaoDao->recuperarNaoFinalizados();

        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame("Variante 0km", $leiloes[0]->recuperarDescricao());
    }
}