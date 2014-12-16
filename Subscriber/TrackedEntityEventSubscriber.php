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
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/* Local Imports */
use UCS\Component\ActivityTracker\Annotation\TrackedEntity;
use UCS\Component\ActivityTracker\Metadata\ClassMetadata;
use UCS\Component\ActivityTracker\Metadata\Driver\AnnotationDriver;
use UCS\Component\ActivityTracker\ActivityRecordManagerInterface;

/**
 * Event Subscriber for Doctrine events
 *
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class TrackedEntityEventSubscriber implements EventSubscriber
{

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var ActivityRecordManagerInterface
     */
    private $recordManager;

    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * Constructor
     *
     * @param SecurityContextInterface       $securityContext
     * @param EngineInterface                $templating   
     * @param ActivityRecordManagerInterface $recordManager
     */
    public function __construct(SecurityContextInterface $securityContext, EngineInterface $templating, ActivityRecordManagerInterface $recordManager)
    {
        $this->templating = $templating;
        $this->recordManager = $recodManager;
        $this->securityContext = $securityContext;
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
     * @param EventArgs $args
     */
    public function onFlush(EventArgs $args)
    {
        $om = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        // check all scheduled inserts for Tracked Entities objects
        foreach ($ea->getScheduledObjectInsertions($uow) as $object) {
            $meta = $om->getClassMetadata(get_class($object));
            $record = $this->manageActivityRecord($object, $meta, 'create');

            if (null !== $record) {
                $newMeta = $om->getClassMetadata(get_class($record));
                $uow->computeChangeSet($newMeta, $record);
            }
        }

        // check all scheduled updates for Tracked Entities entities
        foreach ($ea->getScheduledObjectUpdates($uow) as $object) {
            $meta = $om->getClassMetadata(get_class($object));
            $this->manageActivityRecord($meta, 'update');

            if (null !== $record) {
                $newMeta = $om->getClassMetadata(get_class($record));
                $uow->computeChangeSet($newMeta, $record);
            }
        }

        // check scheduled deletions for Tracked Entities entities
        foreach ($ea->getScheduledObjectDeletions($uow) as $object) {
            $meta = $om->getClassMetadata(get_class($object));
            $this->manageActivityRecord($meta, 'delete');

            if (null !== $record) {
                $newMeta = $om->getClassMetadata(get_class($record));
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
        $metadata = $this
            ->getFactory()
            ->getMetadataForClass($meta->name);

        $events = $metadata->getEvents();
        $titleTemplate = $metadata->getTitleTemplate();
        $contentTemplate = $metadat->getContentTemplate();

        if (!in_array("all", $events) && !in_array($event, $events)) {
            return;
        }

        // Render the record properly
        $context = array(
            'entity' => $object,
            'envent' => $event,
        );

        $title = $template->render($titleTemplate, $context);
        $content = $template->render($contentTemplate, $context);

        return $this->recordManager->createRecordFrom($title, $content, $this->securityContext->getToken()->getUser());
    }
}
