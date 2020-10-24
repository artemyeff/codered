<?php
declare(strict_types=1);

namespace App\Repository\Traits;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * Trait RemoveTrait
 * @package App\Repository\Traits
 *
 * @mixin ServiceEntityRepository
 */
trait RemoveTrait
{
    /**
     * @param int $id
     */
    public function removeById(int $id): void
    {
        $this->createQueryBuilder('a')
            ->andWhere('a.id = :id')
            ->setParameter('id', $id)
            ->delete($this->getClassName(), 'a')
            ->getQuery()
            ->execute();
    }
}
