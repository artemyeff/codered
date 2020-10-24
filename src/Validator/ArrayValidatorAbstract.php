<?php
declare(strict_types=1);

namespace App\Validator;

use App\Dto\Api\Error;
use App\Exceptions\Validation\HttpException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Valitron\Validator;

/**
 * Базовый абстрактный класс валидаторов
 *
 * Class ArrayValidatorAbstract
 * @package App\Validator
 */
abstract class ArrayValidatorAbstract
{
    protected Validator $validator;

    /**
     * ArrayValidatorAbstract constructor.
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
        $this->defaultRules();
    }

    /**
     * @param array $data
     * @return void
     * @throws HttpException
     */
    public function validate(array $data): void
    {
        $validator = $this->validator
            ->withData($data)
            ->labels($this->getLabels());

        $validator->rules($this->getRules());

        $validator->validate();

        $errors = null;

        foreach ($validator->errors() as $field => $messages) {
            foreach ($messages as $message) {
                $errors[] = new Error($field, $message);
            }
        }

        if ($errors !== null) {
            throw new HttpException($errors);
        }
    }

    /**
     * @return void
     */
    private function defaultRules(): void
    {
        Validator::addRule('integer', static function ($field, $value, array $params, array $fields): bool {
            $params = $params[0] ?? [];

            if (isset($params['empty']) && $params['empty']) {
                if ($value === null || $value === '') {
                    return true;
                }
            }

            if (!filter_var($value, FILTER_VALIDATE_INT)) {
                return false;
            }

            $stringValue = (string)$value;

            if (($stringValue[0] ?? '') === '-') {
                $stringValue = substr($stringValue, 1);
            }

            return ctype_digit($stringValue) && is_int((int)$stringValue);
        }, "Поле '{field}' должно быть числом");

        Validator::addRule('string', static function ($field, $value, array $params, array $fields): bool {
            return is_string($value);
        }, "Поле '{field}' должно быть строкой");

        Validator::addRule('file', static function ($field, $value, array $params, array $fields): bool {
            if ($value instanceof UploadedFile) {
                if (!empty($params)) {
                    return ($params[0] * 1024 * 1024) > $value->getSize();
                }
                return true;
            }

            return false;
        }, "Поле '{field}' должно быть файлом (Макс. размер %sМБ)");
    }

    /**
     * Метод возвращает читабельные алиасы для ответа
     * Ключ - путь, значение - читабельное название
     *
     * @return array
     */
    protected function getLabels(): array
    {
        return [];
    }

    /**
     * Метод возваращает коллекцию правил
     *
     * @return array
     */
    abstract protected function getRules(): array;
}
