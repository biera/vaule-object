<?php

namespace Biera;

/**
 * ImmutableTrait
 *
 * @author Jakub Biernacki <kubiernacki@gmail.com>
 * @package Biera
 */
trait Immutable
{
    /**
     * Prevent using __set magic method
     *
     * @throws \LogicException
     *
     * @param $k
     * @param $v
     */
    final public function __set($k, $v)
    {
        throw new \LogicException('Object is immutable and connot be altered.');
    }
}
