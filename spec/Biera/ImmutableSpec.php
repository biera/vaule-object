<?php

namespace spec\Biera;

use PhpSpec\ObjectBehavior;

/**
 * Specification for \Biera\Immutable
 */
class ImmutableSpec extends ObjectBehavior
{
    function it_throws_exception_when_set_property_attempted()
    {
        $this->beAnInstanceOf('spec\Biera\Immutable');

        try {
            $this->property = '';
            throw new \Exception('LogicException should be thrown.');
        } catch (\LogicException $ex) {
            if ($ex->getMessage() !== 'Object is immutable and connot be altered.') {
                throw $ex;
            }
        }
    }
}

class Immutable
{
    use \Biera\Immutable;
}