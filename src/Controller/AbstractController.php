<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractController
 * @package App\Controller
 */
abstract class AbstractController extends BaseAbstractController
{
    /**
     * Метод получения payload запроса
     *
     * @return array|null
     */
    protected function getPayload(): ?array
    {
        /** @var Request $request */
        $request = $this->container->get('request_stack')->getCurrentRequest();
        if ($request === null) {
            return null;
        }

        return json_decode($request->getContent() ?? [], true);
    }
}
