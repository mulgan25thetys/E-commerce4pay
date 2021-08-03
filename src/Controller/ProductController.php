<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route ("/product/lists", name="products_lists")
     */
    public function liste_des_produits():Response{
        return $this->render('produit/liste.html.twig',[
            'datapro' => 'From product controller'
        ]);
    }

}