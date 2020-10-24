<?php
declare(strict_types=1);

namespace App\Api\Transformer\FileManager;

use App\Api\Transformer\AbstractTransformer;
use App\Dto\FileManager\FoldersAndFilesDto;
use League\Fractal\Resource\Collection;

/**
 * Class FoldersAndFilesTransformer
 * @package App\Api\Transformer\FileManager
 */
final class FoldersAndFilesTransformer extends AbstractTransformer
{
    protected $defaultIncludes = [
        'folders',
        'files',
    ];

    /**
     * @return array
     */
    public function transform(): array
    {
        return [];
    }

    /**
     * @param FoldersAndFilesDto $dto
     * @return Collection
     */
    public function includeFolders(FoldersAndFilesDto $dto): Collection
    {
        return $this->collection($dto->getFolders(), new FolderTransformer($this->manager), '');
    }

    /**
     * @param FoldersAndFilesDto $dto
     * @return Collection
     */
    public function includeFiles(FoldersAndFilesDto $dto): Collection
    {
        return $this->collection($dto->getFiles(), new FileTransformer(), '');
    }
}
