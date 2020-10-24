<?php
declare(strict_types=1);

namespace App\Repository\FileSystem;

use App\Entity\FileSystem\File;
use App\Repository\Traits\RemoveTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method File|null find($id, $lockMode = null, $lockVersion = null)
 * @method File|null findOneBy(array $criteria, array $orderBy = null)
 * @method File[]    findAll()
 * @method File[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * Class FileRepository
 * @package App\Repository\FileSystem
 */
class FileRepository extends ServiceEntityRepository
{
    use RemoveTrait;

    /**
     * FileRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }

    /**
     * @param int|null $folderId
     * @return File[]
     */
    public function findByFolder(?int $folderId = null): array
    {
        $folderId = $folderId ?: null;
        $builder = $this->createQueryBuilder('f');
        $builder->andWhere('f.is_single = false');

        if ($folderId === null) {
            $builder->andWhere($builder->expr()->isNull('f.folder'));
        } else {
            $builder->andWhere('f.folder = :folder');
            $builder->setParameter('folder', $folderId ?: null);
        }

        return $builder->orderBy('f.updated_at', 'desc')
            ->getQuery()
            ->execute();
    }

    /**
     * @param File $file
     * @return File
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(File $file): File
    {
        $manager = $this->getEntityManager();
        $manager->persist($file);
        $manager->flush();

        return $file;
    }
}
