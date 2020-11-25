<?php

namespace Basilicom\MultiDomainBundle\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends FrontendController
{

    /**
     * @Route("/basilicom_multi_domain")
     */
    public function indexAction(Request $request)
    {

    }
}
