<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Contact
 */
class Contact
{
    /**
     * @var string
     */
    private $date;

    /**
     * @var string
     */
    private $closetag;

    /**
     * @var string
     */
    private $demotag;

    /**
     * @var string
     */
    private $demoCount;

    /**
     * @var string
     */
    private $closeCount;

    /**
     * @var collection
     */
    private $activities;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param string $date
     *
     * @return Contact
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set tag
     *
     * @param string $tag
     *
     * @return Contact
     */
    public function setCloseTag($closetag)
    {
        $this->closetag = $closetag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getCloseTag()
    {
        return $this->closetag;
    }

    /**
     * Set tag
     *
     * @param string $tag
     *
     * @return Contact
     */
    public function setDemoTag($demotag)
    {
        $this->demotag = $demotag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getDemoTag()
    {
        return $this->demotag;
    }

    /**
     * Get demoCount
     *
     * @return int $count
     */
    public function getDemoCount()
    {
        return $this->demoCount;
    }

    /**
     * Set tag
     *
     * @param int $count
     *
     * @return Contact
     */
    public function setDemoCount($demoCount)
    {
        $this->demoCount = $demoCount;

        return $this;
    }

    /**
     * Set tag
     *
     * @param int $count
     *
     * @return Contact
     */
    public function setCloseCount($closeCount)
    {
        $this->closeCount = $closeCount;

        return $this;
    }

    /**
     * Get closeCount
     *
     * @return int $closeCount
     */
    public function getCloseCount()
    {
        return $this->closeCount;
    }

    public function addActivity($activity): ?self
    {
        $this->activities[] = $activity;

        return $this;
    }

    public function getActivities(): ?Collection
    {
        return $this->activities;
    }

    public function __toString() 
    {
        $demoTag = null;
        $closeTag = null;

        if ($this->getDemoCount() > 0) {
            $demoTag = $this->getDemoTag() . ' ' . $this->getDemoCount();
        }

        if ($this->getCloseCount() > 0) {
            $closeTag = $this->getCloseTag() . ' ' . $this->getCloseCount();
        }

        $filteredArray = array_filter([$demoTag, $closeTag], function($var) {
            return !is_null($var);
        });

        return $this->getDate() . ': ' . implode(', ',  $filteredArray)  . "\n";

        ;
    }
}
