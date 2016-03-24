<?php

namespace Spa\SpaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Staff
 *
 * @ORM\Table(name="staff", indexes={@ORM\Index(name="fk_Staff_Images1_idx", columns={"Images_idImages"})})
 * @ORM\Entity
 */
class Staff
{

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=45, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=45, nullable=true)
     */
    private $lastname;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sex", type="boolean", nullable=true)
     */
    private $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=45, nullable=true)
     */
    private $role;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="boolean", nullable=true)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="idStaff", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idstaff;

    /**
     * @var \Spa\SpaBundle\Entity\Images
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Spa\SpaBundle\Entity\Images")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Images_idImages", referencedColumnName="idImages")
     * })
     */
    private $imagesimages;

    /**
     * @var object
     */
    public $file;
    
    /**
     * Set password
     *
     * @param string $password
     * @return Staff
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Staff
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Staff
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set sex
     *
     * @param boolean $sex
     * @return Staff
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
     * Set role
     *
     * @param string $role
     * @return Staff
     */
    public function setRole($role)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set type
     *
     * @param boolean $type
     * @return Staff
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return boolean 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set idstaff
     *
     * @param integer $idstaff
     * @return Staff
     */
    public function setIdstaff($idstaff)
    {
        $this->idstaff = $idstaff;
    
        return $this;
    }

    /**
     * Get idstaff
     *
     * @return integer 
     */
    public function getIdstaff()
    {
        return $this->idstaff;
    }

    /**
     * Set imagesimages
     *
     * @param \Spa\SpaBundle\Entity\Images $imagesimages
     * @return Staff
     */
    public function setImagesimages(\Spa\SpaBundle\Entity\Images $imagesimages)
    {
        $this->imagesimages = $imagesimages;
    
        return $this;
    }

    /**
     * Get imagesimages
     *
     * @return \Spa\SpaBundle\Entity\Images 
     */
    public function getImagesimages()
    {
        return $this->imagesimages;
    }
    
    /**
     * Get All Staff FROM Database
     * 
     * @param DoctrineManager $em
     * @return {Collection} 
     */
    public function getAllStaff($em) {
        return $em->getRepository("\Spa\SpaBundle\Entity\Staff")->findAll();
    }
    /**
     * Create One Staff IN Database
     * 
     * @param DoctrineManager $em 
     * @param Staff $staff
     * 
     * @return void
     */
    public function saveOne($em, $staff) {
        $em->persist($staff);
        $em->flush();
    }
    /**
     * Delete One Staff IN Database
     * 
     * @param DoctrineManager $em 
     * @param Staff $staff
     * 
     * @return void
     */
    public function deleteOne($em, $staff) {
        $em->remove($staff);
        $em->flush();
    }
    
    /*
     * Functions Upload Files
     */

    public function getWebPath() {
        return null === $this->pictureName ? null : $this->getUploadDir() . '/' . $this->pictureName;
    }

    public function getUploadRootDir() {
        // le chemin absolu du répertoire dans lequel sauvegarder les photos de profil
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    public function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/staff/pictures';
    }

    public function uploadPicture($em) {
        // Nous utilisons le nom de fichier original, donc il est dans la pratique 
        // nécessaire de le nettoyer pour éviter les problèmes de sécurité
        // move copie le fichier présent chez le client dans le répertoire indiqué.

            // On sauvegarde le nom de fichier
            $images = new Images();
            $fileName = str_replace(' ', '_', $this->file->getClientOriginalName());
            //Vérification de l'existence du fichier
            // S'il existe, on ajoute une string et on revérifie
            // 
            while (file_exists($this->getUploadRootDir() .'/'. $fileName)) {
                $match = '';
                if ($fileName == str_replace(' ', '_', $this->file->getClientOriginalName())) {
                    $fileName = preg_replace('/(.+)\./', "$1(1).", $fileName);
                } else {
                    preg_match("/\((\d+)\)\.\w+/", $fileName, $match);
                    $nextNumber = intval($match[1]) + 1;
                    $fileName = preg_replace("/((.+)\()\d+(\)\.\w+)/", '${1}' . $nextNumber . '$3', $fileName);
                }
            }
            $this->file->move($this->getUploadRootDir(), $fileName);
            $images->setUrl($fileName);
            $this->setImagesimages($images);
            $em->persist($images);
            $em->flush();
        // La propriété file ne servira plus
        $this->file = null;
    }
}
