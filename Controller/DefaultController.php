<?php

namespace Basilicom\MultiDomainBundle\Controller;

use Basilicom\MultiDomainBundle\Service\Domain;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends FrontendController
{

    private function log($msg)
    {

        $fh = fopen('/tmp/pimcore.log','a+');
        fputs($fh, $msg."\n");
        fclose($fh);
    }

    private function renderTable($data)
    {

        $out = "| Case        | Product        | Description      | Category  | Price |\n";
        $out .= "|-------------|----------------|------------------|-----------|------:|\n";

        $c = '';

        foreach ($data as $row) {

            if (count($row) == 1) {
                $c = implode($row);
            } else {
                $out .= "|$c|".implode('|', $row)."|\n";
            }
        }
        return $out;
    }

    /**
     * @Route("/basilicom_multi_domain")
     */
    public function indexAction(Request $request)
    {


        $out = '';

        $p1s = Product::getByPath('/Products/Shoe "Marathon"/Size S');
        $p1m = Product::getByPath('/Products/Shoe "Marathon"/Size M');


        $data = [];

        $data[] = ['NO DOMAINS SELECTED'];
        $data[] = ['Size S',$p1s->getName(), $p1s->getDescription(), $p1s->getCategory(), $p1s->getPrice()];
        $data[] = ['Size M',$p1m->getName(), $p1m->getDescription(), $p1m->getCategory(), $p1m->getPrice()];

        Domain::select(['Marketplace eBay']);
        $data[] = ['Marketplace eBay == eBay categories'];
        $data[] = ['Size S',$p1s->getName(), $p1s->getDescription(), $p1s->getCategory(), $p1s->getPrice()];
        $data[] = ['Size M',$p1m->getName(), $p1m->getDescription(), $p1m->getCategory(), $p1m->getPrice()];

        Domain::select(['Marketplace Amazon']);
        $data[] = ['Marketplace Amazon == Amazon categories'];
        $data[] = ['Size S',$p1s->getName(), $p1s->getDescription(), $p1s->getCategory(), $p1s->getPrice()];
        $data[] = ['Size M',$p1m->getName(), $p1m->getDescription(), $p1m->getCategory(), $p1m->getPrice()];

        Domain::select(['Country US']);
        $data[] = ['Country US, "soccer" instead of "football"'];
        $data[] = ['Size S',$p1s->getName(), $p1s->getDescription(), $p1s->getCategory(), $p1s->getPrice()];
        $data[] = ['Size M',$p1m->getName(), $p1m->getDescription(), $p1m->getCategory(), $p1m->getPrice()];

        Domain::select(['Country UK']);
        $data[] = ['Country UK, "trainers" instead of "sneakers"'];
        $data[] = ['Size S',$p1s->getName(), $p1s->getDescription(), $p1s->getCategory(), $p1s->getPrice()];
        $data[] = ['Size M',$p1m->getName(), $p1m->getDescription(), $p1m->getCategory(), $p1m->getPrice()];

        Domain::select(['Country US','Marketplace eBay']);
        $data[] = ['US + eBay'];
        $data[] = ['Size S',$p1s->getName(), $p1s->getDescription(), $p1s->getCategory(), $p1s->getPrice()];
        $data[] = ['Size M',$p1m->getName(), $p1m->getDescription(), $p1m->getCategory(), $p1m->getPrice()];

        Domain::select(['Marketplace eBay','Country US']);
        $data[] = ['eBay + US'];
        $data[] = ['Size S',$p1s->getName(), $p1s->getDescription(), $p1s->getCategory(), $p1s->getPrice()];
        $data[] = ['Size M',$p1m->getName(), $p1m->getDescription(), $p1m->getCategory(), $p1m->getPrice()];

        Domain::select(['Marketplace Amazon','Country US']);
        $data[] = ['Amazon + US, special child price for Size S and Amazon!'];
        $data[] = ['Size S',$p1s->getName(), $p1s->getDescription(), $p1s->getCategory(), $p1s->getPrice()];
        $data[] = ['Size M',$p1m->getName(), $p1m->getDescription(), $p1m->getCategory(), $p1m->getPrice()];

        Domain::select(['Marketplace Amazon','Marketplace eBay']);
        $data[] = ['Conflict: Amazon + Ebay, 1st wins!'];
        $data[] = ['Size S',$p1s->getName(), $p1s->getDescription(), $p1s->getCategory(), $p1s->getPrice()];
        $data[] = ['Size M',$p1m->getName(), $p1m->getDescription(), $p1m->getCategory(), $p1m->getPrice()];

        Domain::select(['Marketplace eBay','Marketplace Amazon']);
        $data[] = ['Conflict: Ebay + Amazon, 1st wins!'];
        $data[] = ['Size S',$p1s->getName(), $p1s->getDescription(), $p1s->getCategory(), $p1s->getPrice()];
        $data[] = ['Size M',$p1m->getName(), $p1m->getDescription(), $p1m->getCategory(), $p1m->getPrice()];

        /*
        ob_start();
        print_r($data);
        $out = ob_get_clean();
        */
        $out = $this->renderTable($data);

        return new Response('<pre>'.$out.'</pre>');
    }
}
