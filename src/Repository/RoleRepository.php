<?php

namespace App\Repository;

use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Role Repository
 *
 * @method Role|null find($id, $lockMode = null, $lockVersion = null)
 * @method Role|null findOneBy(array $criteria, array $orderBy = null)
 * @method Role[]    findAll()
 * @method Role[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleRepository extends ServiceEntityRepository
{

    /**
     * RoleRepository constructor.
     *
     * @param  \Symfony\Bridge\Doctrine\RegistryInterface  $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Role::class);
    }
}
