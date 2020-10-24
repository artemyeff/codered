<?php
declare(strict_types=1);

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class JWTCreatedListener
 * @package App\EventListener
 */
class JWTListener
{
    private RequestStack $requestStack;

    /**
     * JWTCreatedListener constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $expiration = new \DateTime();
        $expiration->modify('+5 hour');

        $payload = $event->getData();
        $payload['avatar'] = 'avatar';
        $payload['exp'] = $expiration->getTimestamp();

        $event->setData($payload);
    }

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        if (!in_array('ROLE_ADMIN', $event->getUser()->getRoles())) {
            $event->setData([
                'errors' => [
                    [
                        'source' => 'Авторизация',
                        'title' => 'Пользователь не являтся админом'
                    ]
                ]
            ]);
            $event->getResponse()->setStatusCode(403);
        } else {
            $event->setData([
                'data' => $event->getData()
            ]);
        }
    }

    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $event->setResponse(new JsonResponse([
            'errors' => [
                [
                    'title' => 'Авторизация',
                    'source' => 'Неверные логин или пароль'
                ]
            ]
        ], 401));
    }

    /**
     * @param JWTNotFoundEvent $event
     */
    public function onJwtNotFound(JWTNotFoundEvent $event): void
    {
        $event->setResponse(new JsonResponse([
            'errors' => [
                [
                    'title' => 'Авторизация',
                    'source' => 'Токен не найден'
                ]
            ]
        ], 401));
    }

    /**
     * @param JWTInvalidEvent $event
     */
    public function onJwtInvalid(JWTInvalidEvent $event): void
    {
        $event->setResponse(new JsonResponse([
            'errors' => [
                [
                    'title' => 'Авторизация',
                    'source' => 'Не валидный токен'
                ]
            ]
        ], 401));
    }
}
