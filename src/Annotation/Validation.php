<?php
declare(strict_types=1);

namespace App\Annotation;

/**
 * @Annotation
 * @Target({"METHOD", "CLASS"})
 *
 * Class Validation
 * @package App\Annotation
 */
class Validation
{
    /**
     * @Required
     * @var string $validationClass
     */
    public $validationClass;

    /**
     * Validation constructor.
     * @param $validationClass
     */
    public function __construct($validationClass)
    {
        $this->validationClass = $validationClass;
    }

    /**
     * @param string $validationClass
     */
    public function setValidationClass(string $validationClass): void
    {
        $this->validationClass = $validationClass;
    }

    /**
     * @return string
     */
    public function getValidationClass(): string
    {
        if (isset($this->validationClass['value'])) {
            return $this->validationClass['value'];
        }

        if (isset($this->validationClass['validationClass'])) {
            return $this->validationClass['validationClass'];
        }

        return $this->validationClass;
    }
}
