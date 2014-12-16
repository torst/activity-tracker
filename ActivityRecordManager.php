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
use Symfony\Component\Security\Core\User\UserInterface;

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
     * {@inheritdoc}
     */
    public function record($title, $content, UserInterface $user = null)
    {
        $record = $this->createRecord();
        $record->setUser($user)
            ->setCreatedAt(new \DateTime())
            ->setTitle($title)
            ->setContent($content);

        $this->saveRecord($record);

        return $record;
    }

    /**
     * {@inheritdoc}
     */
    public function createRecordFrom($title, $content, UserInterface $user = null)
    {
        $record = $this->createRecord();
        $record->setUser($user)
            ->setCreatedAt(new \DateTime())
            ->setTitle($title)
            ->setContent($content);

        return $record;
    }

    /**
     * {@inheritdoc}
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
