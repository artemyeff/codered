<?php

namespace App\EventListener;

use App\Annotation\Manager\ValidationManager;
use App\Annotation\Validation;
use App\Exceptions\Validation\HttpException;
use Doctrine\Common\Annotations\Reader;
use JsonException;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\{Event\ControllerEvent, KernelEvents};

class ValidationAnnotationListener implements EventSubscriberInterface
{
    private Reader $reader;

    private ValidationManager $validationManager;

    /**
     * ValidationAnnotationListener constructor.
     * @param Reader $reader
     * @param ValidationManager $validationManager
     */
    public function __construct(Reader $reader, ValidationManager $validationManager)
    {
        $this->reader = $reader;
        $this->validationManager = $validationManager;
    }

    /**
     * @param ControllerEvent $controllerEvent
     * @throws HttpException
     * @throws JsonException
     * @throws ReflectionException
     */
    public function onKernelController(ControllerEvent $controllerEvent): void
    {
        $controllerAndAction = $this->getControllerAndAction($controllerEvent->getRequest());

        $controllerReflectionClass = $this->getControllerReflectionClass($controllerAndAction['controller']);

        if ($controllerReflectionClass === null) {
            return;
        }

        if ($controllerAndAction['action'] !== null) {
            /** @var Validation $validationAnnotation */
            $validationAnnotation = $this->reader->getMethodAnnotation(
                $controllerReflectionClass->getMethod($controllerAndAction['action']),
                Validation::class
            );
        } else {
            /** @var Validation $validationAnnotation */
            $validationAnnotation = $this->reader->getClassAnnotation($controllerReflectionClass, Validation::class);
        }

        if ($validationAnnotation === null) {
            return;
        }

        $this->validationManager->validate($validationAnnotation->getValidationClass());
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getControllerAndAction(Request $request): array
    {
        $controller = $request->attributes->get('_controller');
        $parts = explode('::', $controller);

        return [
            'controller' => $parts[0],
            'action' => $parts[1] ?? null
        ];
    }

    /**
     * @param string $class
     * @return ReflectionClass|null
     */
    private function getControllerReflectionClass(string $class): ?ReflectionClass
    {
        try {
            return new ReflectionClass($class);
        } catch (ReflectionException $e) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
