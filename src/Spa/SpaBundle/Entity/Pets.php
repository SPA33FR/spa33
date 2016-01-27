<?php

namespace Spa\SpaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pets
 *
 * @ORM\Table(name="pets", indexes={@ORM\Index(name="fk_Pets_Races1_idx", columns={"Races_idraces"})})
 * @ORM\Entity
 */
class Pets
{
    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=45, nullable=true)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="medal", type="string", length=45, nullable=true)
     */
    private $medal;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sex", type="boolean", nullable=true)
     */
    private $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="arrivaldate", type="date", nullable=true)
     */
    private $arrivaldate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="date", nullable=true)
     */
    private $birthdate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="petofmonth", type="boolean", nullable=true)
     */
    private $petofmonth;

    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=10, nullable=true)
     */
    private $size;

    /**
     * @var boolean
     *
     * @ORM\Column(name="veteran", type="boolean", nullable=true)
     */
    private $veteran;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="idPets", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idpets;

    /**
     * @var \Spa\SpaBundle\Entity\Races
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Spa\SpaBundle\Entity\Races")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Races_idraces", referencedColumnName="idraces")
     * })
     */
    private $racesraces;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Spa\SpaBundle\Entity\Videos", inversedBy="petspets")
     * @ORM\JoinTable(name="pets_has_videos",
     *   joinColumns={
     *     @ORM\JoinColumn(name="Pets_idPets", referencedColumnName="idPets")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="Videos_idVideos", referencedColumnName="idVideos")
     *   }
     * )
     */
    private $videosvideos;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Spa\SpaBundle\Entity\Images", inversedBy="petspets")
     * @ORM\JoinTable(name="pets_has_images",
     *   joinColumns={
     *     @ORM\JoinColumn(name="Pets_idPets", referencedColumnName="idPets")
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
    public function __construct()
    {
        $this->videosvideos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->imagesimages = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set reference
     *
     * @param string $reference
     * @return Pets
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    
        return $this;
    }

    /**
     * Get reference
     *
     * @return string 
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set medal
     *
     * @param string $medal
     * @return Pets
     */
    public function setMedal($medal)
    {
        $this->medal = $medal;
    
        return $this;
    }

    /**
     * Get medal
     *
     * @return string 
     */
    public function getMedal()
    {
        return $this->medal;
    }

    /**
     * Set sex
     *
     * @param boolean $sex
     * @return Pets
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    
        return $this;
    }

    /**
     * Get sex
     *
     * @return boolean 
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Pets
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
     * Set arrivaldate
     *
     * @param \DateTime $arrivaldate
     * @return Pets
     */
    public function setArrivaldate($arrivaldate)
    {
        $this->arrivaldate = $arrivaldate;
    
        return $this;
    }

    /**
     * Get arrivaldate
     *
     * @return \DateTime 
     */
    public function getArrivaldate()
    {
        return $this->arrivaldate;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return Pets
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    
        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime 
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set petofmonth
     *
     * @param boolean $petofmonth
     * @return Pets
     */
    public function setPetofmonth($petofmonth)
    {
        $this->petofmonth = $petofmonth;
    
        return $this;
    }

    /**
     * Get petofmonth
     *
     * @return boolean 
     */
    public function getPetofmonth()
    {
        return $this->petofmonth;
    }

    /**
     * Set size
     *
     * @param string $size
     * @return Pets
     */
    public function setSize($size)
    {
        $this->size = $size;
    
        return $this;
    }

    /**
     * Get size
     *
     * @return string 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set veteran
     *
     * @param boolean $veteran
     * @return Pets
     */
    public function setVeteran($veteran)
    {
        $this->veteran = $veteran;
    
        return $this;
    }

    /**
     * Get veteran
     *
     * @return boolean 
     */
    public function getVeteran()
    {
        return $this->veteran;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Pets
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set idpets
     *
     * @param integer $idpets
     * @return Pets
     */
    public function setIdpets($idpets)
    {
        $this->idpets = $idpets;
    
        return $this;
    }

    /**
     * Get idpets
     *
     * @return integer 
     */
    public function getIdpets()
    {
        return $this->idpets;
    }

    /**
     * Set racesraces
     *
     * @param \Spa\SpaBundle\Entity\Races $racesraces
     * @return Pets
     */
    public function setRacesraces(\Spa\SpaBundle\Entity\Races $racesraces)
    {
        $this->racesraces = $racesraces;
    
        return $this;
    }

    /**
     * Get racesraces
     *
     * @return \Spa\SpaBundle\Entity\Races 
     */
    public function getRacesraces()
    {
        return $this->racesraces;
    }

    /**
     * Add videosvideos
     *
     * @param \Spa\SpaBundle\Entity\Videos $videosvideos
     * @return Pets
     */
    public function addVideosvideo(\Spa\SpaBundle\Entity\Videos $videosvideos)
    {
        $this->videosvideos[] = $videosvideos;
    
        return $this;
    }

    /**
     * Remove videosvideos
     *
     * @param \Spa\SpaBundle\Entity\Videos $videosvideos
     */
    public function removeVideosvideo(\Spa\SpaBundle\Entity\Videos $videosvideos)
    {
        $this->videosvideos->removeElement($videosvideos);
    }

    /**
     * Get videosvideos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVideosvideos()
    {
        return $this->videosvideos;
    }

    /**
     * Add imagesimages
     *
     * @param \Spa\SpaBundle\Entity\Images $imagesimages
     * @return Pets
     */
    public function addImagesimage(\Spa\SpaBundle\Entity\Images $imagesimages)
    {
        $this->imagesimages[] = $imagesimages;
    
        return $this;
    }

    /**
     * Remove imagesimages
     *
     * @param \Spa\SpaBundle\Entity\Images $imagesimages
     */
    public function removeImagesimage(\Spa\SpaBundle\Entity\Images $imagesimages)
    {
        $this->imagesimages->removeElement($imagesimages);
    }

    /**
     * Get imagesimages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImagesimages()
    {
        return $this->imagesimages;
    }
    
    /**
     * Get All Pets FROM Database
     * 
     * @param DoctrineManager $em
     * @return {Collection} 
     */
    public function getAllPets($em) {
        return $em->getRepository("\Spa\SpaBundle\Entity\Pets")->findAll();
    }
    /**
     * Get One Pets FROM Database By Id
     * 
     * @param DoctrineManager $em 
     * @param int $id 
     * 
     * @return Pets
     */
    public function getOneById($em, $id) {
        return $em->getRepository("\Spa\SpaBundle\Entity\Pets")->find($id);
    }
    /**
     * Create One Pets IN Database
     * 
     * @param DoctrineManager $em 
     * @param Pets $pet
     * 
     * @return void
     */
    public function saveOne($em, $pet) {
        $em->persist($pet);
        $em->flush();
    }
    /**
     * Delete One Pets IN Database
     * 
     * @param DoctrineManager $em 
     * @param Pets $pet
     * 
     * @return void
     */
    public function deleteOne($em, $pet) {
        $em->remove($pet);
        $em->flush();
    }
}
