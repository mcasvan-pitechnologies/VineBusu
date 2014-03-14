<?php

namespace Pitech\Fedex\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;

class MapController extends Controller
{
    public function indexAction()
    {
        return $this->render('PitechFedexMainBundle:Map:index.html.twig', array());
    }

    public function getTimesAction($name)
    {
        $handle = @fopen($name.'.txt', "r");
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
            foreach($rows as $value) {
                if (trim($value)) {
                    $times[] = trim($value);
                }
            }
        }
        var_dump($times); die;

    public function getKMLfileAction()
    {
        $finder = new Finder();
        var_dump($finder->in(__DIR__));exit();
    }
}
