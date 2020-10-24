<?php
declare(strict_types=1);

namespace App\Service\FileSystem;

use App\Entity\FileSystem\{File, Folder};
use App\Repository\FileSystem\FileRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileService
 * @package App\Service\FileSystem
 */
final class FileService
{
    private FileRepository $repository;

    private ParameterBagInterface $parameterBag;

    /**
     * FileService constructor.
     * @param FileRepository $repository
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(FileRepository $repository, ParameterBagInterface $parameterBag)
    {
        $this->repository = $repository;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param Folder|null $folder
     * @param bool $isSingle
     * @return File
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveUploadedFile(UploadedFile $uploadedFile, ?Folder $folder = null, bool $isSingle = false): File
    {
        $md5Name = md5($uploadedFile->getClientOriginalName());
        $folderPath = $this->getMd5DirectoryPrefix($md5Name);
        $destination = $this->parameterBag->get('filesPath') . $folderPath;
        $newName = $md5Name . '.' . $uploadedFile->getClientOriginalExtension();
        $relativePath = '/' . $this->parameterBag->get('filesFolder') . $folderPath . '/' . $newName;

        $uploadedFile->move($destination, $newName);

        $newFile = $this->repository->findOneBy(['path' => $relativePath]) ?? new File();
        $newFile->setName($uploadedFile->getClientOriginalName());
        $newFile->setExtension($uploadedFile->getClientOriginalExtension());
        $newFile->setFolder($folder);
        $newFile->setPath($relativePath);
        $newFile->setSingle($isSingle);

        $this->repository->save($newFile);

        return $newFile;
    }

    /**
     * @param string $md5
     * @return string
     */
    private function getMd5DirectoryPrefix(string $md5): string
    {
        return '/' . substr($md5, 0, 2) .
            '/' . substr($md5, 2, 2) .
            '/' . substr($md5, 4, 2);
    }

}
