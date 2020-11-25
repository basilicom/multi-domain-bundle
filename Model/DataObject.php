<?php


namespace Basilicom\MultiDomainBundle\Model;


use Basilicom\MultiDomainBundle\AdminStyle\DomainObject;
use Basilicom\MultiDomainBundle\Service\Domain;
use Pimcore\Logger;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\Exception\InheritanceParentNotFoundException;

use Pimcore\Model;
use Pimcore\Model\DataObject\Product;

class DataObject extends \Pimcore\Model\DataObject\Concrete implements Model\DataObject\PreGetValueHookInterface
{

    private $domainSiblingCache = null;

    private function log($msg, $key="\t")
    {
        if (Domain::isDomainKey($key)) {
            return;
        }

        $msg = '['.implode('|',Domain::get()).'] '
            . $this->getKey() . ': ' . $key . ' | ' . $msg;

        Logger::debug("MultiDomainBundle " . $msg);
    }

    /**
     * @param string $key
     * @param mixed $params
     *
     * @return mixed
     *
     * @throws InheritanceParentNotFoundException
     */
    public function getValueFromParent($key, $params = null)
    {

        $this->log('getValueFromParent START', $key);

        // @todo make domain field (domain) configurable!
        if (Domain::isDomainKey($key)) {
            $this->log('getValueFromParent DOMAIN_KEY_SKIP', $key);
            return null; // do not deep dive on domain value (prevent recursion)!
        }

        $parent = $this->getNextParentForInheritance();
        if ($parent) {
            $method = 'get' . $key;
            if (method_exists($parent, $method)) {
                $this->log('getValueFromParent CALLING_PARENT/SIBLING: '.$parent->getKey(), $key);
                $value = $parent->$method($params);
                if ($value!==null) {
                    $this->log('getValueFromParent VALUE FOUND - clearing siblings', $key);
                    Domain::clearSiblings();
                    return $value;
                }
            }

            throw new InheritanceParentNotFoundException(sprintf('Parent object does not have a method called `%s()`, unable to retrieve value for key `%s`', $method, $key));
        }

        throw new InheritanceParentNotFoundException('No parent object available to get a value from');
    }


    /**
     * @return AbstractObject|null
     */
    public function getNextParentForInheritance()
    {

        $this->log('getNextParentForInheritance START');

        // if a Domain is selected, try to "inject" a sibling into the variant hierarchy!
        if (Domain::isSelected()) {

            $this->log('getNextParentForInheritance: n domains are selected ... ');

            // only add new siblings;
            // 1) once for a given hierarchy level
            // 2) only for real variants

            if (!Domain::isSelectedInObject($this)) {

                $this->log('getNextParentForInheritance $this is normal variant!');

                if (!Domain::existSiblings()) {

                    $this->log('getNextParentForInheritance there are no siblings fetched, yet!');

                    $siblings = $this->getSiblings([self::OBJECT_TYPE_VARIANT]);

                    foreach ($siblings as $sibling) {

                        $this->log('getNextParentForInheritance trying to PUSH Sibling ['.$sibling->getFullPath().']');
                        Domain::pushIfDomainSibling($sibling);
                    }
                }
            }

            $sibling = Domain::popNextSibling();
            if (is_object($sibling)) {
                $this->log('getNextParentForInheritance RETURN FOUND Sibling ['.$sibling->getFullPath().']');
                return $sibling;
            }
        }

        // if we get here, there are no siblings, or all have been processed ... mark as cleared!
        //Domain::clearSiblings();

        $parent = $this->getClosestParentOfClass($this->getClassId());
            if (null == $parent) {
                $this->log('getNextParentForInheritance, NOT found, using: NULL');
            } else {
                $this->log('getNextParentForInheritance, NOT found, using Parent:'.$this->getClosestParentOfClass($this->getClassId())->getFullPath());
            }
        return $this->getClosestParentOfClass($this->getClassId());
    }

    /**
     * Look for a domain specific variant of the object, use this objects value if not null
     * @param string $key
     * @return mixed
     */
    public function preGetValue(string $key)
    {

        $value = null;

        $this->log('preGetValue CHILD_CHECK ...',$key);

        // only scan childs for "real" attributes ..
        if (!Domain::isDomainKey($key)) {

            $value = $this->getChildDomainValue($key);
        }

        if (($value === "")|($value === null)) {
            $value = $this->$key;
        }

        if ($value === "") {
            $value = null;
        }

        $this->log('preGetValue ['.$value.']',$key);

        if ($value !== null) {

            $this->log('preGetValue FOUND==clearing sibling cache prematurely!',$key);

            if (!Domain::isDomainKey($key)) {
                Domain::clearSiblings();
            }
        }

        return $value;
    }

    private function getChildDomainValue($key)
    {
        if (Domain::isSelected() && (!Domain::isDomainKey($key))) {

            // todo: do not scan childs of domain variants!

            $variants = $this->getChildren([AbstractObject::OBJECT_TYPE_VARIANT]);

            // 1st: find candidates!
            $domainVariants = [];
            foreach ($variants as $variant) {

                // @todo make domain field (getDomain) configurable!
                if (in_array($variant->getDomain(), Domain::get())) {

                    if (!empty($variant->$key)) {

                        $domainVariants[$variant->getDomain()] = $variant->$key;
                    }
                }
            }

            $this->log('getChildDomainValue CHILDS: [' . implode(',', array_keys($domainVariants)).']', $key);

            if (count($domainVariants) > 0) {

                // 2nd consider order and deliver 1st selected domain first!
                foreach (Domain::get() as $domain) {
                    $this->log('getChildDomainValue testing [' . $domain . '] ...', $key);
                    if (array_key_exists($domain, $domainVariants)) {
                        $this->log('getChildDomainValue testing [' . $domain . '] FOUND', $key);
                        return $domainVariants[$domain];
                    }
                }
            }
        }

        $this->log('getChildDomainValue NONE_FOUND.', $key);

        return null;
    }

    public function isDomainVariant():bool
    {
        return (Domain::isDomainVariant($this));
    }
}
