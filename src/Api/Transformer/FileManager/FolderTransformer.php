<?php
declare(strict_types=1);

namespace App\Api\Transformer\FileManager;

use App\Api\Transformer\AbstractTransformer;
use App\Dto\FileManager\FoldersAndFilesDto;
use App\Entity\FileSystem\File;
use App\Entity\FileSystem\Folder;
use App\Entity\News;
use League\Fractal\Resource\{Item, NullResource};

/**
 * Class FolderTransformer
 * @package App\Api\Transformer\FileManager
 */
final class FolderTransformer extends AbstractTransformer
{
    /**
     * @param Folder $folder
     * @return array
     */
    public function transform(Folder $folder): array
    {
        $parent = $folder->getParent();

        if ($parent !== null) {
            $parentData['id'] = $parent->getId();
            $parentData['name'] = $parent->getName();
        }

        return [
            'id' => $folder->getId(),
            'name' => $folder->getName(),
            'parent' => $parentData ?? null
        ];
    }
}
