<?php

namespace Spa\SpaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Images
 *
 * @ORM\Table(name="images")
 * @ORM\Entity
 */
class Images {

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="idImages", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idimages;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Spa\SpaBundle\Entity\Pets", mappedBy="imagesimages")
     */
    private $petspets;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Spa\SpaBundle\Entity\Articles", mappedBy="imagesimages")
     */
    private $articlesarticles;

    /**
     * Constructor
     */
    public function __construct() {
        $this->petspets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->articlesarticles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Images
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Get idimages
     *
     * @return integer 
     */
    public function getIdimages() {
        return $this->idimages;
    }

    /**
     * Add petspets
     *
     * @param \Spa\SpaBundle\Entity\Pets $petspets
     * @return Images
     */
    public function addPetspet(\Spa\SpaBundle\Entity\Pets $petspets) {
        $this->petspets[] = $petspets;

        return $this;
    }

    /**
     * Remove petspets
     *
     * @param \Spa\SpaBundle\Entity\Pets $petspets
     */
    public function removePetspet(\Spa\SpaBundle\Entity\Pets $petspets) {
        $this->petspets->removeElement($petspets);
    }

    /**
     * Get petspets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPetspets() {
        return $this->petspets;
    }

    /**
     * Add articlesarticles
     *
     * @param \Spa\SpaBundle\Entity\Articles $articlesarticles
     * @return Images
     */
    public function addArticlesarticle(\Spa\SpaBundle\Entity\Articles $articlesarticles) {
        $this->articlesarticles[] = $articlesarticles;

        return $this;
    }

    /**
     * Remove articlesarticles
     *
     * @param \Spa\SpaBundle\Entity\Articles $articlesarticles
     */
    public function removeArticlesarticle(\Spa\SpaBundle\Entity\Articles $articlesarticles) {
        $this->articlesarticles->removeElement($articlesarticles);
    }

    /**
     * Get articlesarticles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticlesarticles() {
        return $this->articlesarticles;
    }

    /*
     * Functions Upload d'images
     */

    public function getAbsoluteUrl() {
        return null === $this->url ? null : $this->getUploadRootDir() . '/' . $this->url;
    }

    public function getWebUrl() {
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->url;
    }

    protected function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/images';
    }
}
