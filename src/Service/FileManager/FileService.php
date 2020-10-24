<?php
declare(strict_types=1);

namespace App\Service\FileManager;

use App\Dto\FileManager\FoldersAndFilesDto;
use App\Exceptions\Validation\HttpException;
use App\Repository\FileSystem\FileRepository;
use App\Repository\FileSystem\FolderRepository;
use App\Service\FileSystem\FileService as BaseFileService;
use App\Validator\FileManager\File\UploadRequestValidator;
use App\Entity\FileSystem\File;
use Doctrine\ORM\{
    OptimisticLockException, ORMException
};
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Серивис для паботы с файлами
 *
 * Class FolderService
 * @package App\Service\FileManager
 */
class FileService
{
    private FileRepository $fileRepository;

    private FolderRepository $folderRepository;

    private ParameterBagInterface $parameterBag;

    private UploadRequestValidator $uploadRequestValidator;

    private BaseFileService $service;

    /**
     * FileService constructor.
     * @param FileRepository $fileRepository
     * @param FolderRepository $folderRepository
     * @param ParameterBagInterface $parameterBag
     * @param UploadRequestValidator $uploadRequestValidator
     * @param BaseFileService $service
     */
    public function __construct(
        FileRepository $fileRepository,
        FolderRepository $folderRepository,
        ParameterBagInterface $parameterBag,
        UploadRequestValidator $uploadRequestValidator,
        BaseFileService $service
    ) {
        $this->fileRepository = $fileRepository;
        $this->folderRepository = $folderRepository;
        $this->parameterBag = $parameterBag;
        $this->uploadRequestValidator = $uploadRequestValidator;
        $this->service = $service;
    }

    /**
     * Метод удаления файлов
     *
     * @param array $files
     * @return void
     * @throws HttpException
     */
    public function remove(array $files): void
    {
        foreach ($files as $file) {
            $fullPath = $this->parameterBag->get('publicPath') . $file['path'];

            if (file_exists($fullPath)) {
                $result = unlink($fullPath);
            }

            if (!($result ?? true)) {
                $errors['errors'][] = [
                    'title' => 'Файл',
                    'detail' => 'Ошибка удаления файла "' . $file['path'] . '"',
                ];
            } else {
                $this->fileRepository->removeById($file['id']);
            }
        }

        if (!empty($errors)) {
            throw new HttpException($errors);
        }
    }

    /**
     * @param string $name
     * @param File $file
     * @return File
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function rename(string $name, File $file): File
    {
        $file->setName($name);

        return $this->fileRepository->save($file);
    }

    /**
     * @param Request $request
     * @return FoldersAndFilesDto
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws HttpException
     */
    public function upload(Request $request): FoldersAndFilesDto
    {
        $files = $request->files->all();

        $currentFolderId = $request->request->get('currentFolderId');

        $data = [
            'file' => array_shift($files),
            'currentFolder' => [
                'id' => (int)$currentFolderId ?: null
            ]
        ];

        $this->uploadRequestValidator->validate($data);
        $folder = $currentFolderId ? $this->folderRepository->find($currentFolderId) : null;

        foreach ($request->files as $file) {
            $this->service->saveUploadedFile($file, $folder);
        }

        return new FoldersAndFilesDto(
            $this->folderRepository->findBy(['parent' => $data['currentFolder']['id'] ?? null]),
            $this->fileRepository->findByFolder($data['currentFolder']['id'] ?? null)
        );
    }
}
