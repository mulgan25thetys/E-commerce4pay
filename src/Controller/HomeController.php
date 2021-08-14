<?php


namespace App\Controller;


use App\Entity\Animal;
use App\Entity\Content;
use App\Entity\Playlist;
use App\Entity\Post;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        $user = $this->getUser();
        if ($user) {
            return $this->homeLogged($user);
        }
        $productRepository = $this->em->getRepository(Product::class);
        $animalRepository = $this->em->getRepository(Animal::class);
        return $this->render('pages/home.html.twig', [
            'menu' => 'home',
            'products' => $productRepository->findRecent(3),
            'animals' => $animalRepository->findRecent(5),
            'hours' => round($animalRepository->findTotalDuration() / 3600),
            'playlist' => $this->em->getRepository(Playlist::class)->findRecent(5),
            'posts' => $this->em->getRepository(Post::class)->findRecent(5),
        ]);
    }
    public function homeLogged(User $user): Response
    {
        #$watchlist = $this->historyService->getLastWatchedContent($user);
        #$excluded = array_map(fn (Progress $progress) => $progress->getContent()->getId(), $watchlist);
        //Todo personnalisé la page d'acceuil d'un utilisateur abonné
        $content = $this->em->getRepository(Content::class)
            ->findLatest(14)
            ->andWhere('c INSTANCE OF '.Product::class.' OR c INSTANCE OF '.Animal::class.' OR c INSTANCE OF '.Playlist::class);

        return $this->render('pages/home-logged.html.twig', [
            'menu' => 'home',
            'latest_content' => $content,

        ]);
    }
}