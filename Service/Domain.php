<?php


namespace Basilicom\MultiDomainBundle\Service;


use Basilicom\MultiDomainBundle\Model\DataObject;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;
use Pimcore\Logger;

class Domain
{

    static $domains = [];

    public static $siblingCache = [];

    private static function log($msg)
    {
        Logger::debug("MultiDomainBundle " . $msg);
    }

    public static function isSelected($check = null)
    {
        // any domain selected?
        if ($check == null) {
            return (count(self::$domains) > 0);
        }

        // specific domain selected?
        return in_array($check, self::$domains);
    }

    public static function get()
    {
        return self::$domains;
    }

    public static function select(Array $domains)
    {
        self::$domains = $domains;
    }

    public static function clear()
    {
        self::$domains = [];
    }

    public static function isSelectedInObject($object)
    {
        return (($object instanceof DataObject) && in_array($object->getDomain(), self::$domains));
    }

    public static function isDomainVariant($object)
    {
        return ((object instanceof DataObjectf) && !empty($object->getDomain()));
    }


    public static function pushIfDomainSibling($sibling)
    {
        self::log('pushIfDomainSibling, domain: '.$sibling->getDomain().$sibling->getKey());
        if (self::isSelectedInObject($sibling)) {
            self::log('pushIfDomainSibling, PUSHED domain: '.$sibling->getDomain().$sibling->getKey());
            self::$siblingCache[$sibling->getDomain()] = $sibling;
        }
    }

    public static function popNextSibling()
    {
        foreach (self::$domains as $domain) {

            self::log('popNextSibling, trying '.$domain .' in '.implode(array_keys(self::$siblingCache)));

            $sibling = self::$siblingCache[$domain];
            if (is_object($sibling)) {
                self::log('popNextSibling, FOUND! '.$domain);

                self::$siblingCache[$domain] = null; // remove, as it will be used ..
                return $sibling;
            }
        }
        return null;
    }

    public static function clearSiblings()
    {

        self::log('clearSiblings!');

        //throw new \Exception('clear');
        self::$siblingCache = [];
    }

    public static function existSiblings()
    {
        return (count(self::$siblingCache)>0);
    }

    public static function isDomainKey($key)
    {
        return ($key == 'domain');
    }

}
