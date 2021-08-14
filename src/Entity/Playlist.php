<?php

namespace App\Entity;

use App\Core\Product\Entity\PackagistTrait;
use App\Entity\Content;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Core\Product\Repository\PlaylistRepository")
 */
class Playlist extends Content
{
    use PackagistTrait;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $short = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $youtube_playlist = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Animal", mappedBy="playlist")
     *
     * @var Collection<int, Animal>
     */
    private Collection $animals;
    //TODO dupliqué animals si necessaire pour celui des produits ayant des videos de presentation.

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $links = null;

    public function __construct()
    {
        $this->animals = new ArrayCollection();
        parent::__construct();
    }

    public function getShort(): ?string
    {
        return $this->short;
    }

    public function setShort(?string $short): self
    {
        $this->short = $short;

        return $this;
    }

    public function getDuration(): int
    {
        return array_reduce($this->animals->toArray(), function (int $acc, Animal $item) {
            $acc += $item->getDuration();

            return $acc;
        }, 0);
    }

    public function getYoutubePlaylist(): ?string
    {
        return $this->youtube_playlist;
    }

    public function setYoutubePlaylist(?string $youtube_playlist): self
    {
        $this->youtube_playlist = $youtube_playlist;

        return $this;
    }

    public function getNextAnimalsId(?int $id): ?int
    {
        if (null === $id) {
            return null;
        }
        $ids = array_reduce($this->getRawElements(), fn ($acc, $element) => array_merge($acc, $element['modules']), []);
        $index = array_search($id, $ids);
        if (false === $index) {
            return null;
        }

        return $ids[(int) $index + 1] ?? null;
    }

    /**
     * @return Collection<int, Animal>
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(Animal $animal): self
    {
        if (!$this->animals->contains($animal)) {
            $this->animals[] = $animal;
            $animal->setPlaylist($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): self
    {
        if ($this->animals->contains($animal)) {
            $this->animals->removeElement($animal);
            // set the owning side to null (unless already changed)
            if ($animal->getPlaylist() === $this) {
                $animal->setPlaylist(null);
            }
        }

        return $this;
    }

    public function getAnimalsById(): array
    {
        $animals = $this->getAnimals();
        $animalsById = [];
        foreach ($animals as $animal) {
            $animalsById[$animal->getId()] = $animal;
        }

        return $animalsById;
    }

    public function getLinks(): ?string
    {
        return $this->links;
    }

    public function setLinks(?string $links): self
    {
        $this->links = $links;

        return $this;
    }
}
