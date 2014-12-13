<?php

/*
 * This file is part of the UCS package.
 *
 * Copyright 2014 UCS <http://www.ucs-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace UCS\Component\ActivityTracker;

/* imports */
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Main class to implement in order to create an activity trackers
 *
 * @author Nicolas MACHEREY <nicolas.macherey@gmail.com>
 */
interface ActivityRecordInterface
{
    /**
     * Get the id
     *
     * @return mixed
     */
    public function getId();

    /**
     * Get the user associated to this tracker
     *
     * @return UserInterface
     */
    public function getUser();

    /**
     * Set the user associated
     *
     * @param UserInterface $user
     *
     * @return self
     */
    public function setUser(UserInterface $user);

    /**
     * Get the activity title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set the title
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle($title);

    /**
     * Retrieve the content of the 
     *
     * @return string
     */
    public function getContent();

    /**
     * Set the activity content
     *
     * @param string $content
     *
     * @return self
     */
    public function setContent($content);

    /**
     * Get the date the avtivity was created
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Set the creation date
     *
     * @param \DateTime $createdAt
     *
     * @return self
     */
    public function setCreatedAt(\DateTime $createdAt);
}