<?php

namespace App\Entity;

use App\Entity\Content;
use App\Entity\Attachment;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Core\Product\Repository\AnimalRepository")
 * @Vich\Uploadable()
 */
class Animal extends Content
{
    /**
     * @ORM\Column(type="float", options={"default": 0})
     */
    private  float $price = 0;


    /**
     * @ORM\Column(type="smallint", options={"default": 0})
     */
    private int $duration = 0;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private ?string $youtubeId = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Attachment", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private ?Attachment $youtubeThumbnail = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $videoPath = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $source = null;

    /**
     * @Vich\UploadableField(mapping="sources", fileNameProperty="source")
     */
    private ?File $sourceFile = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $demo = null;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private bool $premium = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $Birthday = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Playlist", inversedBy="animals")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private ?Playlist $playlist = null;

    /**
     * @return float|int
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float|int $price
     */
    public function setPrice($price): self
    {
        $this->price = $price;
        return $this;
    }
    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function isVideoPremium(): bool
    {
        return $this->getPremium() || $this->isScheduled();
    }

    public function getYoutubeId(): ?string
    {
        return $this->youtubeId;
    }

    public function setYoutubeId(?string $youtubeId): self
    {
        $this->youtubeId = $youtubeId;

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

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getDemo(): ?string
    {
        return $this->demo;
    }

    public function setDemo(?string $demo): self
    {
        $this->demo = $demo;

        return $this;
    }

    public function getPremium(): bool
    {
        return $this->premium;
    }

    public function setPremium(bool $premium): self
    {
        $this->premium = $premium;

        return $this;
    }


    public function getSourceFile(): ?File
    {
        return $this->sourceFile;
    }

    public function setSourceFile(?File $sourceFile): Animal
    {
        $this->sourceFile = $sourceFile;

        return $this;
    }

    public function getYoutubeThumbnail(): ?Attachment
    {
        return $this->youtubeThumbnail;
    }

    public function setYoutubeThumbnail(?Attachment $youtubeThumbnail): self
    {
        $this->youtubeThumbnail = $youtubeThumbnail;

        return $this;
    }

    public function isScheduled(): bool
    {
        return new \DateTimeImmutable() < $this->getCreatedAt();
    }


    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->Birthday;
    }

    /**
     * @param \DateTimeInterface|null $Birthday
     */
    public function setBirthday(?\DateTimeInterface $Birthday): self
    {
        $this->Birthday = $Birthday;
        return $this;
    }
    public function getPlaylist(): ?Playlist
    {
        return $this->playlist;
    }

    public function setPlaylist(?Playlist $playlist): self
    {
        $this->playlist = $playlist;

        return $this;
    }
}
