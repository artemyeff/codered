<?php
declare(strict_types=1);

namespace App\Controller\Client;

use App\Context\Category\Repository\CategoryRepository;
use App\Context\Product\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", methods={"GET"}, name="main_page")
 *
 * Class MainController
 * @package App\Controller\Client
 */
class MainController extends AbstractController
{
    /**
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @param SessionInterface $session
     * @return Response
     */
    public function __invoke(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        SessionInterface $session
    ): Response
    {
        return $this->render('client/index.twig', [
            'products' => $productRepository->findNewest(),
            'categories' => $categoryRepository->findAll(),
            'cart' => $session->get('cart', []),
        ]);
    }
}
