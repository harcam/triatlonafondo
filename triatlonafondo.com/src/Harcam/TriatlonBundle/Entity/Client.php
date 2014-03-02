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
     * @ORM\Column(type="string", nullable=true)
     */
    protected $token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $paymentTime;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $paymentAuth;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $paymentFolio;

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

    public function getFullName()
    {
        return $this->name . " " . $this->lastName;
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
     * @param mixed $paymentAuth
     * @return Client
     */
    public function setPaymentAuth($paymentAuth)
    {
        $this->paymentAuth = $paymentAuth;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentAuth()
    {
        return $this->paymentAuth;
    }

    /**
     * @param mixed $paymentFolio
     * @return Client
     */
    public function setPaymentFolio($paymentFolio)
    {
        $this->paymentFolio = $paymentFolio;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentFolio()
    {
        return $this->paymentFolio;
    }

    /**
     * @param mixed $token
     * @return Client
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
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
