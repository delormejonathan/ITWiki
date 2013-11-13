<?php
// src/Wiki/UserBundle/Entity/User.php
 
namespace Wiki\UserBundle\Entity;
 
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Wiki\UserBundle\Entity\UserRepository")
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
	/**
		* @ORM\Id
		* @ORM\Column(type="integer")
		* @ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;
	
	/**
		* @ORM\Column(name="firstname", type="string", length=255)
	 * @Assert\Length(
	 *      min = "2",
	 *      max = "50",
	 *      minMessage = "Votre nom doit être composé d'au moins {{ limit }} caractères"
	 * )
	*/
	protected $firstname;
	
	/**
		* @ORM\Column(name="lastname", type="string", length=255)
	 * @Assert\Length(
	 *      min = "2",
	 *      max = "50",
	 *      minMessage = "Votre prénom doit être composé d'au moins {{ limit }} caractères"
	 * )
	*/
	protected $lastname;

	private $fullname;
	
    /**
     * Constructor
     */
    public function __construct()
    {
    	parent::__construct();
        $this->discussions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
    }

	public function getFullname()
	{
		return $this->lastname . ' ' . $this->firstname;
	}

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
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
     * @return User
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
}