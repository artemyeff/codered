<?php
declare(strict_types=1);

namespace App\Context\Order\Hydrator;

use App\Context\Category\Entity\Category;
use App\Context\Order\Entity\Order;

/**
 * Class ProductHydrator
 * @package App\Hydrator
 */
final class OrderHydrator
{
    /**
     * @param array $data
     * @param Order|null $order
     * @return Order
     */
    public function hydrate(array $data, ?Order $order = null): Order
    {
        $order ??= new Order();

        $order->setCreatedAt(new \DateTime());
        $order->setUpdatedAt(new \DateTime());
        $order->setPhone($data['phone']);
        $order->setCount($data['count']);
        $order->setSum($data['sum']);
        return $order;
    }
}
