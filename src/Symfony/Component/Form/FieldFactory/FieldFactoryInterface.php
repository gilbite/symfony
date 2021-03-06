<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\FieldFactory;

/**
 * Automatically creates form fields for properties of an object
 *
 * @author Bernhard Schussek <bernhard.schussek@symfony-project.com>
 */
interface FieldFactoryInterface
{
    /**
     * Returns a field for a given property name
     *
     * @param  object $object     The object to create a field for
     * @param  string $property   The name of the property
     * @param  array $options     Custom options for creating the field
     * @return FieldInterface     A field instance
     */
    function getInstance($object, $property, array $options = array());
}
