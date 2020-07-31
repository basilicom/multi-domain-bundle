<?php

namespace Basilicom\MultiDomainBundle;

use Basilicom\MultiDomainBundle\AdminStyle\DomainObject;
use Basilicom\MultiDomainBundle\Model\DataObject;
use Basilicom\MultiDomainBundle\Service\Domain;
use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class BasilicomMultiDomainBundle extends AbstractPimcoreBundle
{
    public function getJsPaths()
    {
        return [
            '/bundles/basilicommultidomain/js/pimcore/startup.js'
        ];
    }

    public function boot()
    {
        \Pimcore::getEventDispatcher()->addListener(\Pimcore\Event\AdminEvents::RESOLVE_ELEMENT_ADMIN_STYLE,
            function(\Pimcore\Event\Admin\ElementAdminStyleEvent $event) {
                $element = $event->getElement();
                // decide which default styles you want to override
                //if ($element instanceof DataObject) {
                $event->setAdminStyle(new DomainObject($element));
                //}
            });
        parent::boot();
    }
}
