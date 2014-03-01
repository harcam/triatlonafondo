<?php

namespace NivaShs\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\Column(type="bigint", nullable=false)
     */
    protected $msisdn;

    /**
     * A: Active
     * I: Invalid, (proven not to be valid for the carrier)
     * P: Pin Pending
     * @ORM\Column(type="string", length=2, nullable=false)
     */
    protected $status = 'N';

    /**
     * Client's contract plan type: 1:Pre-paid, 2:Post-paid
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $planType;

    /**
     * 4-digit verification PIN for WebPin methods
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $pin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $pinCreationTime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $totalCharged;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $creationTime;

    /**
     * Trial Begin Time
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $trialTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $activationTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $suspensionTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $resurrectionTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $cancellationTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastChargeAttemptTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastChargeTime;


    #########################
    ## OBJECT RELATIONSHIP ##
    #########################

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="clients")
     * @ORM\JoinColumn(name="productId", referencedColumnName="productId", nullable=false)
     */
    protected $productId;

    /**
     * @ORM\OneToMany(targetEntity="ChargeLog", mappedBy="clientId")
     */
    protected $chargeLogs;

    /**
     * @ORM\OneToMany(targetEntity="SyncLog", mappedBy="clientId")
     */
    protected $syncLogs;

    /**
     * @ORM\OneToMany(targetEntity="CommLog", mappedBy="clientId")
     */
    protected $commLogs;

    /**
     * @ORM\OneToMany(targetEntity="CommErrorLog", mappedBy="aggregatorId")
     */
    protected $commErrorLogs;

    public function __construct()
    {
        $this->chargeLogs = new ArrayCollection();
        $this->syncLogs = new ArrayCollection();
        $this->commLogs = new ArrayCollection();
        $this->commErrorLogs = new ArrayCollection();
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

    /**
     * Check if client is subscribed
     */
    public function isSubscribed()
    {
        if($this->status == 'A' || $this->status == 'T' || $this->status == 'S'){
            return true;
        } else {
            return false;
        }
    }


    #########################
    ## GETTERs AND SETTERs ##
    #########################

    /**
     * Get clientId
     *
     * @return integer 
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set msisdn
     *
     * @param integer $msisdn
     * @return Client
     */
    public function setMsisdn($msisdn)
    {
        $this->msisdn = $msisdn;

        return $this;
    }

    /**
     * Get msisdn
     *
     * @return integer 
     */
    public function getMsisdn()
    {
        return $this->msisdn;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Client
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set planType
     *
     * @param integer $planType
     * @return Client
     */
    public function setPlanType($planType)
    {
        $this->planType = $planType;

        return $this;
    }

    /**
     * Get planType
     *
     * @return integer 
     */
    public function getPlanType()
    {
        return $this->planType;
    }

    /**
     * Set pin
     *
     * @param integer $pin
     * @return Client
     */
    public function setPin($pin)
    {
        $this->pin = $pin;

        return $this;
    }

    /**
     * Get pin
     *
     * @return integer 
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * Set pinCreationTime
     *
     * @param \DateTime $pinCreationTime
     * @return Client
     */
    public function setPinCreationTime($pinCreationTime)
    {
        $this->pinCreationTime = $pinCreationTime;

        return $this;
    }

    /**
     * Get pinCreationTime
     *
     * @return \DateTime 
     */
    public function getPinCreationTime()
    {
        return $this->pinCreationTime;
    }

    /**
     * Set totalCharged
     *
     * @param integer $totalCharged
     * @return Client
     */
    public function setTotalCharged($totalCharged)
    {
        $this->totalCharged = $totalCharged;

        return $this;
    }

    /**
     * Get totalCharged
     *
     * @return integer 
     */
    public function getTotalCharged()
    {
        return $this->totalCharged;
    }

    /**
     * Set creationTime
     *
     * @param \DateTime $creationTime
     * @return Client
     */
    public function setCreationTime($creationTime)
    {
        $this->creationTime = $creationTime;

        return $this;
    }

    /**
     * Get creationTime
     *
     * @return \DateTime 
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }

    /**
     * Set trialTime
     *
     * @param \DateTime $trialTime
     * @return Client
     */
    public function setTrialTime($trialTime)
    {
        $this->trialTime = $trialTime;

        return $this;
    }

    /**
     * Get trialTime
     *
     * @return \DateTime
     */
    public function getTrialTime()
    {
        return $this->trialTime;
    }

    /**
     * Set activationTime
     *
     * @param \DateTime $activationTime
     * @return Client
     */
    public function setActivationTime($activationTime)
    {
        $this->activationTime = $activationTime;

        return $this;
    }

    /**
     * Get activationTime
     *
     * @return \DateTime 
     */
    public function getActivationTime()
    {
        return $this->activationTime;
    }

    /**
     * Set suspensionTime
     *
     * @param \DateTime $suspensionTime
     * @return Client
     */
    public function setSuspensionTime($suspensionTime)
    {
        $this->suspensionTime = $suspensionTime;

        return $this;
    }

    /**
     * Get suspensionTime
     *
     * @return \DateTime 
     */
    public function getSuspensionTime()
    {
        return $this->suspensionTime;
    }

    /**
     * Set resurrectionTime
     *
     * @param \DateTime $resurrectionTime
     * @return Client
     */
    public function setResurrectionTime($resurrectionTime)
    {
        $this->resurrectionTime = $resurrectionTime;

        return $this;
    }

    /**
     * Get resurrectionTime
     *
     * @return \DateTime 
     */
    public function getResurrectionTime()
    {
        return $this->resurrectionTime;
    }

    /**
     * Set cancellationTime
     *
     * @param \DateTime $cancellationTime
     * @return Client
     */
    public function setCancellationTime($cancellationTime)
    {
        $this->cancellationTime = $cancellationTime;

        return $this;
    }

    /**
     * Get cancellationTime
     *
     * @return \DateTime 
     */
    public function getCancellationTime()
    {
        return $this->cancellationTime;
    }

    /**
     * Set lastChargeAttemptTime
     *
     * @param \DateTime $lastChargeAttemptTime
     * @return Client
     */
    public function setLastChargeAttemptTime($lastChargeAttemptTime)
    {
        $this->lastChargeAttemptTime = $lastChargeAttemptTime;

        return $this;
    }

    /**
     * Get lastChargeAttemptTime
     *
     * @return \DateTime 
     */
    public function getLastChargeAttemptTime()
    {
        return $this->lastChargeAttemptTime;
    }

    /**
     * Set lastChargeTime
     *
     * @param \DateTime $lastChargeTime
     * @return Client
     */
    public function setLastChargeTime($lastChargeTime)
    {
        $this->lastChargeTime = $lastChargeTime;

        return $this;
    }

    /**
     * Get lastChargeTime
     *
     * @return \DateTime 
     */
    public function getLastChargeTime()
    {
        return $this->lastChargeTime;
    }

    /**
     * Set productId
     *
     * @param \NivaShs\ModelBundle\Entity\Product $productId
     * @return Client
     */
    public function setProductId(\NivaShs\ModelBundle\Entity\Product $productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return \NivaShs\ModelBundle\Entity\Product 
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Add chargeLogs
     *
     * @param \NivaShs\ModelBundle\Entity\ChargeLog $chargeLogs
     * @return Client
     */
    public function addChargeLog(\NivaShs\ModelBundle\Entity\ChargeLog $chargeLogs)
    {
        $this->chargeLogs[] = $chargeLogs;

        return $this;
    }

    /**
     * Remove chargeLogs
     *
     * @param \NivaShs\ModelBundle\Entity\ChargeLog $chargeLogs
     */
    public function removeChargeLog(\NivaShs\ModelBundle\Entity\ChargeLog $chargeLogs)
    {
        $this->chargeLogs->removeElement($chargeLogs);
    }

    /**
     * Get chargeLogs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChargeLogs()
    {
        return $this->chargeLogs;
    }

    /**
     * Add syncLogs
     *
     * @param \NivaShs\ModelBundle\Entity\SyncLog $syncLogs
     * @return Client
     */
    public function addSyncLog(\NivaShs\ModelBundle\Entity\SyncLog $syncLogs)
    {
        $this->syncLogs[] = $syncLogs;

        return $this;
    }

    /**
     * Remove syncLogs
     *
     * @param \NivaShs\ModelBundle\Entity\SyncLog $syncLogs
     */
    public function removeSyncLog(\NivaShs\ModelBundle\Entity\SyncLog $syncLogs)
    {
        $this->syncLogs->removeElement($syncLogs);
    }

    /**
     * Get syncLogs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSyncLogs()
    {
        return $this->syncLogs;
    }

    /**
     * Add commLogs
     *
     * @param \NivaShs\ModelBundle\Entity\CommLog $commLogs
     * @return Client
     */
    public function addCommLog(\NivaShs\ModelBundle\Entity\CommLog $commLogs)
    {
        $this->commLogs[] = $commLogs;

        return $this;
    }

    /**
     * Remove commLogs
     *
     * @param \NivaShs\ModelBundle\Entity\CommLog $commLogs
     */
    public function removeCommLog(\NivaShs\ModelBundle\Entity\CommLog $commLogs)
    {
        $this->commLogs->removeElement($commLogs);
    }

    /**
     * Get commLogs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommLogs()
    {
        return $this->commLogs;
    }

    /**
     * Add commErrorLogs
     *
     * @param \NivaShs\ModelBundle\Entity\CommErrorLog $commErrorLogs
     * @return Client
     */
    public function addCommErrorLog(\NivaShs\ModelBundle\Entity\CommErrorLog $commErrorLogs)
    {
        $this->commErrorLogs[] = $commErrorLogs;

        return $this;
    }

    /**
     * Remove commErrorLogs
     *
     * @param \NivaShs\ModelBundle\Entity\CommErrorLog $commErrorLogs
     */
    public function removeCommErrorLog(\NivaShs\ModelBundle\Entity\CommErrorLog $commErrorLogs)
    {
        $this->commErrorLogs->removeElement($commErrorLogs);
    }

    /**
     * Get commErrorLogs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommErrorLogs()
    {
        return $this->commErrorLogs;
    }

}
