<?php

namespace Alura\Leilao\Service;

use Alura\Leilao\Dao\Leilao as LeilaoDao;

class Encerrador
{
    /** @var LeilaoDao */
    private LeilaoDao $dao;

    public function __construct(LeilaoDao $leilaoDao)
    {
        $this->dao = $leilaoDao;
    }

    public function encerra()
    {
        $leiloes = $this->dao->recuperarNaoFinalizados();

        foreach ($leiloes as $leilao) {
            if ($leilao->temMaisDeUmaSemana()) {
                $leilao->finaliza();
                $this->dao->atualiza($leilao);
            }
        }
    }
}
