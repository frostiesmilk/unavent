<?php

namespace Flowber\PageManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Flowber\PageManagerBundle\Entity\PageTemplate;
use Flowber\PageManagerBundle\Entity\Widget;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;


/**
 * Page
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Flowber\PageManagerBundle\Entity\PageRepository")
 */
class Page
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
     * @ORM\Column(name="routeName", type="string", length=255)
     */
    private $routeName;

    /**
     * @var string
     *
     * @ORM\Column(name="pageName", type="string", length=255)
     */
    private $pageName;
    
    /**
     * @OneToOne(targetEntity="PageTemplate")
     * @JoinColumn(name="page_id", referencedColumnName="id")
     **/
    private $pageTemplate;

    /**
     * @ManyToMany(targetEntity="Widget")
     * @JoinTable(name="page_widgets",
     *      joinColumns={@JoinColumn(name="page_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="widget_id", referencedColumnName="id", unique=false)}
     *      )
     **/
    private $widgets;

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
     * Set routeName
     *
     * @param string $routeName
     * @return Page
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;

        return $this;
    }

    /**
     * Get routeName
     *
     * @return string 
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * Set pageName
     *
     * @param string $pageName
     * @return Page
     */
    public function setPageName($pageName)
    {
        $this->pageName = $pageName;

        return $this;
    }

    /**
     * Get pageName
     *
     * @return string 
     */
    public function getPageName()
    {
        return $this->pageName;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->widgets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set pageTemplate
     *
     * @param \Flowber\PageManagerBundle\Entity\PageTemplate $pageTemplate
     * @return Page
     */
    public function setPageTemplate(\Flowber\PageManagerBundle\Entity\PageTemplate $pageTemplate = null)
    {
        $this->pageTemplate = $pageTemplate;

        return $this;
    }

    /**
     * Get pageTemplate
     *
     * @return \Flowber\PageManagerBundle\Entity\PageTemplate 
     */
    public function getPageTemplate()
    {
        return $this->pageTemplate;
    }

    /**
     * Add widgets
     *
     * @param \Flowber\PageManagerBundle\Entity\Widget $widgets
     * @return Page
     */
    public function addWidget(\Flowber\PageManagerBundle\Entity\Widget $widgets)
    {
        $this->widgets[] = $widgets;

        return $this;
    }

    /**
     * Remove widgets
     *
     * @param \Flowber\PageManagerBundle\Entity\Widget $widgets
     */
    public function removeWidget(\Flowber\PageManagerBundle\Entity\Widget $widgets)
    {
        $this->widgets->removeElement($widgets);
    }

    /**
     * Get widgets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWidgets()
    {
        return $this->widgets;
    }
}
