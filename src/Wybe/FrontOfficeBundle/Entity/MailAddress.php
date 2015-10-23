<?php

namespace Wybe\FrontOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MailAddress
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class MailAddress
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     */
    private $mail;

    /**
     * @var boolean
     *
     * @ORM\Column(name="main", type="boolean")
     */
    private $main;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateC", type="datetime")
     */
    private $dateC;

     /**
     * @var boolean
     *
     * @ORM\Column(name="newsletter", type="boolean")
     */
    private $newsletter;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="user", type="string", length=255)
     */
    private $user;

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
     * Set mail
     *
     * @param string $mail
     * @return MailAddress
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set main
     *
     * @param boolean $main
     * @return MailAddress
     */
    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }

    /**
     * Get main
     *
     * @return boolean 
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Set dateC
     *
     * @param \DateTime $dateC
     * @return MailAddress
     */
    public function setDateC($dateC)
    {
        $this->dateC = $dateC;

        return $this;
    }

    /**
     * Get dateC
     *
     * @return \DateTime 
     */
    public function getDateC()
    {
        return $this->dateC;
    }

    /**
     * Set newsletter
     *
     * @param boolean $newsletter
     * @return MailAddress
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * Get newsletter
     *
     * @return boolean 
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return MailAddress
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }
}
