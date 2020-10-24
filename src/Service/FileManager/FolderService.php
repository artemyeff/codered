<?php
declare(strict_types=1);

namespace App\Service\FileManager;

use App\Dto\Api\Error;
use App\Entity\FileSystem\Folder;
use App\Exceptions\Validation\HttpException;
use App\Repository\FileSystem\FolderRepository;
use Doctrine\ORM\{OptimisticLockException, ORMException};

/**
 * Серивис для паботы с папками
 *
 * Class FolderService
 * @package App\Service\FileManager
 */
class FolderService
{
    private FolderRepository $folderRepository;

    /**
     * FolderService constructor.
     * @param FolderRepository $folderRepository
     */
    public function __construct(FolderRepository $folderRepository)
    {
        $this->folderRepository = $folderRepository;
    }

    /**
     * @param int|null $folderId
     * @return array|array[]
     */
    public function getChildren(?int $folderId = null): array
    {
        return array_map(static function (Folder $folder) {
            $parent = $folder->getParent();

            return [
                'id' => $folder->getId(),
                'name' => $folder->getName(),
                'parent' => [
                    'id' => $parent ? $parent->getId() : null
                ]
            ];
        }, $this->folderRepository->findChildren($folderId) ?? []);
    }

    /**
     * @param array $payload
     * @return Folder
     * @throws HttpException
     */
    public function create(array $payload): Folder
    {
        $currentFolderId = $payload['currentFolder']['id'];

        $parent = $currentFolderId ? $this->folderRepository->find($currentFolderId) : null;

        $folder = $this->folderRepository->findOneBy([
            'name' => $payload['name'],
            'parent' => $parent
        ]);

        if ($folder !== null) {
            throw new HttpException([new Error('Папка', 'Папка с таким именем уже существует')]);
        }

        $folder = new Folder();
        $folder->setName($payload['name']);
        $folder->setParent($parent);

        try {
            $folder = $this->folderRepository->save($folder);
        } catch (OptimisticLockException | ORMException $e) {
            throw new HttpException([new Error('Папка', 'Ошибка сохранения папки')], 422);
        }

        return $folder;
    }

    /**
     * @param string $name
     * @param Folder $folder
     * @return Folder
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function rename(string $name, Folder $folder): Folder
    {
        $folder->setName($name);

        return $this->folderRepository->save($folder);
    }
}
