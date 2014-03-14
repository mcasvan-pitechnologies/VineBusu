<?php

namespace Pitech\Fedex\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MapController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PitechFedexMainBundle:Map:index.html.twig', array('name' => $name));
    }
}
