<?php

namespace Spa\SpaBundle\Entity;

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
     * @ORM\ManyToMany(targetEntity="Spa\SpaBundle\Entity\Tags", inversedBy="articlesarticles")
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
     * @ORM\ManyToMany(targetEntity="Spa\SpaBundle\Entity\Images", inversedBy="articlesarticles")
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
     * @var array
     */
    public $file;

    /**
     * Constructor
     */
    public function __construct() {
        $this->tagstags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->imagesimages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->file = array();
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
     * @param \Spa\SpaBundle\Entity\Tags $tagstags
     * @return Articles
     */
    public function addTagstag(\Spa\SpaBundle\Entity\Tags $tagstags) {
        $this->tagstags[] = $tagstags;

        return $this;
    }

    /**
     * Remove tagstags
     *
     * @param \Spa\SpaBundle\Entity\Tags $tagstags
     */
    public function removeTagstag(\Spa\SpaBundle\Entity\Tags $tagstags) {
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
     * @param \Spa\SpaBundle\Entity\Images $imagesimages
     * @return Articles
     */
    public function addImagesimage(\Spa\SpaBundle\Entity\Images $imagesimages = null) {
        if ($imagesimages != null)
            $this->imagesimages[] = $imagesimages;

        return $this;
    }

    /**
     * Remove imagesimages
     *
     * @param \Spa\SpaBundle\Entity\Images $imagesimages
     */
    public function removeImagesimage(\Spa\SpaBundle\Entity\Images $imagesimages) {
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
     * Get all articles from Database
     * Get All Articles FROM Database
     * 
     * @param EntityManager $em EntityManager from Controller
     * @param DoctrineManager $em 
     * @return {Collection}
     */
    public function getAllArticles($em) {
        $articles = $em->getRepository('SpaSpaBundle:Articles')->findAll();
        if (!$articles) {
            throw $this->createNotFoundException("Erreur lors de la récupération des articles");
        }
        return $articles;
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
        return $em->getRepository("\Spa\SpaBundle\Entity\Articles")->find($id);
    }

    /**
     * Create One Article IN Database
     * 
     * @param DoctrineManager $em 
     * @param Articles $article
     * 
     * @return void
     */
    public function saveOne($em, $article) {
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
    public function deleteOne($em, $article) {
        $em->remove($article);
        $em->flush();
    }

    /*
     * Functions Upload Files
     */

    public function getWebPath() {
        return null === $this->pictureName ? null : $this->getUploadDir() . '/' . $this->pictureName;
    }

    protected function getUploadRootDir() {
        // le chemin absolu du répertoire dans lequel sauvegarder les photos de profil
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/articles/pictures';
    }

    public function uploadPicture($em) {
        // Nous utilisons le nom de fichier original, donc il est dans la pratique 
        // nécessaire de le nettoyer pour éviter les problèmes de sécurité
        // move copie le fichier présent chez le client dans le répertoire indiqué.

        for ($i = 0; $i < count($this->file); $i++) {

            // On sauvegarde le nom de fichier
            $images = new Images();
            $fileName = $this->file[$i]->getClientOriginalName();
            //Vérification de l'existence du fichier
            // S'il existe, on ajoute une string et on revérifie
            // 
            while (file_exists($this->getUploadRootDir() .'/'. $fileName)) {
                $match = '';
                if ($fileName == $this->file[$i]->getClientOriginalName()) {
                    $fileName = preg_replace('/(.+)\./', "$1(1).", $fileName);
                } else {
                    preg_match("/\((\d+)\)\.\w+/", $fileName, $match);
                    $nextNumber = intval($match[1]) + 1;
                    $fileName = preg_replace("/((.+)\()\d+(\)\.\w+)/", '${1}' . $nextNumber . '$3', $fileName);
                }
            }
            $this->file[$i]->move($this->getUploadRootDir(), $fileName);
            $images->setUrl($fileName);
            $this->addImagesimage($images);
            $em->persist($images);
            $em->flush();
        }
        // La propriété file ne servira plus
        $this->file = [];
    }

}
