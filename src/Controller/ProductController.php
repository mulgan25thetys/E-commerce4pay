<?php


namespace App\Controller;

use App\Core\Product\Repository\ProductRepository;
use App\Entity\Product;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class ProductController extends AbstractController
{

    /**
     * @Route("/product", name="product_index")
     */
    public function index(ProductRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $repo->queryAll();
        $page = $request->query->getInt('page', 1);

        $products = $paginator->paginate($query->setMaxResults(9)->getQuery());

        return $this->render('product/index.html.twig', [
            'product' => $products,
            'page' => $page,
            'menu' => 'product',
        ], new Response('', $products->count() > 0 ? 200 : 404));
    }
    /**
     * @Route("/product/{slug<[a-z0-9A-Z\-]+>}-{id<\d+>}", name="product_show")
     */
    public function show(Product $product, string $slug): Response
    {
        if ($product->getSlug() !== $slug) {
            return $this->redirectToRoute('product_show', [
                'id' => $product->getId(),
                'slug' => $product->getSlug(),
            ], 301);
        }

        $response = new Response();
        if (false === $product->isOnline()) {
            $response = $response->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->render('courses/show.html.twig', [
            'product' => $product,
            'menu' => 'courses',
        ], $response);
    }
}