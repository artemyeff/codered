<?php

declare(strict_types=1);

namespace App\Controller\Api\Client\V1;

use App\Context\Product\Repository\ProductRepository;
use App\Controller\Api\AbstractApiController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use JsonException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Rest\Route("/cart")
 *
 * Class CartController
 * @package App\Controller\Api\Client\V1
 */
class CartController extends AbstractApiController
{
    /**
     * @Rest\Post("/add")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @param ProductRepository $repository
     * @return View
     * @throws JsonException
     */
    public function add(SessionInterface $session, ProductRepository $repository)
    {
        $payload = $this->getPayload();

        $productId = $payload['product']['id'];
        $product = $repository->find($productId);

        $cart = $session->get('cart', []);

        $count = isset($cart['items'][$productId]['count']) ? $cart['items'][$productId]['count'] + 1 : 1;

        $cart['items'][$productId] = [
            'count' => $count,
            'price' => $product->getPrice(),
            'sum' => $count * $product->getPrice(),
            'image' => $product->getImage(),
            'name' => $product->getName(),
        ];

        $sum = array_reduce($cart['items'], function ($carry, $item) {
            return $carry + $item['sum'];
        });
        $countTotal = array_reduce($cart['items'], function ($carry, $item) {
            return $carry + $item['count'];
        });

        $cart['sum'] = $sum;
        $cart['count'] = $countTotal;

        $session->set('cart', $cart);

        return $this->view([
            'count' => $countTotal
        ]);
    }

    /**
     * @Rest\Post("/minus")
     *
     * @param SessionInterface $session
     */
    public function minus(SessionInterface $session)
    {
        $payload = $this->getPayload();

        $productId = $payload['product']['id'];

        $cart = $session->get('cart', []);

        $product = &$cart['items'][$productId];

        $product['count'] -= 1;

        if ($product['count'] <= 0) {
            unset($cart['items'][$productId]);
            if (empty($cart['items'])) {
                $session->remove('cart');
                return $this->view([
                    'html' => ''
                ]);
            } else {
                $session->set('cart', $cart);
            }
        }

        $product['sum'] = $product['count'] * $product['price'];

        $sum = array_reduce($cart['items'], function ($carry, $item) {
            return $carry + $item['sum'];
        });

        $countTotal = array_reduce($cart['items'], function ($carry, $item) {
            return $carry + $item['count'];
        });

        $cart['sum'] = $sum;
        $cart['count'] = $countTotal;

        $session->set('cart', $cart);

        return $this->view([
            'html' => $this->renderView('partials/cart-content.twig', ['cart' => $cart])
        ]);
    }


    /**
     * @Rest\Post("/remove")
     *
     * @param SessionInterface $session
     */
    public function remove(SessionInterface $session)
    {
        $payload = $this->getPayload();

        $productId = $payload['product']['id'];

        $cart = $session->get('cart', []);

        $product = &$cart['items'][$productId];

        unset($cart['items'][$productId]);
        if (empty($cart['items'])) {
            $session->remove('cart');
            return $this->view([
                'html' => ''
            ]);
        } else {
            $session->set('cart', $cart);
        }

        $product['sum'] = $product['count'] * $product['price'];

        $sum = array_reduce($cart['items'], function ($carry, $item) {
            return $carry + $item['sum'];
        });

        $countTotal = array_reduce($cart['items'], function ($carry, $item) {
            return $carry + $item['count'];
        });

        $cart['sum'] = $sum;
        $cart['count'] = $countTotal;

        $session->set('cart', $cart);

        return $this->view([
            'html' => $this->renderView('partials/cart-content.twig', ['cart' => $cart])
        ]);
    }


    /**
     * @Rest\Post("/plus")
     *
     * @param SessionInterface $session
     * @return View
     * @throws JsonException
     */
    public function plus(SessionInterface $session)
    {
        $payload = $this->getPayload();

        $productId = $payload['product']['id'];

        $cart = $session->get('cart', []);

        $product = &$cart['items'][$productId];

        $product['count'] += 1;

        $product['sum'] = $product['count'] * $product['price'];

        $sum = array_reduce($cart['items'], function ($carry, $item) {
            return $carry + $item['sum'];
        });

        $countTotal = array_reduce($cart['items'], function ($carry, $item) {
            return $carry + $item['count'];
        });

        $cart['sum'] = $sum;
        $cart['count'] = $countTotal;

        $session->set('cart', $cart);

        return $this->view([
            'html' => $this->renderView('partials/cart-content.twig', ['cart' => $cart])
        ]);
    }
}
