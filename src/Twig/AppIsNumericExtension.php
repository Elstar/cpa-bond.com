<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppIsNumericExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('numeric', [$this, 'numeric']),
        ];
    }

    public function numeric($value): bool
    {
        return is_numeric($value);
    }
}
