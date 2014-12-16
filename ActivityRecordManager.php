<?php

/*
 * This file is part of the UCS package.
 *
 * Copyright 2014 Nicolas Macherey <nicolas.macherey@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace UCS\Component\ActivityTracker;

/* Imports */
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\SecurityContextInterface;

/* Local Imports */
use UCS\Component\ActivityTracker\Exception\UnsupportedRecordException;
use UCS\Component\ActivityTracker\Exception\RecordNotFoundException;

/**
 * Abstract Record Manager implementation which can be used as base class for your
 * concrete manager.
 *
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
abstract class ActivityRecordManager implements ActivityRecordManagerInterface
{
    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * Constructor
     *
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * Create a new record
     *
     * @param string $title  
     * @param string $content
     *
     * @return ActivityRecord
     */
    public function record($title, $content)
    {
        $record = $this->createRecord();
        $record->setUser($this->securityContext->getToken()->getUser())
            ->setCreatedAt(new \DateTime())
            ->setTitle($title)
            ->setContent($content);

        $this->saveRecord($record);

        return $record;
    }

    /**
     * Create a new record from the given title and content
     *
     * @param string $title  
     * @param string $content
     *
     * @return ActivityRecord
     */
    public function createRecordFrom($title, $content)
    {
        $record = $this->createRecord();
        $record->setUser($this->securityContext->getToken()->getUser())
            ->setCreatedAt(new \DateTime())
            ->setTitle($title)
            ->setContent($content);

        return $record;
    }

    /**
     * Creates an empty record instance.
     *
     * @return ActivityRecordInterface
     */
    public function createRecord()
    {
        $class = $this->getClass();
        $record = new $class();

        return $record;
    }

    /**
     * {@inheritdoc}
     */
    public function findRecordById($id)
    {
        return $this->findRecordBy(array('id' => $id));
    }

    /**
     * {@inheritdoc}
     */
    public function reloadRecord(ActivityRecordInterface $record)
    {
        $class = $this->getClass();
        if (!$record instanceof $class) {
            throw new UnsupportedRecordException('Record class is not supported.');
        }

        if (!$record instanceof ActivityRecord) {
            throw new UnsupportedRecordException(sprintf('Expected an instance of UCS\Component\ActivityTracker\ActivityRecord, but got "%s".', get_class($record)));
        }

        $reloadedRecord = $this->findRecordById($record->getId());

        if (null === $reloadedRecord) {
            throw new RecordNotFoundException(sprintf('Record with ID "%d" could not be reloaded.', $record->getId()));
        }

        return $reloadedRecord;
    }
}
