<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class RouteNameToArrayExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('route_name_to_array', [$this, 'converter']),
        ];
    }

    /**
     * Explode route name to array Example app_category_create return array ['app', 'category', 'create']
     * @param string $value
     * @return array
     */
    public function converter(string $value): array
    {
        return explode('_', $value);
    }
}
