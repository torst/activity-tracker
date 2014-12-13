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
 * Default activity record class implementation
 *
 * @author Nicolas MACHEREY <nicolas.macherey@gmail.com>
 */
class ActivityRecord implements ActivityRecordInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var \DateTime
     */
    protected $createdAt;
 
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }
 
    /**
     * {@inheritdoc}
     */
    public function getUser()
    {
        return $this->user;
    }
     
    /**
     * {@inheritdoc}
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        
        return $this;
    }
     
    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->title;
    }
     
    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        $this->title = $title;
        
        return $this;
    }
     
    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->content;
    }
     
    /**
     * {@inheritdoc}
     */
    public function setContent($content)
    {
        $this->content = $content;
        
        return $this;
    }
     
    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
     
    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
}