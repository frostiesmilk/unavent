<?php

namespace Flowber\PageManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Widget
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Flowber\PageManagerBundle\Entity\WidgetRepository")
 */
class Widget
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="viewPath", type="string", length=255)
     */
    private $viewPath;


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
     * Set name
     *
     * @param string $name
     * @return Widget
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set viewPath
     *
     * @param string $viewPath
     * @return Widget
     */
    public function setViewPath($viewPath)
    {
        $this->viewPath = $viewPath;

        return $this;
    }

    /**
     * Get viewPath
     *
     * @return string 
     */
    public function getViewPath()
    {
        return $this->viewPath;
    }
}
