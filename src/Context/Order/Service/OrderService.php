<?php

declare(strict_types=1);

namespace App\Context\Order\Service;

use App\Context\Order\Hydrator\OrderHydrator;
use App\Context\Order\Hydrator\OrderProductHydrator;
use App\Context\Order\Repository\OrderProductRepository;
use App\Context\Order\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class OrderService
 * @package App\Context\Order\Service
 */
final class OrderService
{
    private SessionInterface $session;

    private OrderRepository $orderRepository;

    private OrderProductRepository $orderProductRepository;

    private EntityManagerInterface $entityManager;

    private OrderHydrator $orderHydrator;

    private OrderProductHydrator $orderProductHydrator;

    /**
     * OrderService constructor.
     * @param SessionInterface $session
     * @param OrderRepository $orderRepository
     * @param OrderProductRepository $orderProductRepository
     * @param EntityManagerInterface $entityManager
     * @param OrderHydrator $orderHydrator
     * @param OrderProductHydrator $orderProductHydrator
     */
    public function __construct(SessionInterface $session, OrderRepository $orderRepository, OrderProductRepository $orderProductRepository, EntityManagerInterface $entityManager, OrderHydrator $orderHydrator, OrderProductHydrator $orderProductHydrator)
    {
        $this->session = $session;
        $this->orderRepository = $orderRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->entityManager = $entityManager;
        $this->orderHydrator = $orderHydrator;
        $this->orderProductHydrator = $orderProductHydrator;
    }

    public function create(string $phone)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $cart = $this->session->get('cart');
            $order = $this->orderHydrator->hydrate(
                array_merge(
                    [
                        'phone' => $phone
                    ],
                    $cart
                )
            );
            $this->entityManager->persist($order);

            foreach ($cart['items'] as $id => $product) {
                $orderProduct = $this->orderProductHydrator->hydrate(array_merge([
                    'product' => [
                        'id' => $id
                    ],
                ], $product), $order);
                $this->entityManager->persist($orderProduct);
            }

            $this->entityManager->flush();

            $this->session->remove('cart');

            $this->entityManager->getConnection()->commit();
        } catch (\Throwable $exception) {
            $this->entityManager->getConnection()->rollBack();
        }
    }
}
