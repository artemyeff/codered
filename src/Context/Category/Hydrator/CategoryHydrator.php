<?php
declare(strict_types=1);

namespace App\Context\Category\Hydrator;

use App\Context\Category\Entity\Category;

/**
 * Class ProductHydrator
 * @package App\Hydrator
 */
final class CategoryHydrator
{
    /**
     * @param array $data
     * @param Category|null $category
     * @return Category
     */
    public function hydrate(array $data, ?Category $category = null): Category
    {
        $category ??= new Category();

        $category->setName($data['name']);

        return $category;
    }
}
