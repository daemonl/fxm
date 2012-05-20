<?php
namespace Rebase\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="User")
 * @ORM\Entity(repositoryClass="Rebase\UserBundle\Entity\UserRepository")
 */
class User implements UserInterface, \Serializable
{
      /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=200, nullable=true)
     */
    private $email;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=40, nullable=true)
     */
    private $password;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=40, nullable=true)
     */
    private $type;

    /**
     * @var string $token
     *
     * @ORM\Column(name="token", type="string", length=100, nullable=true)
     */
    private $token;

    /**
     * @var date $last
     *
     * @ORM\Column(name="last", type="date", nullable=true)
     */
    private $last;
    

    
    public function __construct()
    {
        $this->isActive = true;
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }
    
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function equals(UserInterface $user)
    {
        return $user->getEmail() === $this->email;
    }

    public function eraseCredentials()
    {
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getSalt()
    {
        return "";
    }

    public function getPassword()
    {
        return $this->password;
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
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = SHA1($password);
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set uType
     *
     * @param string $uType
     */
    public function setUType($uType)
    {
        $this->uType = $uType;
    }

    /**
     * Get uType
     *
     * @return string 
     */
    public function getUType()
    {
        return $this->uType;
    }

    /**
     * Set uToken
     *
     * @param string $uToken
     */
    public function setUToken($uToken)
    {
        $this->uToken = $uToken;
    }

    /**
     * Get uToken
     *
     * @return string 
     */
    public function getUToken()
    {
        return $this->uToken;
    }

    /**
     * Set uLast
     *
     * @param datetime $uLast
     */
    public function setULast($uLast)
    {
        $this->uLast = $uLast;
    }

    /**
     * Get uLast
     *
     * @return datetime 
     */
    public function getULast()
    {
        return $this->uLast;
    }

        
    public function serialize()
    {
       return serialize($this->id);
    }

    public function unserialize($data)
    {
      $this->id = unserialize($data);
    }

  
    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
     * Set token
     *
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set last
     *
     * @param date $last
     */
    public function setLast($last)
    {
        $this->last = $last;
    }

    /**
     * Get last
     *
     * @return date 
     */
    public function getLast()
    {
        return $this->last;
    }

}