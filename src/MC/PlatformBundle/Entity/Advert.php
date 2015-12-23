<?php

namespace MC\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Mapping\Annotations as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="MC\PlatformBundle\Entity\AdvertRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Advert
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
    * @ORM\Column(name="published", type="boolean")
    */
    private $published = true;

    /**
    * @ORM\OneToMany(targetEntity="MC\PlatformBundle\Entity\Application", mappedBy="advert")
    */
    private $applications;
    

    public function __construct()
    {
        $this->date         = new \Datetime();
        $this->applications = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param \DateTime $date
     * @return Advert
     */
    public function setDate($date) {
        $this->date = $date;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param string $title
     * @return Advert
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $author
     * @return Advert
     */
    public function setAuthor($author) {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor() {
        return $this->author;
    }

    /**
     * @param string $content
     * @return Advert
     */
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param boolean $published
     * @return Advert
     */
    public function setPublished($published) {
        $this->published = $published;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getPublished() {
        return $this->published;
    }
}
