<?php

namespace Basilicom\MultiDomainBundle\Model;

use Pimcore\Model\DataObject\ClassDefinition\Data\Select;

class MultiDomainDataType extends Select
{
    /**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = 'multiDomainSelect';
}
