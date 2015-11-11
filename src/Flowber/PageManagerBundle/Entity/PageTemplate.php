<?php

namespace Flowber\PageManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PageTemplate
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Flowber\PageManagerBundle\Entity\PageTemplateRepository")
 */
class PageTemplate
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
     * @var integer
     *
     * @ORM\Column(name="numberOfWidgets", type="integer")
     */
    private $numberOfWidgets;


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
     * @return PageTemplate
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
     * @return PageTemplate
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

    /**
     * Set numberOfWidgets
     *
     * @param integer $numberOfWidgets
     * @return PageTemplate
     */
    public function setNumberOfWidgets($numberOfWidgets)
    {
        $this->numberOfWidgets = $numberOfWidgets;

        return $this;
    }

    /**
     * Get numberOfWidgets
     *
     * @return integer 
     */
    public function getNumberOfWidgets()
    {
        return $this->numberOfWidgets;
    }
}
