<?php

namespace SPA\SpaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Videos
 *
 * @ORM\Table(name="videos")
 * @ORM\Entity
 */
class Videos
{
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=45, nullable=true)
     */
    private $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="idVideos", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idvideos;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="SPA\SpaBundle\Entity\Pets", mappedBy="videosvideos")
     */
    private $petspets;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->petspets = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set url
     *
     * @param string $url
     * @return Videos
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get idvideos
     *
     * @return integer 
     */
    public function getIdvideos()
    {
        return $this->idvideos;
    }

    /**
     * Add petspets
     *
     * @param \SPA\SpaBundle\Entity\Pets $petspets
     * @return Videos
     */
    public function addPetspet(\SPA\SpaBundle\Entity\Pets $petspets)
    {
        $this->petspets[] = $petspets;

        return $this;
    }

    /**
     * Remove petspets
     *
     * @param \SPA\SpaBundle\Entity\Pets $petspets
     */
    public function removePetspet(\SPA\SpaBundle\Entity\Pets $petspets)
    {
        $this->petspets->removeElement($petspets);
    }

    /**
     * Get petspets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPetspets()
    {
        return $this->petspets;
    }
}
