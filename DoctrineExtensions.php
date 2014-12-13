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

/* Imports */
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * Version class allows to checking the dependencies required
 * and the current version of doctrine extensions
 *
 * @author Nicolas Macherey <nicolas.macherey@gmail.com>
 */
final class DoctrineExtensions
{
    /**
     * Current version of extensions
     */
    const VERSION = '2.3.9';

    /**
     * Includes all extension annotations once
     */
    public static function registerAnnotations()
    {
        AnnotationRegistry::registerFile(__DIR__.'/Annotation/TrackedEntity.php');
    }
}
