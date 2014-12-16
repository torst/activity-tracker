<?php

/*
 * This file is part of the UCS package.
 *
 * Copyright 2014 Nicolas Macherey <nicolas.macherey@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace UCS\Component\ActivityTracker\Tests\Metadata;

/* Imports */
use Doctrine\Common\Annotations\AnnotationReader;
use Metadata\MetadataFactory;

/* Local Imports */
use UCS\Component\ActivityTracker\Metadata\ClassMetadata;
use UCS\Component\ActivityTracker\DoctrineExtensions;
use UCS\Component\ActivityTracker\Metadata\Driver\AnnotationDriver;

/**
 * Unit Test Suite for FilterCollection
 *
 * FIXME: This class test both cases (ClassMetaData and AnnotationDriver)
 *
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class ClassMetadataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test analyze a class with the annotation
     */
    public function testAnalyze()
    {
        $metadata = $this
            ->getFactory()
            ->getMetadataForClass('UCS\Component\ActivityTracker\Tests\Fixtures\TrackableEntity');

        $this->assertTrue($metadata instanceof ClassMetadata);
        $this->assertEquals(array('all'), $metadata->getEvents());
        $this->assertEquals('DummyLabel', $metadata->getTitleTemplate());
        $this->assertEquals('DummyView', $metadata->getContentTemplate());
    }

    /**
     * Register annotations and creates a meta data factory
     *
     * @return MetadataFactory
     */
    private function getFactory()
    {
        DoctrineExtensions::registerAnnotations();

        $factory = new MetadataFactory(new AnnotationDriver(new AnnotationReader()));
        $factory->setIncludeInterfaces(true);

        return $factory;
    }
}