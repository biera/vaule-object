<?php

namespace Biera;

/**
 * BaseValueObject
 *
 * @package Biera
 * @author Jakub Biernacki <kubiernacki@gmail.com>
 */
abstract class BaseValueObject implements ValueObject
{
    /**
     * {@inheritDoc}
     */
    public function equals($other)
    {
        if (!$this->areSameType($this, $other)) {
            return false;
        }

        $sRef = new \ReflectionClass($this);
        $oRef = new \ReflectionClass($other);

        foreach ($sRef->getProperties() as $sPropRef) {
            $oPropRef = $oRef->getProperty($sPropRef->getName());
            $sPropRef->setAccessible(true);
            $oPropRef->setAccessible(true);

            if ($this->compare($sPropRef->getValue($this), $oPropRef->getValue($other))) {
                continue;
            }

            return false;
        }

        return true;
    }

    /**
     * Compare two parameters.
     *
     * It returns true if parameters are equals, false otherwise.
     *
     * @param mixed $varA
     * @param mixed $varB
     *
     * @return bool
     */
    private function compare($varA, $varB)
    {
        if ($this->areSameType($varA, $varB)) {
            if (is_object($varA)) {
                $equals = $this->compareObjects($varA, $varB);
            } else if (is_array($varA)) {
                $equals = $this->compareCollections($varA, $varB);
            } else {
                $equals = ($varA === $varB);
            }
        } else {
            $equals = false;
        }

        return $equals;
    }

    /**
     * Compare two objects
     *
     * This method is private and is called from self::compare
     * that guarantees parameters are always of the same type
     *
     * @param object $objA
     * @param object $objB
     *
     * @return bool
     */
    private function compareObjects($objA, $objB)
    {
        $objARef= new \ReflectionClass($objA);

        if ($objARef->implementsInterface('Biera\ValueObject')) {
            $equals = $objA->equals($objB);

        } else if($objARef->implementsInterface('Traversable')) {

            $equals = $this->compareCollections($objA, $objB);
        } else {

            $equals = ($objA === $objB);
        }

        return $equals;
    }

    /**
     * Compare two collections.
     *
     * It expects two parameters of the same type as input:
     * either arrays or object of class which implements \Traversable.
     *
     * Keys are not compared.
     *
     * This method is private and is called from self::compare and self::comapreObject
     * that guarantees parameters are always of the same type
     *
     * @param array|\Traversable $colA
     * @param array|\Traversable $colB
     *
     * @return bool
     */
    private function compareCollections($colA, $colB)
    {
        // Traversable
        if (is_object($colA)) {
            $isCountable = in_array('Countable', class_implements($colA));

            if ($isCountable && ($colA->count() !== $colB->count())) {
                return false;
            }

            list($iteratorA, $iteratorB) = $colA instanceof \Iterator ? array($colA, $colB) : array($colA->getIterator(), $colB->getIterator());

            $iteratorA->rewind();
            $iteratorB->rewind();

            while (($isIteratorAValid = $iteratorA->valid()) && ($isIteratorBValid = $iteratorB->valid())) {

                if (!$this->compare($iteratorA->current(), $iteratorB->current())) {
                    return false;
                }

                $iteratorA->next();
                $iteratorB->next();
            }

            return $isCountable || !($isIteratorAValid || $isIteratorBValid);

        // array
        } else {

            if (count($colA) !== count($colB)) {
                return false;
            }

            reset($colB);

            foreach ($colA as $currA) {

                if (!$this->compare($currA, current($colB))) {
                    return false;
                }

                next($colB);
            }

            return true;
        }
    }

    /**
     * Check wheter two parameters are of the same type.
     *
     * @param mixed $varA
     * @param mixed $varB
     *
     * @return true
     */
    private function areSameType($varA, $varB)
    {
        return (gettype($varA) === gettype($varB)) && (!is_object($varA) || (get_class($varA) === get_class($varB)));
    }
}