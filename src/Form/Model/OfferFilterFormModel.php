<?php

namespace App\Form\Model;

use App\Entity\Category;
use App\Entity\Geo;

class OfferFilterFormModel
{
    public string $name;

    public Geo $geo;

    public Category $category;
}