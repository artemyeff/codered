<?php

declare(strict_types=1);

namespace App\Controller\Client;

use App\Context\Category\Entity\Category;
use App\Context\Category\Repository\CategoryRepository;
use App\Context\Product\Entity\Product;
use App\Context\Product\Repository\ProductRepository;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/catalog")
 *
 * Class CatalogController
 * @package App\Controller\Client
 */
class CatalogController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @param Request $request
     * @return Response
     */
    public function main(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        Request $request
    ): Response
    {
        return $this->render('client/catalog.twig', [
            'pagination' => $productRepository->findAllPaginated($request->query->getInt('page', 1)),
            'categories' => $categoryRepository->findAll(),
            'cart' => $request->getSession()->get('cart', []),
        ]);
    }

    /**
     * @Route("/category/{id<\d+>}", methods={"GET"})
     * @param Category $category
     * @param Request $request
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function category(
        Category $category,
        Request $request,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ): Response
    {
        return $this->render('client/category.twig', [
            'pagination' => $productRepository->findAllPaginated(
                $request->query->getInt('page', 1),
                $category->getId(),
            ),
            'categories' => $categoryRepository->findAll(),
            'cart' => $request->getSession()->get('cart', []),
            'category' => $category,
        ]);
    }

    /**
     * @Route("/product/{id<\d+>}", methods={"GET"})
     * @param Product $product
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function product(Product $product, Request $request, CategoryRepository $categoryRepository): Response
    {
        return $this->render('client/product.twig', [
            'product' => $product,
            'cart' => $request->getSession()->get('cart', []),
            'categories' => $categoryRepository->findAll(),
        ]);
    }
}
