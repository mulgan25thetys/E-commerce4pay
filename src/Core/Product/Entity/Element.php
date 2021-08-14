<?php

namespace App\Core\Product\Entity;

use App\Entity\Content;
use App\Entity\Playlist;

class Element
{
    private string $title;

    /**
     * @var array<Content>
     */
    private array $modules = [];

    /**
     * Génère des packages à partir du JSON renvoyée par la base.
     *
     * @return Element[]
     */
    public static function makeFromContent(Content $target): array
    {
        if ($target instanceof Playlist) {
            $modulesById = $target->getAnimalsById();
        } else {
            throw new \RuntimeException('Type innattendu');
        }
        $elements = [];
        foreach ($target->getRawChapters() as $c) {
            $element = new self();
            $element->title = $c['title'];
            $element->modules = array_map(fn (int $id) => $modulesById[$id], $c['modules']);
            $elements[] = $element;
        }

        return $elements;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Content[]
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * @param Content[] $content
     */
    public function setModules(array $content): self
    {
        $this->modules = $content;

        return $this;
    }
}
