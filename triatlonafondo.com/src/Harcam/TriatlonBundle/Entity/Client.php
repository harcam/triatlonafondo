<?php

namespace Harcam\TriatlonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="Clients")
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
    protected $distance;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $affiliationNumber;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $team;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phoneNumber;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $email;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $creationTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $paymentTime;


    #########################
    ## OBJECT RELATIONSHIP ##
    #########################

    // None


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

    /**
     * Check if client has already payed
     */
    public function hasPayed()
    {
        if($this->paymentTime != null){
            return true;
        } else {
            return false;
        }
    }


    #########################
    ## GETTERs AND SETTERs ##
    #########################

    /**
     * @param mixed $affiliationNumber
     * @return Client
     */
    public function setAffiliationNumber($affiliationNumber)
    {
        $this->affiliationNumber = $affiliationNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAffiliationNumber()
    {
        return $this->affiliationNumber;
    }

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
     * @param mixed $distance
     * @return Client
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDistance()
    {
        return $this->distance;
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
     * @param mixed $paymentTime
     * @return Client
     */
    public function setPaymentTime($paymentTime)
    {
        $this->paymentTime = $paymentTime;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentTime()
    {
        return $this->paymentTime;
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

}
