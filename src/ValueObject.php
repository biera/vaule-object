<?php

namespace Biera;

/**
 * @package Biera\ValueObject
 *
 * @package Biera
 * @author Jakub Biernacki <kubiernacki@gmail.com>
 */
interface ValueObject
{
    /**
     * Return true if $other value object is equals
     *
     * @return bool
     */
    public function equals($other);
}