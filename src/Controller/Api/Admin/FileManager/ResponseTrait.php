<?php
declare(strict_types=1);

namespace App\Controller\Api\Admin\FileManager;

use App\Api\Transformer\FileManager\FoldersAndFilesTransformer;
use App\Controller\Api\AbstractApiController;
use App\Dto\FileManager\FoldersAndFilesDto;
use App\Entity\FileSystem\File;
use App\Entity\FileSystem\Folder;
use FOS\RestBundle\View\View;

/**
 * Trait ResponseTrait
 * @package App\Controller\Api\Admin\FileManager
 *
 * @mixin AbstractApiController
 */
trait ResponseTrait
{
    /**
     * @param Folder[] $folders
     * @param File[] $files
     * @return View
     */
    protected function makeResponse(array $folders, array $files): View
    {
        return $this->makeItemView(
            new FoldersAndFilesDto(
                $folders,
                $files
            ),
            new FoldersAndFilesTransformer()
        );
    }
}
