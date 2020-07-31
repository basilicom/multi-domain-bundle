<?php

namespace Basilicom\MultiDomainBundle\Controller;

use Basilicom\MultiDomainBundle\Service\Domain;
use Pimcore\Bundle\AdminBundle\Controller\AdminController;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Pimcore\Bundle\AdminBundle\Controller\Admin\ElementControllerBase;
use Pimcore\Bundle\AdminBundle\Controller\Traits\AdminStyleTrait;
use Pimcore\Bundle\AdminBundle\Controller\Traits\ApplySchedulerDataTrait;
use Pimcore\Bundle\AdminBundle\Helper\GridHelperService;
use Pimcore\Controller\Configuration\TemplatePhp;
use Pimcore\Controller\EventedControllerInterface;
use Pimcore\Controller\Traits\ElementEditLockHelperTrait;
use Pimcore\Db;
use Pimcore\Event\Admin\ElementAdminStyleEvent;
use Pimcore\Event\AdminEvents;
use Pimcore\Localization\LocaleServiceInterface;
use Pimcore\Logger;
use Pimcore\Model;
use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\ClassDefinition\Data\ManyToManyObjectRelation;
use Pimcore\Model\DataObject\ClassDefinition\Data\Relations\AbstractRelations;
use Pimcore\Model\DataObject\ClassDefinition\Data\ReverseManyToManyObjectRelation;
use Pimcore\Model\Element;
use Pimcore\Tool;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;


class DataObjectController extends FrontendController
{

    /**
     * @Route("/basilicom_multi_domain/preview", name="basilicom_multi_domain_dataobject_dataobject_previewversion", methods={"GET"})
     *
     * @param Request $request
     * @TemplatePhp()
     *
     * @throws \Exception
     *
     * @return array
     */
    public function previewVersionAction(Request $request)
    {
        //DataObject\AbstractObject::setDoNotRestoreKeyAndPath(true);

        $id = intval($request->get('id'));

        /*
        $version = Model\Version::getById($id);
        $object = $version->loadData();
           */

        $object = Product::getById(4);


        //DataObject\AbstractObject::setDoNotRestoreKeyAndPath(false);

        return ['object' => $object];

        /*
        if ($object) {
            if ($object->isAllowed('versions')) {
                return ['object' => $object];
            } else {
                throw $this->createAccessDeniedException('Permission denied, version id [' . $id . ']');
            }
        } else {
            throw $this->createNotFoundException('Version with id [' . $id . "] doesn't exist");
        }
        */
    }
}
