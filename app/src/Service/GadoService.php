<?php

namespace App\Service;

use App\Entity\Fazenda;
use App\Entity\Gado;
use App\Repository\GadoRepository;

class GadoService
{
    public function __construct(
        private GadoRepository $gadoRepository
    ) {
    }

    public function validarLimiteAnimais(Fazenda $fazenda): void
    {
        $total = $fazenda->getGados()->count();

        $maximo = $fazenda->getTamanho() * 18;

        if ($total >= $maximo) {

            throw new \DomainException(
                'Limite de animais da fazenda atingido.'
            );
        }
    }

    public function validarCodigoUnico(Gado $gado): void
    {
        if (
            $this->gadoRepository
                ->existeCodigoVivo($gado->getCodigo())
        ) {

            throw new \DomainException(
                'Já existe um animal vivo com este código.'
            );
        }
    }
}