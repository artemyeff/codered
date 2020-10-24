<?php
declare(strict_types=1);

namespace App\Context\Product\Hydrator;

use App\Context\Category\Repository\CategoryRepository;
use App\Context\Product\Entity\Product;

/**
 * Class ProductHydrator
 * @package App\Hydrator
 */
final class ProductHydrator
{
    private CategoryRepository $categoryRepository;

    /**
     * ProductHydrator constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param array $data
     * @param Product|null $product
     * @return Product
     */
    public function hydrate(array $data, ?Product $product = null): Product
    {
        $product ??= new Product();

        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setImage($data['image'] ?? null);
        $product->setPrice((float)$data['price']);
        $product->setCategory($this->categoryRepository->find($data['category']['id']));

        return $product;
    }
}
