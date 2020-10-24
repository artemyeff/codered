<?php
declare(strict_types=1);

namespace App\Context\Category\Transformer;

use App\Api\Transformer\AbstractTransformer;
use App\Context\Category\Entity\Category;
use App\Context\Order\Entity\Order;

/**
 * Class NewsTransformer
 * @package App\Api\Transformer
 */
final class CategoryTransformer extends AbstractTransformer
{
    /**
     * @param Category $category
     * @return array
     */
    public function transform(Category $category): array
    {
        return [
            'id' => $category->getId(),
            'name' => $category->getName(),
        ];
    }
}
