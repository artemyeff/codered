<?php
declare(strict_types=1);

namespace App\Dto\FileManager;

use App\Entity\FileSystem\File;
use App\Entity\FileSystem\Folder;

/**
 * Class FoldersAndFilesDto
 * @package App\Dto\FileManager
 */
final class FoldersAndFilesDto
{
    /** @var Folder[] */
    private array $folders;

    /** @var File[] */
    private array $files;

    /**
     * FoldersAndFilesDto constructor.
     * @param Folder[] $folders
     * @param File[] $files
     */
    public function __construct(array $folders, array $files)
    {
        $this->folders = $folders;
        $this->files = $files;
    }

    /**
     * @return Folder[]
     */
    public function getFolders(): array
    {
        return $this->folders;
    }

    /**
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }
}
