<?php


namespace Basilicom\MultiDomainBundle\AdminStyle;

use Basilicom\MultiDomainBundle\Model\DataObject;
use Basilicom\MultiDomainBundle\Tool\ForceInheritance;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\Element\AdminStyle;
use Pimcore\Model\Element\ElementInterface;

class DomainObject extends AdminStyle
{
    /** @var ElementInterface */
    protected $element;

    public function __construct($element)
    {
        parent::__construct($element);

        $this->element = $element;

        if (($element instanceof DataObject)
            && ($element->getType() == AbstractObject::OBJECT_TYPE_VARIANT)
            && ($element->getDomain() != '')) {

            $this->setElementIcon('/bundles/pimcoreadmin/img/flat-color-icons/mind_map.svg');
            $this->setElementIconClass(null);
        }

    }

    public function getElementQtipConfig()
    {
        $element = $this->element;

        if (($element instanceof DataObject)
            && ($element->getType() == AbstractObject::OBJECT_TYPE_VARIANT)
            && ($element->getDomain() !== null)) {

            // @todo make domain field (getDomain) configurable!
            $text = 'Domain: ' . $element->getDomain();
            return [
                "title" => 'ID: ' . $element->getId(),
                "text" => $text
            ];
        }

        return parent::getElementQtipConfig();
    }
}
