<?php

namespace App\Controller;

use App\Entity\Gado;
use App\Form\GadoType;
use App\Repository\GadoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gado')]
final class GadoController extends AbstractController
{
    /*
     * LISTAGEM DE GADOS VIVOS
     */
    #[Route('/', name: 'app_gado_index', methods: ['GET'])]
    public function index(
        GadoRepository $gadoRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $query = $gadoRepository
            ->createQueryBuilder('g')
            ->where('g.abatido = false')
            ->orderBy('g.id', 'DESC');

        $pagination = $paginator->paginate(

            $query,

            $request->query->getInt('page', 1),

            10

        );

        return $this->render('gado/index.html.twig', [

            'pagination' => $pagination,

        ]);
    }

    /*
     * LISTAGEM DE GADOS ABATIDOS
     */
    #[Route(
        '/abatidos',
        name: 'app_gado_abatidos',
        methods: ['GET']
    )]
    public function abatidos(
        GadoRepository $gadoRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $query = $gadoRepository
            ->createQueryBuilder('g')
            ->where('g.abatido = true')
            ->orderBy('g.id', 'DESC');

        $pagination = $paginator->paginate(

            $query,

            $request->query->getInt('page', 1),

            10

        );

        return $this->render(
            'gado/abatidos.html.twig',
            [

                'pagination' => $pagination

            ]
        );
    }

    /*
     * NOVO GADO
     */
    #[Route('/new', name: 'app_gado_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {

        $gado = new Gado();

        /*
         * Animal nasce vivo
         */
        $gado->setAbatido(false);

        $form = $this->createForm(
            GadoType::class,
            $gado
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($gado);

            $entityManager->flush();

            $this->addFlash(
                'success',
                'Gado cadastrado com sucesso.'
            );

            return $this->redirectToRoute(
                'app_gado_index'
            );
        }

        return $this->render('gado/new.html.twig', [

            'gado' => $gado,

            'form' => $form,

        ]);
    }

    /*
     * DETALHES
     */
    #[Route('/{id}', name: 'app_gado_show', methods: ['GET'])]
    public function show(Gado $gado): Response
    {
        return $this->render('gado/show.html.twig', [

            'gado' => $gado,

        ]);
    }

    /*
     * EDITAR
     */
    #[Route('/{id}/edit', name: 'app_gado_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Gado $gado,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(
            GadoType::class,
            $gado
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            $this->addFlash(
                'success',
                'Gado atualizado com sucesso.'
            );

            return $this->redirectToRoute(
                'app_gado_index'
            );
        }

        return $this->render('gado/edit.html.twig', [

            'gado' => $gado,

            'form' => $form,

        ]);
    }

    /*
     * ABATER ANIMAL
     */
    #[Route(
        '/{id}/abater',
        name: 'app_gado_abater',
        methods: ['POST']
    )]
    public function abater(
        Request $request,
        Gado $gado,
        EntityManagerInterface $entityManager
    ): Response {

        if (

            $this->isCsrfTokenValid(
                'abater'.$gado->getId(),
                $request->request->get('_token')
            )

        ) {

            $gado->setAbatido(true);

            $entityManager->flush();

            $this->addFlash(
                'warning',
                'Animal abatido com sucesso.'
            );
        }

        return $this->redirectToRoute(
            'app_gado_index'
        );
    }

    /*
     * EXCLUIR
     */
    #[Route('/{id}', name: 'app_gado_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Gado $gado,
        EntityManagerInterface $entityManager
    ): Response {

        if (

            $this->isCsrfTokenValid(
                'delete'.$gado->getId(),
                $request->request->get('_token')
            )

        ) {

            $entityManager->remove($gado);

            $entityManager->flush();

            $this->addFlash(
                'danger',
                'Gado removido com sucesso.'
            );
        }

        return $this->redirectToRoute(
            'app_gado_index'
        );
    }
}