<?php

namespace SPA\SpaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tags
 *
 * @ORM\Table(name="tags")
 * @ORM\Entity
 */
class Tags
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
     * @ORM\Column(name="idTags", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtags;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="SPA\SpaBundle\Entity\Articles", mappedBy="tagstags")
     */
    private $articlesarticles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articlesarticles = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set name
     *
     * @param string $name
     * @return Tags
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
     * Get idtags
     *
     * @return integer 
     */
    public function getIdtags()
    {
        return $this->idtags;
    }

    /**
     * Add articlesarticles
     *
     * @param \SPA\SpaBundle\Entity\Articles $articlesarticles
     * @return Tags
     */
    public function addArticlesarticle(\SPA\SpaBundle\Entity\Articles $articlesarticles)
    {
        $this->articlesarticles[] = $articlesarticles;

        return $this;
    }

    /**
     * Remove articlesarticles
     *
     * @param \SPA\SpaBundle\Entity\Articles $articlesarticles
     */
    public function removeArticlesarticle(\SPA\SpaBundle\Entity\Articles $articlesarticles)
    {
        $this->articlesarticles->removeElement($articlesarticles);
    }

    /**
     * Get articlesarticles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticlesarticles()
    {
        return $this->articlesarticles;
    }
}
