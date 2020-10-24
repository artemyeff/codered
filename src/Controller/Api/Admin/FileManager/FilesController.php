<?php
declare(strict_types=1);

namespace App\Controller\Api\Admin\FileManager;

use App\Annotation\Validation;
use App\Api\Transformer\FileManager\FoldersAndFilesTransformer;
use App\Controller\Api\AbstractApiController;
use App\Entity\FileSystem\File;
use App\Exceptions\Validation\HttpException;
use App\Repository\FileSystem\{FileRepository, FolderRepository};
use App\Service\FileManager\FileService;
use App\Validator\FileManager\File\{RemoveRequestValidator, RenameRequestValidator, UploadRequestValidator};
use Doctrine\ORM\{OptimisticLockException, ORMException};
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use JsonException;
use League\Fractal\Manager;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/files")
 *
 * Class FilesController
 * @package App\Controller\Api\Admin\FileManager
 */
class FilesController extends AbstractApiController
{
    use ResponseTrait;

    private FileRepository $fileRepository;

    private FolderRepository $folderRepository;

    /**
     * FilesController constructor.
     * @param Manager $manager
     * @param FileRepository $fileRepository
     * @param FolderRepository $folderRepository
     */
    public function __construct(Manager $manager, FileRepository $fileRepository, FolderRepository $folderRepository)
    {
        parent::__construct($manager);
        $this->fileRepository = $fileRepository;
        $this->folderRepository = $folderRepository;
    }

    /**
     * @Rest\Post("/upload")
     *
     * @param Request $request
     * @param FileService $fileService
     * @return View
     * @throws ORMException | OptimisticLockException | HttpException
     */
    public function upload(Request $request, FileService $fileService): View
    {
        $dto = $fileService->upload($request);

        return $this->makeItemView($dto, new FoldersAndFilesTransformer());
    }

    /**
     * @Validation(RenameRequestValidator::class)
     * @Rest\Patch("/{id}")
     *
     * @param File $file
     * @param FileService $fileService
     * @return View
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws JsonException
     */
    public function rename(File $file, FileService $fileService): View
    {
        $payload = $this->getPayload();

        $fileService->rename($payload['name'], $file);

        return $this->makeResponse(
            $this->folderRepository->findBy(['parent' => $payload['currentFolder']['id']]),
            $this->fileRepository->findByFolder($payload['currentFolder']['id'])
        );
    }

    /**
     * @Validation(RemoveRequestValidator::class)
     * @Rest\Delete("")
     *
     * @param FileService $fileService
     * @return View
     * @throws HttpException
     * @throws JsonException
     */
    public function remove(FileService $fileService): View
    {
        $fileService->remove($this->getPayload()['files']);

        $payload = $this->getPayload();

        return $this->makeResponse(
            $this->folderRepository->findBy(['parent' => $payload['currentFolder']['id']]),
            $this->fileRepository->findByFolder($payload['currentFolder']['id']),
        );
    }
}
