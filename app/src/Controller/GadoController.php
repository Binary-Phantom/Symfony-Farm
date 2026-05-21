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
     * Lista animais vivos
     */
    #[Route('/', name: 'app_gado_index', methods: ['GET'])]
    public function index(
        GadoRepository $gadoRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $query = $gadoRepository
            ->createQueryBuilder('g')
            ->where('g.abatido = :abatido')
            ->setParameter('abatido', false)
            ->orderBy('g.id', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(

            $query,

            $request->query->getInt('page', 1),

            5,

            [
                'distinct' => true
            ]

        );

        return $this->render('gado/index.html.twig', [

            'pagination' => $pagination,

        ]);
    }

    /*
     * Lista animais abatidos
     */
    #[Route('/abatidos', name: 'app_gado_abatidos', methods: ['GET'])]
    public function abatidos(
        GadoRepository $gadoRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $query = $gadoRepository
            ->createQueryBuilder('g')
            ->where('g.abatido = :abatido')
            ->setParameter('abatido', true)
            ->orderBy('g.id', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(

            $query,

            $request->query->getInt('page', 1),

            5,

            [
                'distinct' => true
            ]

        );

        return $this->render(
            'gado/abatidos.html.twig',
            [

                'pagination' => $pagination

            ]
        );
    }

    /*
     * Cadastro de animal
     */
    #[Route('/new', name: 'app_gado_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        GadoRepository $gadoRepository
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

        /*
         * Validação de data futura
         */
        if (

            $form->isSubmitted()
            &&
            $gado->getNascimento()
            &&
            $gado->getNascimento() > new \DateTime()

        ) {

            $this->addFlash(
                'danger',
                'Não é possível cadastrar um animal com data futura.'
            );

            return $this->redirectToRoute(
                'app_gado_new'
            );
        }

        if ($form->isSubmitted() && $form->isValid()) {

            /*
             * Impede código duplicado entre animais vivos
             */
            $gadoExistente = $gadoRepository->findOneBy([
                'codigo' => $gado->getCodigo(),
                'abatido' => false
            ]);

            if ($gadoExistente) {

                $this->addFlash(
                    'danger',
                    'Já existe um animal vivo com este código.'
                );

                return $this->redirectToRoute(
                    'app_gado_new'
                );
            }

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
     * Detalhes do animal
     */
    #[Route('/{id}', name: 'app_gado_show', methods: ['GET'])]
    public function show(Gado $gado): Response
    {
        return $this->render('gado/show.html.twig', [

            'gado' => $gado,

        ]);
    }

    /*
     * Editar animal
     */
    #[Route('/{id}/edit', name: 'app_gado_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Gado $gado,
        EntityManagerInterface $entityManager,
        GadoRepository $gadoRepository
    ): Response {

        $form = $this->createForm(
            GadoType::class,
            $gado
        );

        $form->handleRequest($request);

        /*
         * Validação de data futura
         */
        if (

            $form->isSubmitted()
            &&
            $gado->getNascimento()
            &&
            $gado->getNascimento() > new \DateTime()

        ) {

            $this->addFlash(
                'danger',
                'Não é possível cadastrar um animal com data futura.'
            );

            return $this->redirectToRoute(
                'app_gado_edit',
                [
                    'id' => $gado->getId()
                ]
            );
        }

        if ($form->isSubmitted() && $form->isValid()) {

            /*
             * Impede código duplicado entre animais vivos
             */
            $gadoExistente = $gadoRepository
                ->createQueryBuilder('g')
                ->where('g.codigo = :codigo')
                ->andWhere('g.abatido = false')
                ->andWhere('g.id != :id')
                ->setParameter('codigo', $gado->getCodigo())
                ->setParameter('id', $gado->getId())
                ->getQuery()
                ->getOneOrNullResult();

            if ($gadoExistente) {

                $this->addFlash(
                    'danger',
                    'Já existe um animal vivo com este código.'
                );

                return $this->redirectToRoute(
                    'app_gado_edit',
                    [
                        'id' => $gado->getId()
                    ]
                );
            }

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
     * Abater animal
     */
    #[Route('/{id}/abater', name: 'app_gado_abater', methods: ['POST'])]
    public function abater(
        Request $request,
        Gado $gado,
        EntityManagerInterface $entityManager
    ): Response {

        if (

            !$this->isCsrfTokenValid(
                'abater'.$gado->getId(),
                $request->request->get('_token')
            )

        ) {

            $this->addFlash(
                'danger',
                'Token inválido.'
            );

            return $this->redirectToRoute(
                'app_gado_index'
            );
        }

        /*
         * Calcula idade
         */
        $idade = $gado
            ->getNascimento()
            ->diff(new \DateTime());

        $anos = $idade->y;

        /*
         * Litros semana
         */
        $leiteSemana = $gado->getLeiteSemana();

        /*
         * Ração por dia
         */
        $racaoDia = $gado->getRacaoSemana() / 7;

        /*
         * Arrobas
         */
        $arrobas = $gado->getPeso() / 15;

        /*
         * Verifica regras
         */
        $podeAbater = false;

        if ($anos > 5) {
            $podeAbater = true;
        }

        if ($leiteSemana < 40) {
            $podeAbater = true;
        }

        if (
            $leiteSemana < 70
            &&
            $racaoDia > 50
        ) {
            $podeAbater = true;
        }

        if ($arrobas > 18) {
            $podeAbater = true;
        }

        /*
         * Impede abate inválido
         */
        if (!$podeAbater) {

            $this->addFlash(
                'danger',
                'Este animal não atende aos critérios para abate.'
            );

            return $this->redirectToRoute(
                'app_gado_index'
            );
        }

        /*
         * Realiza abate
         */
        $gado->setAbatido(true);

        $entityManager->flush();

        $this->addFlash(
            'success',
            'Animal abatido com sucesso.'
        );

        return $this->redirectToRoute(
            'app_gado_index'
        );
    }

    /*
     * Remove animal
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