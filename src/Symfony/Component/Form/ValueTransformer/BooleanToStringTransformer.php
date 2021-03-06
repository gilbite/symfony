<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\ValueTransformer;

use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Transforms between a boolean and a string.
 *
 * @author Bernhard Schussek <bernhard.schussek@symfony-project.com>
 * @author Florian Eckerstorfer <florian@eckerstorfer.org>
 */
class BooleanToStringTransformer extends BaseValueTransformer
{
    /**
     * Transforms a boolean into a string.
     *
     * @param  boolean $value   Boolean value.
     * @return string           String value.
     */
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        if (!is_bool($value)) {
            throw new UnexpectedTypeException($value, 'boolean');
        }

        return true === $value ? '1' : '';
    }

    /**
     * Transforms a string into a boolean.
     *
     * @param  string $value  String value.
     * @return boolean        Boolean value.
     */
    public function reverseTransform($value, $originalValue)
    {
        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        return '' !== $value;
    }

}