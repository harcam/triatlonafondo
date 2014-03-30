<?php

namespace Harcam\TriatlonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Harcam\TriatlonBundle\Entity\Client;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="PayPalLogs")
 */
class PayPalLog
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", unique=TRUE)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $payPalLogId;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $token;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $creationTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $responseTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $finalTransactionTime;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $transactionResult;


    #########################
    ## OBJECT RELATIONSHIP ##
    #########################

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="payPalLogs")
     * @ORM\JoinColumn(name="clientId", referencedColumnName="clientId")
     */
    protected $clientId;

    /**
     * Set clientId
     *
     * @param Client $clientId
     * @return PayPalLog
     */
    public function setClientId(Client $clientId = null)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get clientId
     *
     * @return Client
     */
    public function getClientId()
    {
        return $this->clientId;
    }


    #########################
    ##   SPECIAL METHODS   ##
    #########################

    /**
     * @ORM\PrePersist
     */
    public function setTimestampValue()
    {
        $this->timestamp = new \Datetime("now");
    }

    #########################
    ## GETTERs AND SETTERs ##
    #########################

}