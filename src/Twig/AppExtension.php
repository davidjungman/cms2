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

    public function getfilters()
    {
        return [
          new TwigFilter('cast_to_array', [$this, 'castToArray']),
        ];
    }

    public function castToArray($object)
    {
        $response = [];
        foreach($object as $key => $value)
        {
            $response[] = array($key,$value);
        }
        return $response;
    }
}