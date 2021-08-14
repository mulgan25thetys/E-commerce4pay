<?php

namespace App\Core\Content\EventListener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Ecouteur d'evenemment distinct.
 */
class DoctrinePropertyChangeEventListener
{
    private array $listeners;
    private PropertyAccessorInterface $propertyAccessor;

    /**
     * @param array<string, mixed> $listeners
     */
    public function __construct(array $listeners, PropertyAccessorInterface $propertyAccessor)
    {
        $this->listeners = $listeners;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @param object $entity
     *
     * @return void
     */
    public function prePersist($entity)
    {
        $listeners = $this->listeners[get_class($entity)] ?? null;
        if ($listeners === null) {
            return;
        }
        foreach ($listeners as $key => $propertyListeners) {
            if ($value = $this->propertyAccessor->getValue($entity, $key)) {
                foreach ($propertyListeners as $listener) {
                    $method = $listener['method'];
                    $listener['listener']->$method($entity, $value, null);
                }
            }
        }
    }

    /**
     * @param object $entity
     *
     * @return void
     */
    public function preUpdate($entity, PreUpdateEventArgs $event)
    {
        $listeners = $this->listeners[get_class($entity)] ?? null;
        if ($listeners === null) {
            return;
        }
        $changeSet = $event->getEntityManager()->getUnitOfWork()->getEntityChangeSet($entity);
        foreach ($listeners as $key => $propertyListeners) {
            if (in_array($key, array_keys($changeSet))) {
                foreach ($propertyListeners as $listener) {
                    $method = $listener['method'];
                    $listener['listener']->$method($entity, $changeSet[$key][1], $changeSet[$key][0]);
                }
            }
        }
    }
}
