<?php

declare(strict_types=1);

namespace App\Controller\Client;

use App\Context\Category\Repository\CategoryRepository;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart")
 * Class CartController
 * @package App\Controller\Client
 */
class CartController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(SessionInterface $session, CategoryRepository $repository)
    {
        return $this->render('client/cart.twig', [
            'cart' => $session->get('cart', []),
            'categories' => $repository->findAll(),
        ]);
    }
}
