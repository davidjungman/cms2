<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Events Repository
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{

    /**
     * EventsRepository constructor.
     *
     * @param  \Symfony\Bridge\Doctrine\RegistryInterface  $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }
}
