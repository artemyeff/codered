<?php
declare(strict_types=1);

namespace App\Repository\FileSystem;

use App\Entity\FileSystem\Folder;
use App\Repository\Traits\RemoveTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Folder|null find($id, $lockMode = null, $lockVersion = null)
 * @method Folder|null findOneBy(array $criteria, array $orderBy = null)
 * @method Folder[]    findAll()
 * @method Folder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * Class FolderRepository
 * @package App\Repository\FileSystem
 */
class FolderRepository extends ServiceEntityRepository
{
    use RemoveTrait;

    /**
     * FolderRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Folder::class);
    }

    /**
     * @param int|null $parentId
     * @return Folder[]
     */
    public function findChildren(?int $parentId = null): array
    {
        return $this->findBy(['parent' => $parentId ?: null]);
    }

    /**
     * @param Folder $folder
     * @return Folder
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Folder $folder): Folder
    {
        $manager = $this->getEntityManager();
        $manager->persist($folder);
        $manager->flush();

        return $folder;
    }
}
