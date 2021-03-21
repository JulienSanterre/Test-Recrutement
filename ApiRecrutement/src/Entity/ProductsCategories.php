<?php

namespace App\Entity;

use App\Repository\ProductsCategoriesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductsCategoriesRepository::class)
 */
class ProductsCategories
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    // php bin/console doctrine:schema:update --dump-sql
    // php bin/console doctrine:schema:update --force
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    // php bin/console doctrine:schema:update --dump-sql
    // php bin/console doctrine:schema:update --force
    private $categories;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?product
    {
        return $this->product;
    }

    public function setProduct(?product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCategories(): ?category
    {
        return $this->categories;
    }

    public function setCategories(?category $categories): self
    {
        $this->categories = $categories;

        return $this;
    }
}
