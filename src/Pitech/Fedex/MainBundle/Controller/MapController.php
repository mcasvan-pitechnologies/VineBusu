<?php

namespace Pitech\Fedex\MainBundle\Controller;

use Pitech\Fedex\MainBundle\Entity\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MapController extends Controller
{
    public function indexAction()
    {
        return $this->render('PitechFedexMainBundle:Map:index.html.twig', array());
    }

    public function getTimesAction($name)
    {
        $handle = @fopen($name . '.txt', "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $array[] = explode(' 	', $buffer);
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }
        foreach ($array as $rows) {
            foreach ($rows as $value) {
                if (trim($value)) {
                    $times[] = trim($value);
                }
            }
        }
        var_dump($times);
        die;
    }

    public function getKMLfileAction()
    {
        $kernel = $this->container->get('kernel');
        $path = $kernel->locateResource('@AdmeDemoBundle/path/to/file/Foo.txt');
    }

    public function parseGPXAction($filename)
    {
        $kernel = $this->container->get('kernel');
        $path = $kernel->locateResource('@PitechFedexMainBundle/Resources/routes/mures/targumures/' . $filename);
        if (!$xml = simplexml_load_file($path)) {
            echo 'unable to load XML file';
        } else {
            $route = new Route();
            $route->setDescription($filename);

            $coords = array();
            foreach($xml->children()->rte->rtept as $key => $coord){
                /** @var $coord \SimpleXMLElement */
                $tmpCoord = array();

                foreach($coord->attributes() as $key2=>$val2){
                    /** @var $val2 \SimpleXMLElement */
                    $tmpCoord[$key2]=(string)$val2;
                }
                $coords[] = $tmpCoord;
            }
            $route->setOras('Targu Mures');
            $route->setJudet('Mures');
            $route->setCoords($coords);

            $em = $this->getDoctrine()->getManager();

//            $em

            $em->persist($route);
            $em->flush();

            return new Response('Route '.$filename . ' was saved.');
        }
    }

}
