<?php
/**
 * Created by PhpStorm.
 * User: sergiu
 * Date: 3/14/14
 * Time: 3:25 PM
 */

namespace Pitech\Fedex\MainBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="Pitech\Fedex\MainBundle\Repository\RouteRepository")
 * @ORM\Table(name="routes")
 * @ORM\HasLifecycleCallbacks()
 */
class Route {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Bus", inversedBy="route")
     * @ORM\JoinColumn(name="bus_id", referencedColumnName="id")
     */
    protected $bus;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $judet;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $oras;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="integer")
     */
    protected $duration;

    /**
     * @ORM\ManyToMany(targetEntity="Station", inversedBy="routes")
     * @ORM\JoinTable(name="route_station")
     */
    protected $stations;

    public function __construct() {
        $this->stations = new ArrayCollection();
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
     * Set judet
     *
     * @param string $judet
     * @return Route
     */
    public function setJudet($judet)
    {
        $this->judet = $judet;

        return $this;
    }

    /**
     * Get judet
     *
     * @return string 
     */
    public function getJudet()
    {
        return $this->judet;
    }

    /**
     * Set oras
     *
     * @param string $oras
     * @return Route
     */
    public function setOras($oras)
    {
        $this->oras = $oras;

        return $this;
    }

    /**
     * Get oras
     *
     * @return string 
     */
    public function getOras()
    {
        return $this->oras;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Route
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return Route
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set bus
     *
     * @param \Pitech\Fedex\MainBundle\Entity\Bus $bus
     * @return Route
     */
    public function setBus(\Pitech\Fedex\MainBundle\Entity\Bus $bus = null)
    {
        $this->bus = $bus;

        return $this;
    }

    /**
     * Get bus
     *
     * @return \Pitech\Fedex\MainBundle\Entity\Bus 
     */
    public function getBus()
    {
        return $this->bus;
    }

    /**
     * Add stations
     *
     * @param \Pitech\Fedex\MainBundle\Entity\Station $stations
     * @return Route
     */
    public function addStation(\Pitech\Fedex\MainBundle\Entity\Station $stations)
    {
        $this->stations[] = $stations;

        return $this;
    }

    /**
     * Remove stations
     *
     * @param \Pitech\Fedex\MainBundle\Entity\Station $stations
     */
    public function removeStation(\Pitech\Fedex\MainBundle\Entity\Station $stations)
    {
        $this->stations->removeElement($stations);
    }

    /**
     * Get stations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStations()
    {
        return $this->stations;
    }
}
