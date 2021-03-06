<?php
/**
 * Created by PhpStorm.
 * User: sergiu
 * Date: 3/14/14
 * Time: 3:03 PM
 */

namespace Pitech\Fedex\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Pitech\Fedex\MainBundle\Repository\BusRepository")
 * @ORM\Table(name="buses")
 * @ORM\HasLifecycleCallbacks()
 */
class Bus {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $busNr;

    /**
     * @ORM\OneToOne(targetEntity="Route", mappedBy="bus")
     */
    protected $route;

    /**
     * @ORM\Column(type="array")
     * @var array
     */
    protected $orePlecareSaptCapA;

    /**
     * @ORM\Column(type="array")
     * @var array
     */
    protected $orePlecareWeekCapA;

    /**
     * @ORM\Column(type="array")
     * @var array
     */
    protected $orePlecareSaptCapB;

    /**
     * @ORM\Column(type="array")
     * @var array
     */
    protected $orePlecareWeekCapB;

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
     * Set busNr
     *
     * @param string $busNr
     * @return Bus
     */
    public function setBusNr($busNr)
    {
        $this->busNr = $busNr;

        return $this;
    }

    /**
     * Get busNr
     *
     * @return string
     */
    public function getBusNr()
    {
        return $this->busNr;
    }

    /**
     * Set orePlecareSaptCapA
     *
     * @param array $orePlecareSaptCapA
     * @return Bus
     */
    public function setOrePlecareSaptCapA(array $orePlecareSaptCapA)
    {
        $this->orePlecareSaptCapA = $orePlecareSaptCapA;

        return $this;
    }

    /**
     * Get orePlecareSaptCapA
     *
     * @return string
     */
    public function getOrePlecareSaptCapA()
    {
        return $this->orePlecareSaptCapA;
    }

    /**
     * Set orePlecareWeekCapA
     *
     * @param array $orePlecareWeekCapA
     * @return Bus
     */
    public function setOrePlecareWeekCapA(array $orePlecareWeekCapA)
    {
        $this->orePlecareWeekCapA = $orePlecareWeekCapA;

        return $this;
    }

    /**
     * Get orePlecareWeekCapA
     *
     * @return string
     */
    public function getOrePlecareWeekCapA()
    {
        return $this->orePlecareWeekCapA;
    }

    /**
     * Set orePlecareSaptCapB
     *
     * @param array $orePlecareSaptCapB
     * @return Bus
     */
    public function setOrePlecareSaptCapB(array $orePlecareSaptCapB)
    {
        $this->orePlecareSaptCapB = $orePlecareSaptCapB;

        return $this;
    }

    /**
     * Get orePlecareSaptCapB
     *
     * @return string
     */
    public function getOrePlecareSaptCapB()
    {
        return $this->orePlecareSaptCapB;
    }

    /**
     * Set orePlecareWeekCapB
     *
     * @param array $orePlecareWeekCapB
     * @return Bus
     */
    public function setOrePlecareWeekCapB(array $orePlecareWeekCapB)
    {
        $this->orePlecareWeekCapB = $orePlecareWeekCapB;

        return $this;
    }

    /**
     * Get orePlecareWeekCapB
     *
     * @return string
     */
    public function getOrePlecareWeekCapB()
    {
        return $this->orePlecareWeekCapB;
    }

    /**
     * Set route
     *
     * @param \Pitech\Fedex\MainBundle\Entity\Route $route
     * @return Bus
     */
    public function setRoute(\Pitech\Fedex\MainBundle\Entity\Route $route = null)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return \Pitech\Fedex\MainBundle\Entity\Route
     */
    public function getRoute()
    {
        return $this->route;
    }
}
