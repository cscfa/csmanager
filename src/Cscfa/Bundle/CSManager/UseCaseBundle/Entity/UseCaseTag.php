<?php
/**
 * This file is a part of CSCFA UseCase project.
 * 
 * The UseCase bundle is part of csmanager project. It's a project manager
 * written in php with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Entity
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\CSManager\UseCaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UseCaseTag class.
 *
 * The base UseCaseTag entity for the
 * Cscfa project manager
 *
 * @category Entity
 * @package  CscfaCSManagerUseCaseBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 *
 * @ORM\Entity(repositoryClass="Cscfa\Bundle\CSManager\UseCaseBundle\Entity\Repository\UseCaseTagRepository")
 * @ORM\Table(name="csmanager_usecase_UseCaseTag",
 *      indexes={@ORM\Index(name="csmanager_usecase_UseCaseTag_indx", columns={"csmanager_UseCaseTag_name"})}
 *      )
 */
class UseCaseTag {

    /**
     * Id
     * 
     * The UseCase id
     * 
     * @ORM\Column(type="guid", nullable=false, name="csmanager_UseCaseTag_id", options={"comment":"UseCaseTag id"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string
     */
    protected $id;

    /**
     * Name
     *
     * The UseCase name
     *
     * @ORM\Column(type="string", length=255, unique=true, nullable=false, name="csmanager_UseCaseTag_name", options={"comment":"UseCaseTag name"})
     * @var string
     */
    protected $name;
    
    /**
     * Description
     * 
     * The UseCase description
     * 
     * @ORM\Column(type="text", nullable=true, name="csmanager_UseCaseTag_description", options={"comment":"UseCaseTag description"})
     * @var string
     */
    protected $description;
    
    /**
     * Get id
     * 
     * This method return the
     * current entity id.
     * 
     * @return string
     */
    public function getId(){
        return $this->id;
    }
    
    /**
     * Set id
     * 
     * This method allow to set
     * the current entity id.
     * 
     * @param string $id The entity id
     */
    private function setId($id){
        $this->id = $id;
    }

    /**
     * Set name
     *
     * This method allow to set
     * the tag name.
     *
     * @param string $name
     * 
     * @return UseCaseTag
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * This method return the
     * tag name.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set description
     * 
     * This method allow to set 
     * the tag description.
     * 
     * @param string $description
     * 
     * @return UseCaseTag
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
    
    /**
     * Get description
     *
     * This method return the
     * tag description.
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }
}
