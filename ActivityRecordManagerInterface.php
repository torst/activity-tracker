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

/**
 * Default record manager interface
 *
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
interface ActivityRecordManagerInterface
{
    /**
     * Creates an empty record instance.
     *
     * @return ActivityRecordInterface
     */
    public function createRecord();

    /**
     * Create a new record
     *
     * @param string        $title  
     * @param string        $content
     * @param UserInterface $user
     *
     * @return ActivityRecord
     */
    public function record($title, $content, UserInterface $user = null);

    /**
     * Create a new record from the given title and content
     *
     * @param string        $title  
     * @param string        $content
     * @param UserInterface $user
     *
     * @return ActivityRecord
     */
    public function createRecordFrom($title, $content, UserInterface $user = null);

    /**
     * Deletes a record.
     *
     * @param ActivityRecordInterface $record
     *
     * @return void
     */
    public function deleteRecord(ActivityRecordInterface $record);

    /**
     * Finds one record by the given criteria.
     *
     * @param array $criteria
     *
     * @return ActivityRecordInterface
     */
    public function findRecordBy(array $criteria);

    /**
     * Find an record by its identifier.
     *
     * @param string $id
     *
     * @return ActivityRecordInterface or null if record does not exist
     */
    public function findRecordById($id);

    /**
     * Returns a collection with all record instances.
     *
     * @return \Traversable
     */
    public function findRecords();

    /**
     * Returns the record's fully qualified class name.
     *
     * @return string
     */
    public function getClass();

    /**
     * Reloads an record.
     *
     * @param ActivityRecordInterface $record
     *
     * @return void
     */
    public function reloadRecord(ActivityRecordInterface $record);

    /**
     * Updates an record.
     *
     * @param ActivityRecordInterface $record
     *
     * @return void
     */
    public function updateRecord(ActivityRecordInterface $record);

    /**
     * Saves a record.
     *
     * @param ActivityRecordInterface $record
     *
     * @return void
     */
    public function saveRecord(ActivityRecordInterface $record);
}