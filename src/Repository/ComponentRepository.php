<?php

namespace App\Repository;

use App\Entity\Component;
use App\Util\Pagination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Component Repository
 *
 * @method Component|null find($id, $lockMode = null, $lockVersion = null)
 * @method Component|null findOneBy(array $criteria, array $orderBy = null)
 * @method Component[]    findAll()
 * @method Component[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComponentRepository extends ServiceEntityRepository
{
    use Pagination;

    /**
     * ComponentRepository constructor.
     *
     * @param  \Symfony\Bridge\Doctrine\RegistryInterface  $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Component::class);
    }

    /**
     * Returns total number of Components
     *
     * @return int|null
     */
    public function countAll(): ?int
    {
        try
        {
            return $this->createQueryBuilder('c')
                        ->select('count(c.id)')
                        ->getQuery()
                        ->getSingleScalarResult();
        }catch(NonUniqueResultException $e)
        {
            return 0;
        }
    }

    /**
     * Returns total number of enabled Components
     *
     * @return int|null
     */
    public function countEnabled(): ?int
    {
        try
        {
            return $this->createQueryBuilder('c')
                        ->andWhere('c.enabled = true')
                        ->select('count(c.id)')
                        ->getQuery()
                        ->getSingleScalarResult();
        }catch(NonUniqueResultException $e)
        {
            return 0;
        }
    }

    /**
     * Returns Components on current page
     *
     * @param  int  $page
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function findAllByPage(int $page)
    {
        $query = $this->createQueryBuilder('c')
                      ->orderBy('c.enabled', 'DESC')
                      ->getQuery();

        return $this->paginate($query, $page, Component::ITEMS_PER_PAGE);
    }
}
