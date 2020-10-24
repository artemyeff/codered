<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Exceptions\Validation\HttpException;
use Doctrine\ORM\Query\QueryException;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\{MethodNotAllowedHttpException, NotFoundHttpException};
use Twig\Environment;
use Twig\Error\{LoaderError, RuntimeError, SyntaxError};

/**
 * Class ExceptionListener
 * @package App\EventListener
 */
class ExceptionListener
{
    private Environment $twig;

    /**
     * ExceptionListener constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param ExceptionEvent $event
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        if ($event->getThrowable() instanceof MethodNotAllowedHttpException) {
            if ($this->isApiRequest($event->getRequest())) {
                $event->setResponse(new JsonResponse([
                    'errors' => [
                        [
                            'title' => 'Method not allowed',
                            'detail' => 'Method not allowed',
                        ]
                    ]
                ], Response::HTTP_METHOD_NOT_ALLOWED));
            } else {
                $event->setResponse(new Response(
                        $this->twig->render('errors/error.html.twig', ['message' => 'Method not allowed']),
                        Response::HTTP_METHOD_NOT_ALLOWED)
                );
            }
            return;
        }

        if ($event->getThrowable() instanceof NotFoundHttpException) {
            if ($this->isApiRequest($event->getRequest())) {
                $event->setResponse(new JsonResponse([
                    'errors' => [
                        [
                            'title' => 'Not found',
                            'detail' => 'Resource not found',
                        ]
                    ]
                ], Response::HTTP_NOT_FOUND));
            } else {
                $event->setResponse(new Response(
                        $this->twig->render('errors/error.html.twig', ['message' => 'Not found']),
                        Response::HTTP_NOT_FOUND)
                );
            }
            return;
        }

        if ($event->getThrowable() instanceof QueryException) {
            if ($this->isApiRequest($event->getRequest())) {
                $event->setResponse(new JsonResponse([
                    'errors' => [
                        [
                            'title' => 'Unprocessable entity',
                            'detail' => 'Unprocessable entity',
                        ]
                    ]
                ], Response::HTTP_UNPROCESSABLE_ENTITY));
            } else {
                $event->setResponse(new Response(
                        $this->twig->render('errors/error.html.twig', ['message' => 'Unprocessable entity']),
                        Response::HTTP_UNPROCESSABLE_ENTITY)
                );
            }
            return;
        }

        if ($event->getThrowable() instanceof HttpException && $this->isApiRequest($event->getRequest())) {
            $event->setResponse(new JsonResponse(
                $event->getThrowable()->getFormattedErrors(),
                Response::HTTP_BAD_REQUEST)
            );

            return;
        }
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function isApiRequest(Request $request): bool
    {
        $header = $request->headers->get('X-Requested-With');
        return $header !== null && $header === 'XMLHttpRequest';
    }
}
