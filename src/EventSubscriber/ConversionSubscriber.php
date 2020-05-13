<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;

use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ConversionSubscriber implements EventSubscriberInterface
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {

        return [
            'kernel.controller' => 'convert'
        ];
    }

    public function convert(ControllerEvent $event)
    {
        $request = $event->getRequest();
        $controller = $event->getController();
        if (!is_array($controller)) {
            return;
        }


        $className = get_class($controller[0]);
        $methodName = $controller[1];


        $reflexion = new ReflectionMethod($className, $methodName);
        $parameters = $reflexion->getParameters();

        foreach ($parameters as $parameter) {
            $name = $parameter->getName();

            if ($this->isInvalidType($parameter)) {
                continue;
            }

            $type = (string) $parameter->getType();

            $reflexionClass = new ReflectionClass($type);

            if ('App\Entity' !== $reflexionClass->getNamespaceName()) {
                continue;
            }

            if (!$request->attributes->has('id')) {
                continue;
            }

            $id = $request->attributes->get('id');

            $repository = $this->em->getRepository($type);
            $object = $repository->find($id);

            if (!$object) {
                throw new NotFoundHttpException("l'article demmandé n'exite pas ");
            }
            $request->attributes->set($name, $object);
        }
    }

    protected function isInvalidType(ReflectionParameter $parameter): bool
    {
        return !$parameter->hasType() || $parameter->getType()->isBuiltin();
    }
}
