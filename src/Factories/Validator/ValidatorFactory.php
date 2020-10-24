<?php
declare(strict_types=1);

namespace App\Factories\Validator;

use App\Validator\ArrayValidatorAbstract;
use Valitron\Validator;

/**
 * Class ValidatorFactory
 * @package App\Factories\Validator
 */
final class ValidatorFactory
{
    private Validator $validator;

    /**
     * ValidatorFactory constructor.
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param string $validatorClass
     * @return ArrayValidatorAbstract
     */
    public function create(string $validatorClass): ArrayValidatorAbstract
    {
        if (!in_array(ArrayValidatorAbstract::class, class_parents($validatorClass), true)) {
            throw new \LogicException('Validator must implement ArrayValidatorAbstract');
        }

        return new $validatorClass($this->validator);
    }
}
