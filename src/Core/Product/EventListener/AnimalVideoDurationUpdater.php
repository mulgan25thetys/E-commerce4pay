<?php


namespace App\Core\Product\EventListener;


use App\Entity\Animal;
use App\Helper\PathHelper;
use App\Infrastructure\Storage\VideoMetaReader;

class AnimalVideoDurationUpdater
{
    private string $videosPath;
    private VideoMetaReader $metaReader;

    public function __construct(string $videosPath, VideoMetaReader $metaReader)
    {
        $this->videosPath = $videosPath;
        $this->metaReader = $metaReader;
    }
    public function updateDuration(Animal $animal): void
    {
        if (!empty($animal->getVideoPath())) {
            $video = PathHelper::join($this->videosPath, $animal->getVideoPath());
            if ($duration = $this->metaReader->getDuration($video)) {
                $animal->setDuration($duration);
            }
        }
    }
}