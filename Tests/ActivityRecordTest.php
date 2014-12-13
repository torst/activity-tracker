<?php

/*
 * This file is part of the UCS package.
 *
 * Copyright 2014 Nicolas Macherey <nicolas.macherey@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace UCS\Component\ActivityTracker\Tests;

/* Imports */
use UCS\Component\ActivityTracker\ActivityRecord;

/**
 * Unit Test Suite for FilterCollection
 *
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class ActivityRecordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the user property is properly handled
     */
    public function testUser()
    {
        $record = new ActivityRecord();
        $this->assertNull($record->getUser());
        $value = $this->getMock('Symfony\Component\Security\Core\User\UserInterface');
        $record->setUser($value);
        $this->assertEquals($value, $record->getUser());
    }

    /**
     * Test that the title property is properly handled
     */
    public function testTitle()
    {
        $record = new ActivityRecord();
        $this->assertNull($record->getTitle());
        $value = 'title';
        $record->setTitle($value);
        $this->assertEquals($value, $record->getTitle());
    }

    /**
     * Test that the content property is properly handled
     */
    public function testContent()
    {
        $record = new ActivityRecord();
        $this->assertNull($record->getContent());
        $value = 'content';
        $record->setContent($value);
        $this->assertEquals($value, $record->getContent());
    }

    /**
     * Test that the content property is properly handled
     */
    public function testCreatedAt()
    {
        $record = new ActivityRecord();
        $this->assertNull($record->getCreatedAt());
        $value = new \DateTime();
        $record->setCreatedAt($value);
        $this->assertEquals($value, $record->getCreatedAt());
    }
}