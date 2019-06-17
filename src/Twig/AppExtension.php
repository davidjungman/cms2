<?php

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Part of program created by David Jungman
 *
 * @author David Jungman <davidjungman.web@gmail.com>
 */
class AppExtension extends AbstractExtension
{

    public function getFilters()
    {
        return [
          new TwigFilter('cast_to_array', [$this, 'castToArray']),
        ];
    }

}