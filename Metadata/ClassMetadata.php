<?php

/*
 * This file is part of the UCS package.
 *
 * Copyright 2014 Nicolas Macherey <nicolas.macherey@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace UCS\Component\ActivityTracker\Metadata;

/* Imports */
use Metadata\MergeableInterface;
use Metadata\MergeableClassMetadata;

/* Local Imports */
use UCS\Component\ActivityTracker\Exception\InvalidArgumentException;

/**
 * Contains class metadata information
 *
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
class ClassMetadata extends MergeableClassMetadata
{
    /**
     * @var string
     */
    protected $contentTemplate;

    /**
     * @var string
     */
    protected $titleTemplate;
    
    /**
     * @var array
     */
    protected $events = array();

    /**
     * Merge with the given metadata
     *
     * @param MergeableInterface $metadata
     */
    public function merge(MergeableInterface $metadata)
    {
        if (!$metadata instanceof ClassMetadata) {
            throw new InvalidArgumentException('$metadata must be an instance of ClassMetadata.');
        }

        $this->events = array_merge($this->events, $metadata->getEvents());
        parent::merge($metadata);
    }
 
    /**
     * Gets the value of contentTemplate.
     *
     * @return string
     */
    public function getContentTemplate()
    {
        return $this->contentTemplate;
    }
     
    /**
     * Sets the value of contentTemplate.
     *
     * @param string $contentTemplate the content template
     *
     * @return self
     */
    public function setContentTemplate($contentTemplate)
    {
        $this->contentTemplate = $contentTemplate;
        
        return $this;
    }
     
    /**
     * Gets the value of titleTemplate.
     *
     * @return string
     */
    public function getTitleTemplate()
    {
        return $this->titleTemplate;
    }
     
    /**
     * Sets the value of titleTemplate.
     *
     * @param string $titleTemplate the title template
     *
     * @return self
     */
    public function setTitleTemplate($titleTemplate)
    {
        $this->titleTemplate = $titleTemplate;
        
        return $this;
    }
     
    /**
     * Gets the value of events.
     *
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }
     
    /**
     * Sets the value of events.
     *
     * @param array $events the events
     *
     * @return self
     */
    public function setEvents(array $events)
    {
        $this->events = (array) $events;
        
        return $this;
    }
}
