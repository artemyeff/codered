<?php
declare(strict_types=1);

namespace App\Context\Product\Transformer;

use App\Api\Transformer\AbstractTransformer;
use App\Context\Category\Transformer\CategoryTransformer;
use App\Context\Product\Entity\Product;
use League\Fractal\Resource\{Item, NullResource};

/**
 * Class NewsTransformer
 * @package App\Api\Transformer
 */
final class ProductTransformer extends AbstractTransformer
{
    protected $availableIncludes = [
        'category',
    ];

    /**
     * @param Product $product
     * @return array
     */
    public function transform(Product $product): array
    {
        return [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'image' => $product->getImage(),
        ];
    }

    /**
     * @param Product $product
     * @return Item|NullResource
     */
    public function includeCategory(Product $product)
    {
        $category = $this->loadInclude($product->getCategory());

        return $this->itemOrNull($category, CategoryTransformer::class);
    }
}
