<?php


namespace App\Core\Product\Utils;


use App\Entity\Playlist;
use App\Normalizer\Breadcrumb\BreadcrumbGeneratorInterface;
use App\Normalizer\Breadcrumb\BreadcrumbItem;

class PlaylistBreadcrumbgenerator implements BreadcrumbGeneratorInterface
{
    /**
     * @param Playlist $playlist
     */
    public function generate($playlist): array
    {
        $items = [];
        $items[] = new BreadcrumbItem('Playlist', ['playlist_index']);
        return $items;
    }

    public function support(object $object): bool
    {
        return $object instanceof Playlist;
    }

}