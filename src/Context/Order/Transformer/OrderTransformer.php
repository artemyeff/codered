<?php
declare(strict_types=1);

namespace App\Context\Order\Transformer;

use App\Api\Transformer\AbstractTransformer;
use App\Context\Category\Entity\Category;
use App\Context\Order\Entity\Order;

/**
 * Class NewsTransformer
 * @package App\Api\Transformer
 */
final class OrderTransformer extends AbstractTransformer
{
    /**
     * @param Order $order
     * @return array
     */
    public function transform(Order $order): array
    {
        return [
            'id' => $order->getId(),
            'sum' => $order->getSum(),
            'phone' => $order->getPhone(),
            'count' => $order->getCount(),
            'createdAt' => $this->formatDate($order->getCreatedAt()),
            'updatedAt' => $this->formatDate($order->getCreatedAt()),
        ];
    }
}
