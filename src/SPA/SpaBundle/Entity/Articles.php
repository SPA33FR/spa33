<?php

namespace SPA\SpaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Articles
 *
 * @ORM\Table(name="articles")
 * @ORM\Entity
 */
class Articles {

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=255, nullable=true)
     */
    private $subtitle;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255, nullable=true)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publishdate", type="date", nullable=true)
     */
    private $publishdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modifdate", type="date", nullable=true)
     */
    private $modifdate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="investigation", type="boolean", nullable=true)
     */
    private $investigation;

    /**
     * @var integer
     *
     * @ORM\Column(name="idarticles", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idarticles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="SPA\SpaBundle\Entity\Tags", inversedBy="articlesarticles")
     * @ORM\JoinTable(name="articles_has_tags",
     *   joinColumns={
     *     @ORM\JoinColumn(name="articles_idarticles", referencedColumnName="idarticles")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="Tags_idTags", referencedColumnName="idTags")
     *   }
     * )
     */
    private $tagstags;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="SPA\SpaBundle\Entity\Images", inversedBy="articlesarticles")
     * @ORM\JoinTable(name="articles_has_images",
     *   joinColumns={
     *     @ORM\JoinColumn(name="articles_idarticles", referencedColumnName="idarticles")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="Images_idImages", referencedColumnName="idImages")
     *   }
     * )
     */
    private $imagesimages;

    /**
     * Constructor
     */
    public function __construct() {
        $this->tagstags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->imagesimages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Articles
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     * @return Articles
     */
    public function setSubtitle($subtitle) {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string 
     */
    public function getSubtitle() {
        return $this->subtitle;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Articles
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set publishdate
     *
     * @param \DateTime $publishdate
     * @return Articles
     */
    public function setPublishdate($publishdate) {
        $this->publishdate = $publishdate;

        return $this;
    }

    /**
     * Get publishdate
     *
     * @return \DateTime 
     */
    public function getPublishdate() {
        return $this->publishdate;
    }

    /**
     * Set modifdate
     *
     * @param \DateTime $modifdate
     * @return Articles
     */
    public function setModifdate($modifdate) {
        $this->modifdate = $modifdate;

        return $this;
    }

    /**
     * Get modifdate
     *
     * @return \DateTime 
     */
    public function getModifdate() {
        return $this->modifdate;
    }

    /**
     * Set investigation
     *
     * @param boolean $investigation
     * @return Articles
     */
    public function setInvestigation($investigation) {
        $this->investigation = $investigation;

        return $this;
    }

    /**
     * Get investigation
     *
     * @return boolean 
     */
    public function getInvestigation() {
        return $this->investigation;
    }

    /**
     * Get idarticles
     *
     * @return integer 
     */
    public function getIdarticles() {
        return $this->idarticles;
    }

    /**
     * Add tagstags
     *
     * @param \SPA\SpaBundle\Entity\Tags $tagstags
     * @return Articles
     */
    public function addTagstag(\SPA\SpaBundle\Entity\Tags $tagstags) {
        $this->tagstags[] = $tagstags;

        return $this;
    }

    /**
     * Remove tagstags
     *
     * @param \SPA\SpaBundle\Entity\Tags $tagstags
     */
    public function removeTagstag(\SPA\SpaBundle\Entity\Tags $tagstags) {
        $this->tagstags->removeElement($tagstags);
    }

    /**
     * Get tagstags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTagstags() {
        return $this->tagstags;
    }

    /**
     * Add imagesimages
     *
     * @param \SPA\SpaBundle\Entity\Images $imagesimages
     * @return Articles
     */
    public function addImagesimage(\SPA\SpaBundle\Entity\Images $imagesimages) {
        $this->imagesimages[] = $imagesimages;

        return $this;
    }

    /**
     * Remove imagesimages
     *
     * @param \SPA\SpaBundle\Entity\Images $imagesimages
     */
    public function removeImagesimage(\SPA\SpaBundle\Entity\Images $imagesimages) {
        $this->imagesimages->removeElement($imagesimages);
    }

    /**
     * Get imagesimages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImagesimages() {
        return $this->imagesimages;
    }

    /**
     * Get All Articles FROM Database
     * 
     * @param DoctrineManager $em 
     * @return {Collection}
     */
    public function getAllArticles($em) {
        return $em->getRepository("\SPA\SpaBundle\Entity\Articles")->findAll();
    }

    /**
     * Get One Article FROM Database By Id
     * 
     * @param DoctrineManager $em 
     * @param int $id 
     * 
     * @return Article
     */
    public function getOneById($em, $id) {
        return $em->getRepository("\SPA\SpaBundle\Entity\Articles")->find($id);
    }
    
    /**
     * Create One Article IN Database
     * 
     * @param DoctrineManager $em 
     * @param Articles $article
     * 
     * @return void
     */
    public function saveOne ($em, $article) {
        $em->persist($article);
        $em->flush();
    }
    
    /**
     * Delete One Article IN Database
     * 
     * @param DoctrineManager $em 
     * @param Articles $article
     * 
     * @return void
     */
    public function deleteOne ($em, $article) {
        $em->remove($article);
        $em->flush();
    }
}
