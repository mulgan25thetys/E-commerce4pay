<?php

namespace App\Core\Product\Entity;

use App\Entity\Content;
use Doctrine\ORM\Mapping as ORM;

trait PackagistTrait
{
    /**
     * @ORM\Column(type="json")
     *
     * @return array{title: string, content: int[]}[]
     */
    protected array $elements = [];

    /**
     * @param list<array{title: string, modules: int[]}> $elements
     */
    public function setRawelements(array $elements): self
    {
        $this->elements = $elements;

        return $this;
    }

    /**
     * Initialise les elements depuis le JSON.
     *
     * @return Element[]
     */
    public function getElements(): array
    {
        return Element::makeFromContent($this);
    }

    public function getAnimalsCount(): int
    {
        $elements = $this->getElements();

        return array_reduce($elements, fn (int $acc, Element $element) => $acc + count($element->getModules()), 0);
    }

    /**
     * Renvoie les données brut (JSON).
     */
    public function getRawElements(): array
    {
        return $this->elements;
    }

    /**
     * Rempli le champs JSON à partir d'un tableau d'objet element.
     *
     * @var Element[]
     */
    public function setElements(array $elements): self
    {
        $this->elements = array_map(function (Element $element) {
            return [
                'title' => $element->getTitle(),
                'modules' => array_map(fn (Content $course) => $course->getId(), $element->getModules()),
            ];
        }, $elements);

        return $this;
    }

    /**
     * Extrait le premier contenu du premier element.
     */
    public function getFirstContent(): ?Content
    {
        $firstElement = $this->getElements()[0] ?? null;
        if (null === $firstElement) {
            return null;
        }

        return $firstElement->getModules()[0] ?? null;
    }

    /**
     * Renvoie la liste des ids des contenu organisés.
     *
     * @return int[]
     */
    public function getModulesIds(): array
    {
        return array_reduce($this->getRawElements(), fn (array $acc, array $element) => array_merge($acc, $element['modules']), []);
    }
}
