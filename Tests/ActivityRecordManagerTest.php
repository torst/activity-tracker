<?php

/*
 * This file is part of the UCS package.
 *
 * (c) Nicolas Macherey <nicolas.macherey@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace UCS\Component\ActivityTracker\Tests;

/* imports */
use UCS\Component\ActivityTracker\ActivityRecord;

/**
 * Unit Test Suite for ActivityRecordManager
 *
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class ActivityRecordManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $instance;
    protected $securityContext;

    protected function setup()
    {
        $this->securityContext = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');
        $this->instance = $this->getMockBuilder('UCS\Component\ActivityTracker\ActivityRecordManager')
            ->setConstructorArgs(array(
                $this->securityContext,
            ))
            ->getMockForAbstractClass();
    }

    /**
     * Test record creation
     */
    public function testCreateRecord()
    {
        $this->instance->expects($this->once())
          ->method('getClass')
          ->will($this->returnValue('UCS\Component\ActivityTracker\ActivityRecord'));

        $record = $this->instance->createRecord();
        $this->assertTrue($record instanceof \UCS\Component\ActivityTracker\ActivityRecord,
            '->createRecord() must create a valid record instance');
    }

    /**
     * Test find by record id
     */
    public function testFindRecordById()
    {
        $record = $this->getMock('UCS\Component\ActivityTracker\ActivityRecordInterface');
        $this->instance->expects($this->once())
          ->method('findRecordBy')
          ->with($this->equalTo(array('id' => 'record')))
          ->will($this->returnValue($record));

        $this->assertEquals($record, $this->instance->findRecordById('record'),
            '->findRecordById() must return an record or null');
    }

    /**
     * @expectedException UCS\Component\ActivityTracker\Exception\UnsupportedRecordException
     */
    public function testRefreshWrongClass()
    {
        $record = $this->getMock('UCS\Component\ActivityTracker\ActivityRecordInterface');
        $this->instance->expects($this->once())
          ->method('getClass')
          ->will($this->returnValue('UCS\Component\ActivityTracker\ActivityRecord'));

        // Wrong expected record got Mock
        $this->instance->reloadRecord($record);
    }

    /**
     * @expectedException UCS\Component\ActivityTracker\Exception\UnsupportedRecordException
     */
    public function testRefreshWrongInherit()
    {
        $record = $this->getMock('UCS\Component\ActivityTracker\ActivityRecordInterface');
        $this->instance->expects($this->once())
          ->method('getClass')
          ->will($this->returnValue('UCS\Component\ActivityTracker\ActivityRecordInterface'));

        // Wrong expected Record got Mock
        $this->instance->reloadRecord($record);
    }

    /**
     * @expectedException UCS\Component\ActivityTracker\Exception\RecordNotFoundException
     */
    public function testRefreshRecordNotFound()
    {
        $record = $this->getMock('UCS\Component\ActivityTracker\ActivityRecord');
        $record->expects($this->exactly(2))
          ->method('getId')
          ->will($this->returnValue(1));

        $this->instance->expects($this->once())
          ->method('getClass')
          ->will($this->returnValue('UCS\Component\ActivityTracker\ActivityRecord'));

        $this->instance->expects($this->once())
          ->method('findRecordBy');

        // Wrong expected Record got Mock
        $this->instance->reloadRecord($record);
    }

    /**
     * Test refresh record
     */
    public function testRefreshComplete()
    {
        $record = $this->getMock('UCS\Component\ActivityTracker\ActivityRecord');
        $record->expects($this->once())
          ->method('getId')
          ->will($this->returnValue(1));

        $this->instance->expects($this->once())
          ->method('getClass')
          ->will($this->returnValue('UCS\Component\ActivityTracker\ActivityRecord'));

        $this->instance->expects($this->once())
          ->method('findRecordBy')
          ->will($this->returnValue($record));

        // Wrong expected Record got Mock
        $this->assertEquals($record, $this->instance->reloadRecord($record),
            '->reloadRecord() the record reference must be returned');
    }

    /**
     * Test create a record
     */
    public function testRecord()
    {
        $this->instance->expects($this->once())
          ->method('saveRecord');

        $this->instance->expects($this->once())
          ->method('getClass')
          ->will($this->returnValue('UCS\Component\ActivityTracker\ActivityRecord'));

        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $user = $this->getMock('Symfony\Component\Security\Core\User\UserInterface');

        $token->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($user));

        $this->securityContext->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($token));

        $date = new \DateTime();
        $record = $this->instance->record('title', 'foo');

        $this->assertEquals('title', $record->getTitle());
        $this->assertEquals('foo', $record->getContent());
        $this->assertEquals($date, $record->getCreatedAt());
        $this->assertEquals($user, $record->getUser());
    }
}
