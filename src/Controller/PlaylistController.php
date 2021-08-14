<?php


namespace App\Controller;

use App\Core\Product\Repository\PlaylistRepository;
use App\Entity\Animal;
use App\Entity\Playlist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class PlaylistController extends AbstractController
{
    /**
     * @Route("/playlist", name="playlist_index")
     */
    public function index(PlaylistRepository $playlistRepository): Response
    {
        $playlists = $playlistRepository->findAll();

        return $this->render('playlists/index.html.twig', [
            'playlists' => $playlists,
            'menu' => 'formations',
        ]);
    }

    /**
     * @Route("/playlists/{slug}", name="playlist_show")
     */
    public function show(
        Playlist $playlist
    ): Response {

        return $this->render('playlists/show.html.twig', [
            'formation' => $playlist,
            'menu' => 'formations',
        ]);
    }
    /**
     * Redirige vers la prochaine playlist à regarder.
     *
     * @Route("/playlist/{slug}/continue", name="playlist_resume")
     */
    public function resume(
        Playlist $playlist,
        EntityManagerInterface $em,
        NormalizerInterface $normalizer
    ): RedirectResponse {
        $user = $this->getUser();
        $ids = $playlist->getModulesIds();
        $nextContentId = $ids[0];
        //Todo gerer le cas d'un abonnee qui regade generalement le contenu du site
        $content = $em->find(Animal::class, $nextContentId);
        /** @var array $path */
        $path = $normalizer->normalize($content, 'path');

        return $this->redirectToRoute($path['path'], $path['params']);
    }

}