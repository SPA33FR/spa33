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
     * @ORM\Column(name="login", type="string", length=45, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=45, nullable=false)
     */
    private $password;

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
     * @ORM\GeneratedValue(strategy="NONE")
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
     * Set login
     *
     * @param string $login
     * @return Staff
     */
    public function setLogin($login)
    {
        $this->login = $login;
    
        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

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
}
