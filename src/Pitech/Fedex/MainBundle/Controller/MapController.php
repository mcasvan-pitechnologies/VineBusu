<?php

namespace Pitech\Fedex\MainBundle\Controller;

use Doctrine\ORM\EntityManager;
use Pitech\Fedex\MainBundle\Entity\Bus;
use Pitech\Fedex\MainBundle\Entity\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

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
            foreach ($array as $rows) {
                foreach ($rows as $value) {
                    if (trim($value)) {
                        $times[] = trim($value);
                    }
                }
            }

            return $times;
        }
        throw new NotFoundResourceException('cannot read times file for [' . $name . ']');
    }

    public function getKMLfileAction()
    {
        $kernel = $this->container->get('kernel');
        $path = $kernel->locateResource('@AdmeDemoBundle/path/to/file/Foo.txt');
    }

    public function getRoutesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $routes = $em->getRepository('PitechFedexMainBundle:Route')->findAll();
        $routeCoords = array();
        foreach($routes as $route){
           $routeCoords[$route->getBus()->getBusNr()] = $route->getCoords();
        }

        $response = new JsonResponse($routeCoords,200);

        return $response;
    }

    public function parseGPXAction()
    {
        $routes = array(
            'linia4.gpx',
//          'linia16.gpx',
//          'linia17.gpx',
            'linia18.gpx',
            'linia19.gpx',
            'linia20.gpx',
            'linia23.gpx',
//          'linia26.gpx',
            'linia27.gpx',
            'linia43.gpx',
        );

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();


        $response = array();

        foreach ($routes as $xmlRoute) {
            $kernel = $this->container->get('kernel');
            $path = $kernel->locateResource('@PitechFedexMainBundle/Resources/routes/mures/targumures/' . $xmlRoute);
            if (!$xml = simplexml_load_file($path)) {
                echo 'unable to load XML file';
            } else {
                $busNr = explode('.', $xmlRoute)[0];
                $exists = $em->getRepository('PitechFedexMainBundle:Bus')->findOneBy(array('busNr' => $busNr));
                if ($exists == null) {
                    $route = new Route();
                    $route->setDescription($xmlRoute);

                    $coords = array();
                    foreach ($xml->children()->rte->rtept as $key => $coord) {
                        /** @var $coord \SimpleXMLElement */
                        $tmpCoord = array();

                        foreach ($coord->attributes() as $key2 => $val2) {
                            /** @var $val2 \SimpleXMLElement */
                            $tmpCoord[$key2] = (string)$val2;
                        }
                        $coords[] = $tmpCoord;
                    }
                    $route->setOras('Targu Mures');
                    $route->setJudet('Mures');
                    $route->setCoords($coords);


                    $bus = new Bus();
                    $bus->setBusNr($busNr);
                    $bus->setOrePlecareSaptCapA($this->getTimesAction($busNr . 'A'));
                    $bus->setOrePlecareSaptCapB($this->getTimesAction($busNr . 'B'));
                    $bus->setRoute($route);
                    $route->setBus($bus);

//            die(var_dump($route));

                    $em->persist($route);
                    $em->persist($bus);
                    $em->flush();

                    $response[] = 'Route ' . $xmlRoute . ' was saved.<br>';
                } else {
                    $response[] = 'Route ' . $xmlRoute . ' already exists.<br>';
                }
            }
        }
        return new Response(implode($response));
    }

}
