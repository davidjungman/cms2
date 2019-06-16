<?php

namespace App\Service;


use DateTimeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Part of program created by David Jungman
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class DateService
{

    /**
     * Returns negative integer when second parameter is greater than first
     *
     * @param  \DateTime  $date1
     * @param  \DateTime  $date2
     *
     * @return int
     */
    public function compareDateTimes(DateTimeInterface $date1, DateTimeInterface $date2)
    {
        return ( $date1->getTimestamp() - $date2->getTimestamp() <= 0 );
    }
}