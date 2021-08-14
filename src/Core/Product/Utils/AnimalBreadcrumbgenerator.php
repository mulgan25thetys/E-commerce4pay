<?php


namespace App\Core\Product\Utils;


use App\Entity\Animal;
use App\Normalizer\Breadcrumb\BreadcrumbGeneratorInterface;
use App\Normalizer\Breadcrumb\BreadcrumbItem;

class AnimalBreadcrumbgenerator implements BreadcrumbGeneratorInterface
{
    /**
     * @param Animal $animal
     */
    public function generate($animal): array
    {
        $items = [];
        $items[] = new BreadcrumbItem('VideosAnimal', ['animal_index']);
        if ($playlist= $animal->getPlaylist()) {
            $items[] = new BreadcrumbItem(
                (string) $playlist->getTitle(),
                ['playlist_show', ['slug' => $playlist->getSlug()]]
            );
        }
        $items[] = new BreadcrumbItem((string) $animal->getTitle(), ['animal_show', [
            'id' => $animal->getId(),
            'slug' => $animal->getSlug(),
        ]]);

        return $items;
    }

    public function support(object $object): bool
    {
        return $object instanceof Animal;
    }

}