<?php
declare(strict_types=1);

namespace App\Exceptions\Validation;

use App\Dto\Api\Error;
use Throwable;

/**
 * Class ValidationException
 * @package App\Exceptions\Validation
 */
class HttpException extends \Exception
{
    public const MESSAGE = 'Http errors';

    private array $errors;

    /**
     * HttpException constructor.
     * @param Error[] $errors
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(array $errors, $code = 400, Throwable $previous = null)
    {
        parent::__construct(self::MESSAGE, $code, $previous);
        $this->errors = $errors;
    }

    /**
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getFormattedErrors(): array
    {
        return ['errors' => array_map(fn(Error $error) => [
            'title' => $error->getTitle(),
            'detail' => $error->getDetail(),
        ], $this->errors)];
    }
}
