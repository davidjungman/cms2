<?php

namespace App\Repository;

use App\Entity\Email;
use App\Util\Pagination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Email Repository
 *
 * @method Email|null find($id, $lockMode = null, $lockVersion = null)
 * @method Email|null findOneBy(array $criteria, array $orderBy = null)
 * @method Email[]    findAll()
 * @method Email[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailRepository extends ServiceEntityRepository
{
    use Pagination;

    /**
     * EmailRepository constructor.
     *
     * @param  \Symfony\Bridge\Doctrine\RegistryInterface  $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Email::class);
    }

    /**
     * Returns total of not-read emails
     *
     * @return int|null
     */
    public function countActiveEmails(): ?int
    {
        try
        {
            return $this->createQueryBuilder('e')
              ->andWhere('e.active = true')
              ->select('count(e.id)')
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
        $query = $this->createQueryBuilder('e')
          ->orderBy('e.active', 'DESC')
          ->getQuery();

        return $this->paginate($query, $page, Email::ITEMS_PER_PAGE);
    }
}
