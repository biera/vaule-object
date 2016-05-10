<?php

namespace spec\Biera;

use PhpSpec\ObjectBehavior;
use Biera\ImmutableValueObject;

class ImmutableValueObjectSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('spec\Biera\ValueObject');
    }

    function it_is_equal()
    {
        $this->beConstructedWith(
            $this->getConstructorParameter()
        );

        $this->equals(new ValueObject($this->getConstructorParameter()))->shouldBe(true);
    }

    function it_is_not_equal()
    {
        $this->beConstructedWith(new \stdClass);

        $this->equals(new ValueObject(new \stdClass))->shouldBe(false);
    }

    private function getConstructorParameter()
    {
        return array(
            new Point(1, 1),
            new Rectangle(
                new Point(0.0, 10.0),
                new Point(10.0, 0.0)
            ),
            new Path(
                new Point(0.0, 0.0),
                new Point(10.0, 1.0),
                new Point(20.0, 3.0)
            ),
            1.0
        );
    }

}

class Point extends ImmutableValueObject
{
    private $x;

    private $y;

    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }
}

class Rectangle extends ImmutableValueObject
{
    private $upperLeftCorner;

    private $lowerRightCorner;

    public function __construct(Point $upperLeftCorner, Point $lowerRightCorner)
    {
        $this->upperLeftCorner = $upperLeftCorner;
        $this->lowerRightCorner = $lowerRightCorner;
    }
}

class Path extends ImmutableValueObject
{
    private $points;

    public function __construct()
    {
        $this->points = new \ArrayObject();

        foreach(func_get_args() as $point) {
            $this->points->append($point);
        }
    }
}

class ValueObject extends ImmutableValueObject
{
    private $property;

    public function __construct($property)
    {
        $this->property = $property;
    }
}