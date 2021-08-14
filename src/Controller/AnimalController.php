<?php


namespace App\Controller;


use App\Core\Product\Repository\AnimalRepository;
use App\Entity\Animal;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{

    /**
     * @Route("/animal", name="animal_index")
     */
    public function index(AnimalRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $repo->queryAll();
        $page = $request->query->getInt('page', 1);

        $animals = $paginator->paginate($query->setMaxResults(26)->getQuery());

        return $this->render('animals/index.html.twig', [
            'animals' => $animals,
            'page' => $page,
            'menu' => 'animals',
        ], new Response('', $animals->count() > 0 ? 200 : 404));
    }

    /**
     * @Route("/tutoriels/{slug<[a-z0-9A-Z\-]+>}-{id<\d+>}", name="animal_show")
     */
    public function show(Animal $animal, string $slug): Response
    {
        if ($animal->getSlug() !== $slug) {
            return $this->redirectToRoute('animal_show', [
                'id' => $animal->getId(),
                'slug' => $animal->getSlug(),
            ], 301);
        }

        $response = new Response();
        if (false === $animal->isOnline()) {
            $response = $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->render('courses/show.html.twig', [
            'animal' => $animal,
            'menu' => 'courses',
        ], $response);
    }
}