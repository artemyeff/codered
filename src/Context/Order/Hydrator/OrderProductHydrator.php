<?php
declare(strict_types=1);

namespace App\Context\Order\Hydrator;

use App\Context\Category\Entity\Category;
use App\Context\Order\Entity\Order;
use App\Context\Order\Entity\OrderProduct;
use App\Context\Product\Repository\ProductRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class ProductHydrator
 * @package App\Hydrator
 */
final class OrderProductHydrator
{
    private ProductRepository $productRepository;

    /**
     * OrderOrderHydrator constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param array $data
     * @param Order $order
     * @param OrderProduct|null $orderProduct
     * @return OrderProduct
     * @throws NonUniqueResultException
     */
    public function hydrate(array $data, Order $order, ?OrderProduct $orderProduct = null): OrderProduct
    {
        $orderProduct ??= new OrderProduct();

        $orderProduct->setOrderCreated($order);
        $orderProduct->setProduct($this->productRepository->findOne($data['product']['id']));
        $orderProduct->setSum($data['sum']);
        $orderProduct->setCount($data['count']);
        return $orderProduct;
    }
}
