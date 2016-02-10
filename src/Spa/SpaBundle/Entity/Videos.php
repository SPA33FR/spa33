<?php

namespace Spa\SpaBundle\Entity;

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
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
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
     * @ORM\ManyToMany(targetEntity="Spa\SpaBundle\Entity\Pets", mappedBy="videosvideos")
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
     * @param \Spa\SpaBundle\Entity\Pets $petspets
     * @return Videos
     */
    public function addPetspet(\Spa\SpaBundle\Entity\Pets $petspets)
    {
        $this->petspets[] = $petspets;
    
        return $this;
    }

    /**
     * Remove petspets
     *
     * @param \Spa\SpaBundle\Entity\Pets $petspets
     */
    public function removePetspet(\Spa\SpaBundle\Entity\Pets $petspets)
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
    
    /*
     * Functions Upload d'images
     */
    
    public function getAbsoluteUrl()
    {
        return null === $this->url
            ? null
            : $this->getUploadRootDir().'/'.$this->url;
    }

    public function getWebUrl()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->url;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/videos';
    }
}
