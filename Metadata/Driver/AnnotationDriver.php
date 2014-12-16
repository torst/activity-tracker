<?php

/*
 * This file is part of the UCS package.
 *
 * Copyright 2014 Nicolas Macherey <nicolas.macherey@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace UCS\Component\ActivityTracker\Metadata\Driver;

/* Imports */
use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\DriverInterface;
use \ReflectionClass;
use \ReflectionMethod;

/* Local */
use UCS\Component\ActivityTracker\Metadata\ClassMetadata;

/**
 * Loads Annotation Driver annotation data
 *
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class AnnotationDriver implements DriverInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * Constructor
     *
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Load the metadata for the given class
     *
     * @param ReflectionClass $reflection
     *
     * @return ClassMetadata
     */
    public function loadMetadataForClass(ReflectionClass $reflection)
    {
        $metadata = new ClassMetadata($reflection->name);

        $classTrackedEntity = $this->reader->getClassAnnotation($reflection, 'UCS\Component\ActivityTracker\Annotation\TrackedEntity');
        if (null === $classTrackedEntity) {
            return $metadata;
        }

        $metadata->setEvents((array) $classTrackedEntity->events)
            ->setContentTemplate($classTrackedEntity->contentTemplate)
            ->setTitleTemplate($classTrackedEntity->titleTemplate);

        return $metadata;
    }
}
