<?php
/**
 * Created by PhpStorm.
 * User: sergiu
 * Date: 3/14/14
 * Time: 3:35 PM
 */

namespace Pitech\Fedex\MainBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="Pitech\Fedex\MainBundle\Repository\StationRepository")
 * @ORM\Table(name="stations")
 * @ORM\HasLifecycleCallbacks()
 */
class Station {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=30)
     */
    protected $latitude;

    /**
     * @ORM\Column(type="string", length=30)
     */
    protected $longitude;

    /**
     * @ORM\ManyToMany(targetEntity="Route", mappedBy="stations")
     */
    protected  $routes;

    public function __construct() {
        $this->routes = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Station
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Station
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Station
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Add routes
     *
     * @param \Pitech\Fedex\MainBundle\Entity\Route $routes
     * @return Station
     */
    public function addRoute(\Pitech\Fedex\MainBundle\Entity\Route $routes)
    {
        $this->routes[] = $routes;

        return $this;
    }

    /**
     * Remove routes
     *
     * @param \Pitech\Fedex\MainBundle\Entity\Route $routes
     */
    public function removeRoute(\Pitech\Fedex\MainBundle\Entity\Route $routes)
    {
        $this->routes->removeElement($routes);
    }

    /**
     * Get routes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}
