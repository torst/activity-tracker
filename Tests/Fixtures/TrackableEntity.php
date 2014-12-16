<?php

/*
 * This file is part of the UCS package.
 *
 * Copyright 2014 Nicolas Macherey <nicolas.macherey@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace UCS\Component\ActivityTracker\Tests\Fixtures;

/* Imports */
use Doctrine\ORM\Mapping as ORM;
use UCS\Component\ActivityTracker\Annotation\TrackedEntity;

/**
 * Tracked Entity sample
 *
 * @TrackedEntity(events="all", titleTemplate="DummyLabel", contentTemplate="DummyView")
 */
class TrackableEntity
{
    /**
     * @ORM\Column(name="name", type="text", length=255)
     *
     * @var string
     */
    private $name;
}
