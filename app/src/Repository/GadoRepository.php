<?php

namespace App\Repository;

use App\Entity\Gado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gado>
 */
class GadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gado::class);
    }

    /*
    //     * @return Gado[] Returns an array of Gado objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }
    //       teste aaaaaaaaaa
    //    public function findOneBySomeField($value): ?Gado
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }*/
    public function existeCodigoVivo(int $codigo): bool
    {
        return (bool) $this->createQueryBuilder('g')
            ->select('COUNT(g.id)')
            ->where('g.codigo = :codigo')
            ->andWhere('g.abatido = false')
            ->setParameter('codigo', $codigo)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function contarVivosPorFazenda(int $fazendaId): int
{
    return (int) $this->createQueryBuilder('g')

        ->select('COUNT(g.id)')

        ->where('g.fazenda = :fazenda')

        ->andWhere('g.abatido = false')

        ->setParameter('fazenda', $fazendaId)

        ->getQuery()

        ->getSingleScalarResult();
    }
}
