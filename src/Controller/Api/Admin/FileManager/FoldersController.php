<?php
declare(strict_types=1);

namespace App\Controller\Api\Admin\FileManager;

use App\Annotation\Validation;
use App\Api\Transformer\FileManager\FoldersAndFilesTransformer;
use App\Controller\Api\AbstractApiController;
use App\Dto\FileManager\FoldersAndFilesDto;
use App\Entity\FileSystem\Folder;
use App\Exceptions\Validation\HttpException;
use App\Repository\FileSystem\{FileRepository, FolderRepository};
use App\Service\FileManager\FolderService;
use App\Validator\FileManager\{Folder\MakeRequestValidator,
    Folder\RemoveRequestValidator,
    Folder\RenameRequestValidator};
use Doctrine\ORM\{OptimisticLockException, ORMException};
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use JsonException;
use League\Fractal\Manager;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/folders")
 *
 * Class FoldersController
 * @package App\Controller\Api\Admin\FileManager
 */
class FoldersController extends AbstractApiController
{
    use ResponseTrait;

    private FileRepository $fileRepository;

    private FolderRepository $folderRepository;

    /**
     * FoldersController constructor.
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
     * @Rest\Get("")
     *
     * @param Request $request
     * @return View
     */
    public function all(Request $request): View
    {
        $id = $request->query->get('id');
        return $this->makeItemView(
            new FoldersAndFilesDto(
                $this->folderRepository->findBy(['parent' => (int)$id ?: null]),
                $this->fileRepository->findByFolder((int)$id ?: null)
            ),
            new FoldersAndFilesTransformer($this->manager)
        );
        return $this->makeResponse(
            $this->folderRepository->findBy(['parent' => (int)$id ?: null]),
            $this->fileRepository->findByFolder((int)$id ?: null)
        );
    }

    /**
     * @Validation(MakeRequestValidator::class)
     * @Rest\Post("")
     *
     * @param FolderService $service
     * @return View
     * @throws JsonException
     * @throws HttpException
     */
    public function create(FolderService $service): View
    {
        $payload = $this->getPayload();

        $service->create($payload);

        return $this->makeResponse(
            $this->folderRepository->findBy(['parent' => $payload['currentFolder']['id']]),
            $this->fileRepository->findByFolder($payload['currentFolder']['id'])
        );
    }

    /**
     * @Validation(RenameRequestValidator::class)
     * @Rest\Patch("/{id}")
     *
     * @param Folder $folder
     * @param FolderService $service
     * @return View
     * @throws ORMException | OptimisticLockException | JsonException
     */
    public function rename(Folder $folder, FolderService $service): View
    {
        $payload = $this->getPayload();

        $service->rename($payload['name'], $folder);

        return $this->makeResponse(
            $this->folderRepository->findBy(['parent' => $payload['currentFolder']['id']]),
            $this->fileRepository->findByFolder($payload['currentFolder']['id'])
        );
    }

    /**
     * @Validation(RemoveRequestValidator::class)
     * @Rest\Delete("/{id<\d+>}")
     *
     * @param int $id
     * @return View
     * @throws JsonException
     */
    public function delete(int $id): View
    {
        $payload = $this->getPayload();

        $this->folderRepository->removeById($id);

        return $this->makeResponse(
            $this->folderRepository->findBy(['parent' => $payload['currentFolder']['id']]),
            $this->fileRepository->findByFolder($payload['currentFolder']['id'])
        );
    }
}
