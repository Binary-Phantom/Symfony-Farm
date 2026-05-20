<?php

namespace App\Controller;

use App\Entity\Fazenda;
use App\Form\FazendaType;
use App\Repository\FazendaRepository;
use App\Repository\GadoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/fazenda')]
final class FazendaController extends AbstractController
{
    #[Route('/', name: 'app_fazenda_index', methods: ['GET'])]
    public function index(
        FazendaRepository $fazendaRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $query = $fazendaRepository
            ->createQueryBuilder('f')
            ->orderBy('f.id', 'DESC');

        $pagination = $paginator->paginate(

            $query,

            $request->query->getInt('page', 1),

            10

        );

        return $this->render('fazenda/index.html.twig', [

            'pagination' => $pagination,

        ]);
    }

    #[Route('/resumo', name: 'app_fazenda_resumo', methods: ['GET'])]
    public function resumo(
        FazendaRepository $fazendaRepository,
        GadoRepository $gadoRepository
    ): Response {

        $fazendas = $fazendaRepository->findAll();

        $nomes = [];

        $quantidadeAnimais = [];

        $producaoLeite = [];

        $consumoRacao = [];

        foreach ($fazendas as $fazenda) {

            $gados = $gadoRepository->findBy([
                'fazenda' => $fazenda,
                'abatido' => false
            ]);

            $nomes[] = $fazenda->getNome();

            $quantidadeAnimais[] = count($gados);

            $totalLeite = 0;

            $totalRacao = 0;

            foreach ($gados as $gado) {

                $totalLeite += $gado->getLeiteSemana();

                $totalRacao += $gado->getRacaoSemana();
            }

            $producaoLeite[] = $totalLeite;

            $consumoRacao[] = $totalRacao;
        }

        return $this->render(
            'fazenda/resumo.html.twig',
            [

                'nomes' => json_encode($nomes),

                'quantidadeAnimais' =>
                    json_encode($quantidadeAnimais),

                'producaoLeite' =>
                    json_encode($producaoLeite),

                'consumoRacao' =>
                    json_encode($consumoRacao)

            ]
        );
    }

    #[Route('/new', name: 'app_fazenda_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {

        $fazenda = new Fazenda();

        $form = $this->createForm(
            FazendaType::class,
            $fazenda
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($fazenda);

            $entityManager->flush();

            $this->addFlash(
                'success',
                'Fazenda cadastrada com sucesso.'
            );

            return $this->redirectToRoute(
                'app_fazenda_index'
            );
        }

        return $this->render('fazenda/new.html.twig', [

            'fazenda' => $fazenda,

            'form' => $form,

        ]);
    }

    #[Route('/{id}', name: 'app_fazenda_show', methods: ['GET'])]
    public function show(Fazenda $fazenda): Response
    {
        return $this->render('fazenda/show.html.twig', [

            'fazenda' => $fazenda,

        ]);
    }

    #[Route('/{id}/edit', name: 'app_fazenda_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Fazenda $fazenda,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(
            FazendaType::class,
            $fazenda
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            $this->addFlash(
                'success',
                'Fazenda atualizada com sucesso.'
            );

            return $this->redirectToRoute(
                'app_fazenda_index'
            );
        }

        return $this->render('fazenda/edit.html.twig', [

            'fazenda' => $fazenda,

            'form' => $form,

        ]);
    }

    #[Route('/{id}', name: 'app_fazenda_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Fazenda $fazenda,
        EntityManagerInterface $entityManager
    ): Response {

        if (

            $this->isCsrfTokenValid(
                'delete'.$fazenda->getId(),
                $request->request->get('_token')
            )

        ) {

            $entityManager->remove($fazenda);

            $entityManager->flush();

            $this->addFlash(
                'danger',
                'Fazenda removida com sucesso.'
            );
        }

        return $this->redirectToRoute(
            'app_fazenda_index'
        );
    }
}