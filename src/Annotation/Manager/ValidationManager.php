<?php
declare(strict_types=1);

namespace App\Annotation\Manager;

use App\Exceptions\Validation\HttpException;
use App\Factories\Validator\ValidatorFactory;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ValidationManager
 * @package App\Annotation\Manager
 */
final class ValidationManager
{
    private ValidatorFactory $validatorFactory;

    private RequestStack $requestStack;

    /**
     * ValidationManager constructor.
     * @param ValidatorFactory $validatorFactory
     * @param RequestStack $requestStack
     */
    public function __construct(ValidatorFactory $validatorFactory, RequestStack $requestStack)
    {
        $this->validatorFactory = $validatorFactory;
        $this->requestStack = $requestStack;
    }

    /**
     * @param string $validatorClass
     * @throws HttpException
     * @throws JsonException
     */
    public function validate(string $validatorClass): void
    {
        $validator = $this->validatorFactory->create($validatorClass);

        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            return;
        }

        $query = $request->query->all();
        $params = $request->request->all();
        $files = $request->files->all();

        if (empty($request->getContent())) {
            $payload = [];
        } else {
            $payload = json_decode($request->getContent() ?? '', true, 512, JSON_THROW_ON_ERROR) ?? [];
        }

        $data = array_merge_recursive($query, $payload, $params, $files);

        $validator->validate($data);
    }
}
