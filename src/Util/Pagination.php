<?php

namespace App\Util;


use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Part of program created by David Jungman
 *
 * @author David Jungman <davidjungman.web@gmail.com>
 */
trait Pagination
{
    public function paginate($query, $page = 1, $limit = 10)
    {
        $paginator = new Paginator($query);
        $paginator->getQuery()
                  ->setFirstResult($limit * ($page - 1))
                  ->setMaxResults($limit);
        return $paginator;
    }
}