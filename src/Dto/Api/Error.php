<?php
declare(strict_types=1);

namespace App\Dto\Api;

/**
 * Class Error
 * @package App\Dto\Api
 */
final class Error
{
    private string $title;

    private string $detail;

    /**
     * Error constructor.
     * @param string $title
     * @param string $detail
     */
    public function __construct(string $title, string $detail)
    {
        $this->title = $title;
        $this->detail = $detail;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDetail(): string
    {
        return $this->detail;
    }
}
