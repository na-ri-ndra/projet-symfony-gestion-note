<?php

namespace App\Repository;

use App\Entity\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Note>
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

//    /**
//     * @return Note[] Returns an array of Note objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Note
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findByNoteRange(float $min, float $max): array
{
    return $this->createQueryBuilder('n')
        ->where('n.note BETWEEN :min AND :max')
        ->setParameter('min', $min)
        ->setParameter('max', $max)
        ->getQuery()
        ->getResult();
}
public function findByFilters($min, $max, $nom)
{
    $qb = $this->createQueryBuilder('n')
        ->join('n.etudiant', 'e')
        ->join('n.matiere', 'm');

    if ($min !== null) {
        $qb->andWhere('n.note >= :min')
           ->setParameter('min', (float) $min);
    }

    if ($max !== null) {
        $qb->andWhere('n.note <= :max')
           ->setParameter('max', (float) $max);
    }

    if ($nom) {
        $qb->andWhere('LOWER(e.nom) LIKE :nom')
           ->setParameter('nom', '%' . strtolower($nom) . '%');
    }

    return $qb->getQuery()->getResult();
}
}
