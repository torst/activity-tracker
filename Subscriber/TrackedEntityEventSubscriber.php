<?php

/*
 * This file is part of the UCS package.
 *
 * Copyright 2014 Nicolas Macherey <nicolas.macherey@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace UCS\Component\ActivityTracker\Subscriber;

/* Import */
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\EventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Common\Annotations\AnnotationReader;
use Metadata\MetadataFactory;
//use Symfony\Component\Templating\EngineInterface;
//use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/* Local Imports */
use UCS\Component\ActivityTracker\Annotation\TrackedEntity;
use UCS\Component\ActivityTracker\Metadata\ClassMetadata;
use UCS\Component\ActivityTracker\Metadata\Driver\AnnotationDriver;
//use UCS\Component\ActivityTracker\ActivityRecordManagerInterface;

/**
 * Event Subscriber for Doctrine events
 *
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class TrackedEntityEventSubscriber implements EventSubscriber
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Constructor
     *
     * @param ContainerInterface             $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Specifies the list of events to listen
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'onFlush',
        );
    }

    /**
     * Looks for translatable objects being inserted or updated
     * for further processing
     *
     * @param EventArgs $eventArgs
     */
    public function onFlush(EventArgs $eventArgs)
    {
        $om = $eventArgs->getEntityManager();
        $uow = $om->getUnitOfWork();

        // check all scheduled inserts for Tracked Entities objects
        foreach ($uow->getScheduledEntityInsertions() as $object) {
            $meta = $om->getClassMetadata(get_class($object));
            $record = $this->manageActivityRecord($object, $meta, 'create');

            if (null !== $record) {
                $newMeta = $om->getClassMetadata(get_class($record));
                $om->persist($record);
                $uow->computeChangeSet($newMeta, $record);
            }
        }

        // check all scheduled updates for Tracked Entities entities
        foreach ($uow->getScheduledEntityUpdates() as $object) {
            $meta = $om->getClassMetadata(get_class($object));
            $this->manageActivityRecord($meta, 'update');

            if (null !== $record) {
                $newMeta = $om->getClassMetadata(get_class($record));
                $om->persist($record);
                $uow->computeChangeSet($newMeta, $record);
            }
        }

        // check scheduled deletions for Tracked Entities entities
        foreach ($uow->getScheduledEntityDeletions() as $object) {
            $meta = $om->getClassMetadata(get_class($object));
            $this->manageActivityRecord($meta, 'delete');

            if (null !== $record) {
                $newMeta = $om->getClassMetadata(get_class($record));
                $om->persist($record);
                $uow->computeChangeSet($newMeta, $record);
            }
        }
    }

    /**
     * Creates a meta data factory
     *
     * @return MetadataFactory
     */
    private function getFactory()
    {
        $factory = new MetadataFactory(new AnnotationDriver(new AnnotationReader()));
        $factory->setIncludeInterfaces(true);

        return $factory;
    }

    /**
     * Return tracked entity
     *
     * @param mixed         $object
     * @param ClassMetadata $meta
     * @param string        $event
     *
     * @return TrackedEntity|null
     */
    private function manageActivityRecord($object, ClassMetadataInfo $meta, $event)
    {


        $securityContext = $this->container->get('security.context');
        $templating = $this->container->get('templating');
        $recordManager = $this->container->get('ucs.activity_record_manager');
        $user = $securityContext->getToken()->getUser();

        $metadata = $this
            ->getFactory()
            ->getMetadataForClass($meta->name);

        $events = $metadata->getEvents();
        $titleTemplate = $metadata->getTitleTemplate();
        $contentTemplate = $metadata->getContentTemplate();

        if (!in_array("all", $events) && !in_array($event, $events)) {
            return;
        }

        // Render the record properly
        $context = array(
            'entity' => $object,
            'event' => $event,
            'user' => $user,
        );

        $title = $templating->render($titleTemplate, $context);
        $content = $templating->render($contentTemplate, $context);

        return $recordManager->createRecordFrom($title, $content, $user);
    }
}
