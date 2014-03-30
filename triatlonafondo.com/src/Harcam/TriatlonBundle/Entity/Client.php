<?php

namespace Harcam\TriatlonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Harcam\TriatlonBundle\Entity\PayPalLog;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="Clients")
 * @UniqueEntity("email")
 */
class Client {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", unique=TRUE)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $clientId;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $category;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $team;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phoneNumber;

    /**
     * @ORM\Column(type="string", nullable=false, unique=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $time100;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $creationTime;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $hasPayed = false;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $hasConfirmed = false;

    /**
     * N: New
     * C: Confirmed
     * R: Registered
     * E: Error
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    protected $status = 'N';


    #########################
    ## OBJECT RELATIONSHIP ##
    #########################

    /**
     * @ORM\OneToMany(targetEntity="PayPalLog", mappedBy="clientId")
     */
    protected $payPalLogs;

    public function __construct()
    {
        $this->payPalLogs = new ArrayCollection();
    }

    /**
     * Add payPalLogs
     *
     * @param PayPalLog $payPalLogs
     * @return Client
     */
    public function addPayPalLogs(PayPalLog $payPalLogs)
    {
        $this->payPalLogs[] = $payPalLogs;

        return $this;
    }

    /**
     * Remove payPalLogs
     *
     * @param PayPalLog $payPalLogs
     */
    public function removePayPalLogs(PayPalLog $payPalLogs)
    {
        $this->payPalLogs->removeElement($payPalLogs);
    }

    /**
     * Get payPalLogs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPayPalLogs()
    {
        return $this->payPalLogs;
    }


    #########################
    ##   SPECIAL METHODS   ##
    #########################

    /**
     * @ORM\PrePersist
     */
    public function setCreationTimeValue()
    {
        $this->creationTime = new \Datetime("now");
    }

    public function getFullName()
    {
        return $this->name . " " . $this->lastName;
    }


    #########################
    ## GETTERs AND SETTERs ##
    #########################

    /**
     * @param mixed $category
     * @return Client
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $clientId
     * @return Client
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $creationTime
     * @return Client
     */
    public function setCreationTime($creationTime)
    {
        $this->creationTime = $creationTime;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }

    /**
     * @param mixed $email
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $lastName
     * @return Client
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $name
     * @return Client
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $phoneNumber
     * @return Client
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $team
     * @return Client
     */
    public function setTeam($team)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param mixed $hasPayed
     * @return Client
     */
    public function setHasPayed($hasPayed)
    {
        $this->hasPayed = $hasPayed;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHasPayed()
    {
        return $this->hasPayed;
    }

    /**
     * @param mixed $hasConfirmed
     * @return Client
     */
    public function setHasConfirmed($hasConfirmed)
    {
        $this->hasConfirmed = $hasConfirmed;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHasConfirmed()
    {
        return $this->hasConfirmed;
    }

    /**
     * @param mixed $status
     * @return Client
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }



}
