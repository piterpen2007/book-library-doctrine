<?php

namespace EfTech\BookLibrary\DoctrineEventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

/**
 * Подписчик на события связанные с сущностью
 */
class EntityEventSubscriber implements EventSubscriber
{
    /**
     * Логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getSubscribedEvents(): array
    {
        return [Events::postLoad];
    }

    /**
     * Обработчик события загрузки сущности
     *
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args): void
    {
        $this->logger->debug('Event postLoad:' . get_class($args->getEntity()));
    }
}