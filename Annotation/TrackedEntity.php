<?php

/*
 * This file is part of the UCS package.
 *
 * Copyright 2014 UCS <http://www.ucs-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace UCS\Component\ActivityTracker\Annotation;

/* Imports */
use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation used to track entity changes
 *
 * @Annotation
 * @Target("CLASS")
 *
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
final class TrackedEntity extends Annotation
{
    /** 
     * @var string
     */
    public $events = 'all';

    /** 
     * @var string
     */
    public $view;

    /** 
     * @var string
     */
    public $label;
}
