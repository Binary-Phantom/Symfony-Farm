<?php

namespace App\Controller;

use App\Repository\FazendaRepository;
use App\Repository\GadoRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(
        Request $request,
        GadoRepository $gadoRepository,
        FazendaRepository $fazendaRepository,
        PaginatorInterface $paginator
    ): Response {

        /*
         * Apenas animais vivos
         */
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
             * Animal jovem
             */
            if (
                $idade <= 1
                &&
                $gado->getRacaoSemana() > 500
            ) {

                $totalAnimaisJovens++;
            }

            /*
             * Animal pra abate
             */
            $racaoDia = $gado->getRacaoSemana() / 7;

            $arrobas = $gado->getPeso() / 15;

            if (

                $idade > 5
                ||
                $gado->getLeiteSemana() < 40
                ||
                (
                    $gado->getLeiteSemana() < 70
                    &&
                    $racaoDia > 50
                )
                ||
                $arrobas > 18

            ) {

                $animaisAbate[] = $gado;
            }
        }

        /*
         * Resumo fazendas
         */
        $resumoFazendas = [];

        foreach ($fazendaRepository->findAll() as $fazenda) {

            $resumoFazendas[] = [

                'nome' => $fazenda->getNome(),

                'responsavel' =>
                    $fazenda->getResponsavel(),

                'tamanho' =>
                    $fazenda->getTamanho(),

                'gados' =>
                    $gadoRepository->contarVivosPorFazenda(
                        $fazenda->getId()
                    )

            ];
        }

        /*
         * Paginação Fazendas
         */
        $fazendasPagination = $paginator->paginate(

            $resumoFazendas,

            $request->query->getInt('page_fazendas', 1),

            5,

            [
                'pageParameterName' => 'page_fazendas'
            ]

        );

        /*
         * Paginação Abate
         */
        $abatePagination = $paginator->paginate(

            $animaisAbate,

            $request->query->getInt('page_abate', 1),

            5,

            [
                'pageParameterName' => 'page_abate'
            ]

        );

        return $this->render(
            'dashboard/index.html.twig',
            [

                'totalLeite' => $totalLeite,

                'totalRacao' => $totalRacao,

                'totalAnimaisJovens' =>
                    $totalAnimaisJovens,

                'totalAbate' =>
                    count($animaisAbate),

                'fazendasPagination' =>
                    $fazendasPagination,

                'abatePagination' =>
                    $abatePagination

            ]
        );
    }
}