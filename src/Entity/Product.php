<?php

namespace App\Entity;

use App\Entity\Content;
use App\Entity\Attachment;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Core\Product\Repository\ProductRepository")
 * @Vich\Uploadable()
 */
class Product extends Content
{

    /**
     * @ORM\Column(type="float", options={"default": 0})
     */
    private float $price = 0;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private ?string $typeProduct;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $videoPath = null;

    /**
     * @ORM\Column(type="float", options={"default": 0})
     */
    private ?float $reduction = 0;

    /**
     * @ORM\Column(type="string", length=55, nullable=false)
     */
    private ?string $brand;

    /**
     * @ORM\Column(type="string", length=55, nullable=false)
     */
    private ?string $tag;


    public function getFormatprice(): string
    {
        return number_format($this->price, 0, '', ' ');
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTypeProduct(): ?string
    {
        return $this->typeProduct;
    }

    public function setTypeProduct(?string $typeProduct): self
    {
        $this->typeProduct = $typeProduct;

        return $this;
    }

    public function getVideoPath(): ?string
    {
        return $this->videoPath;
    }

    public function setVideoPath(?string $videoPath): self
    {
        $this->videoPath = $videoPath;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function isScheduled(): bool
    {
        return new \DateTimeImmutable() < $this->getCreatedAt();
    }
}
