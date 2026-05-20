<?php

namespace App\Controller;

use App\Repository\FazendaRepository;
use App\Repository\GadoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(
        GadoRepository $gadoRepository,
        FazendaRepository $fazendaRepository
    ): Response {

        $gados = $gadoRepository->findBy([
            'abatido' => false
            ]);

        $totalLeite = 0;

        $totalRacao = 0;

        $totalAnimaisJovens = 0;

        $animaisAbate = [];

        foreach ($gados as $gado) {

            $totalLeite += $gado->getLeiteSemana();

            $totalRacao += $gado->getRacaoSemana();

            $idade = $gado
                ->getNascimento()
                ->diff(new \DateTime())
                ->y;

            /*
             * Animal jovem:
             * menos de 2 anos
             */

            if ($idade < 2) {

                $totalAnimaisJovens++;

            }

            /*
             * Animal para abate:
             * mais de 5 anos OU
             * peso acima de 500kg
             */

            if (

                $idade >= 5 ||

                $gado->getPeso() >= 500

            ) {

                $animaisAbate[] = $gado;

            }
        }

        return $this->render(
            'dashboard/index.html.twig',
            [

                'totalLeite' => $totalLeite,

                'totalRacao' => $totalRacao,

                'totalAnimaisJovens' =>
                    $totalAnimaisJovens,

                'totalAbate' =>
                    count($animaisAbate),

                'fazendas' =>
                    $fazendaRepository->findAll(),

                'animaisAbate' =>
                    $animaisAbate

            ]
        );
    }
}