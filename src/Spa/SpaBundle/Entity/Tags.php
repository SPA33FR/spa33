<?php

namespace Spa\SpaBundle\Entity;

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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
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
     * @ORM\ManyToMany(targetEntity="Spa\SpaBundle\Entity\Articles", mappedBy="tagstags")
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
     * @param \Spa\SpaBundle\Entity\Articles $articlesarticles
     * @return Tags
     */
    public function addArticlesarticle(\Spa\SpaBundle\Entity\Articles $articlesarticles)
    {
        $this->articlesarticles[] = $articlesarticles;
    
        return $this;
    }

    /**
     * Remove articlesarticles
     *
     * @param \Spa\SpaBundle\Entity\Articles $articlesarticles
     */
    public function removeArticlesarticle(\Spa\SpaBundle\Entity\Articles $articlesarticles)
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
    
    /**
     * Get All Tags FROM Database
     * 
     * @param DoctrineManager $em
     * @return {Collection} 
     */
    public function getAllTags($em) {
        return $em->getRepository("\Spa\SpaBundle\Entity\Tags")->findAll();
    }
    /**
     * Create One Tags IN Database
     * 
     * @param DoctrineManager $em 
     * @param Tags $tag
     * 
     * @return void
     */
    public function saveOne($em, $tag) {
        $em->persist($tag);
        $em->flush();
    }
    /**
     * Delete One Tags IN Database
     * 
     * @param DoctrineManager $em 
     * @param Tags $tag
     * 
     * @return void
     */
    public function deleteOne($em, $tag) {
        $em->remove($tag);
        $em->flush();
    }
}
