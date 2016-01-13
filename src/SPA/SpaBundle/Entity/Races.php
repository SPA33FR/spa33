<?php

namespace SPA\SpaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Races
 *
 * @ORM\Table(name="races")
 * @ORM\Entity
 */
class Races
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="idraces", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idraces;



    /**
     * Set name
     *
     * @param string $name
     * @return Races
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
     * Get idraces
     *
     * @return integer 
     */
    public function getIdraces()
    {
        return $this->idraces;
    }
}
