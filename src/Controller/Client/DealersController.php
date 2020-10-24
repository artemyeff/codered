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
 * @Route("/dealers", methods={"GET"}, name="dealers")
 *
 * Class MainController
 * @package App\Controller\Client
 */
class DealersController extends AbstractController
{
    /**
     * @param CategoryRepository $categoryRepository
     * @param SessionInterface $session
     * @return Response
     */
    public function __invoke(
        CategoryRepository $categoryRepository,
        SessionInterface $session
    ): Response
    {
        return $this->render('client/dealers.twig', [
            'categories' => $categoryRepository->findAll(),
            'cart' => $session->get('cart', []),
        ]);
    }
}
