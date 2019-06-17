<?php

namespace App\Repository;

use App\Entity\User;
use App\Util\Pagination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * User Repository
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    use Pagination;

    /**
     * UserRepository constructor.
     *
     * @param  \Symfony\Bridge\Doctrine\RegistryInterface  $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Returns total number of Users
     *
     * @return int|null
     */
    public function countAll(): ?int
    {
        try
        {
            return $this->createQueryBuilder('u')
                        ->select('count(u.id)')
                        ->getQuery()
                        ->getSingleScalarResult();
        }catch(NonUniqueResultException $e)
        {
            return 0;
        }
    }

    /**
     * @param  int  $page
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function findAllOrderByActive($page = 1)
    {
        $query = $this->createQueryBuilder('u')
                      ->orderBy('u.active', 'DESC')
                      ->getQuery();

        return $this->paginate($query, $page, User::ITEMS_PER_PAGE);
    }
}
