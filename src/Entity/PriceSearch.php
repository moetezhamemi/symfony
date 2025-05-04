<?php

namespace App\Entity;

use App\Repository\PriceSearchRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PriceSearchRepository::class)]
class PriceSearch
{


    #[ORM\Column(nullable: true)]
    private ?float $minPrice = null;

    #[ORM\Column(nullable: true)]
    private ?float $maxPrice = null;



    public function getMinPrice(): ?float
    {
        return $this->minPrice;
    }

    public function setMinPrice(?float $minPrice): static
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    public function getMaxPrice(): ?float
    {
        return $this->maxPrice;
    }

    public function setMaxPrice(?float $maxPrice): static
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }
}
