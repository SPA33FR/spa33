<?php

namespace Spa\SpaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Races
 *
 * @ORM\Table(name="races")
 * @ORM\Entity
 */
class Races {

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="idraces", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idraces;

    /**
     * Set name
     *
     * @param string $name
     * @return Races
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Get idraces
     *
     * @return integer 
     */
    public function getIdraces() {
        return $this->idraces;
    }

    /**
     * Get All Races FROM Database
     * 
     * @param DoctrineManager $em
     * @return {Collection} 
     */
    public function getAllRaces($em) {
        return $em->getRepository("\Spa\SpaBundle\Entity\Races")->findAll();
    }

    /**
     * Create One Races IN Database
     * 
     * @param DoctrineManager $em 
     * @param Races $race
     * 
     * @return void
     */
    public function saveOne($em, $race) {
        $em->persist($race);
        $em->flush();
    }

    /**
     * Delete One Races IN Database
     * 
     * @param DoctrineManager $em 
     * @param Races $race
     * 
     * @return void
     */
    public function deleteOne($em, $race) {
        $em->remove($race);
        $em->flush();
    }

}
