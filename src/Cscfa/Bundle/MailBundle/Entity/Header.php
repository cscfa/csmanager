<?php
/**
 * This file is a part of CSCFA mail project.
 * 
 * The mail project is a tool bundle written in php
 * with Symfony2 framework to abstract a mail service
 * usage. It prevent the mail service change.
 * 
 * PHP version 5.5
 * 
 * @category MailPart
 * @package  CscfaMailBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\MailBundle\Entity;

use Cscfa\Bundle\MailBundle\Entity\Originator;
use Cscfa\Bundle\MailBundle\Entity\Receiver;
use Cscfa\Bundle\MailBundle\Entity\Specificator;
use Cscfa\Bundle\MailBundle\Entity\Syntactic;
use Cscfa\Bundle\MailBundle\Entity\Usenet;
use Cscfa\Bundle\MailBundle\Entity\Encoder;

/**
 * Header class.
 *
 * The Header class is a composite
 * class containing email header
 * information.
 *
 * @category MailPart
 * @package  CscfaMailBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 * @link     https://tools.ietf.org/html/rfc2076
 */
class Header
{
    /**
     * The originator.
     * 
     * This property contain the
     * originator informations.
     * 
     * @var Originator
     */
    protected $originator;
    
    /**
     * The receiver.
     * 
     * This property contain the
     * receiver informations.
     * 
     * @var Receiver
     */
    protected $receiver;
    
    /**
     * The specificator.
     * 
     * This property contain the
     * specific informations of
     * the message.
     * 
     * @var Specificator
     */
    protected $specificator;
    
    /**
     * The syntactic.
     * 
     * This property contain the
     * syntactic informations of
     * the message.
     * 
     * @var Syntactic
     */
    protected $syntactic;
    
    /**
     * The usenet.
     * 
     * This property contain the
     * newsgroup USENET network
     * informations.
     * 
     * @var Usenet
     */
    protected $usenet;
    
    /**
     * The encoder.
     * 
     * This property contain the
     * encoding message informations.
     * 
     * @var Encoder
     */
    protected $encoder;
    
    /**
     * Encoder default constructor.
     * 
     * This constructor initialize the
     * contained property.
     */
    public function __construct()
    {
        $this->originator = new Originator();
        $this->receiver = new Receiver();
        $this->specificator = new Specificator();
        $this->syntactic = new Syntactic();
        $this->usenet = new Usenet();
        $this->encoder = new Encoder();
    }

    /**
     * Get the originator.
     * 
     * This method return the current
     * message originator.
     * 
     * @return Originator
     */
    public function getOriginator()
    {
        return $this->originator;
    }

    /**
     * Set the originator.
     * 
     * This method allow to set the current
     * message originator.
     * 
     * @param Originator $originator the current message Originator
     * 
     * @return Header
     */
    public function setOriginator(Originator $originator)
    {
        $this->originator = $originator;
        return $this;
    }

    /**
     * Get the receiver.
     * 
     * This method return the current
     * message receiver.
     * 
     * @return Receiver
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * Set the receiver.
     * 
     * This method allow to set the current
     * message receiver.
     * 
     * @param Receiver $receiver the current message receiver
     * 
     * @return Header
     */
    public function setReceiver(Receiver $receiver)
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * Get the specificator.
     * 
     * This method return the current
     * message specificator.
     * 
     * @return Specificator
     */
    public function getSpecificator()
    {
        return $this->specificator;
    }

    /**
     * Set the specificator.
     * 
     * This method allow to set the current
     * message specificator.
     * 
     * @param Specificator $specificator the current message specificator
     * 
     * @return Header
     */
    public function setSpecificator(Specificator $specificator)
    {
        $this->specificator = $specificator;
        return $this;
    }

    /**
     * Get the syntactic.
     * 
     * This method return the current
     * message syntactic.
     * 
     * @return Syntactic
     */
    public function getSyntactic()
    {
        return $this->syntactic;
    }

    /**
     * Set the syntactic.
     * 
     * This method allow to set the current
     * message syntactic.
     * 
     * @param Syntactic $syntactic the current message syntactic
     * 
     * @return Header
     */
    public function setSyntactic(Syntactic $syntactic)
    {
        $this->syntactic = $syntactic;
        return $this;
    }

    /**
     * Get the usenet.
     * 
     * This method return the current
     * message usenet.
     * 
     * @return Usenet
     */
    public function getUsenet()
    {
        return $this->usenet;
    }

    /**
     * Set the usenet.
     * 
     * This method allow to set the current
     * message usenet.
     * 
     * @param Usenet $usenet the current message usenet
     * 
     * @return Header
     */
    public function setUsenet(Usenet $usenet)
    {
        $this->usenet = $usenet;
        return $this;
    }

    /**
     * Get the encoder.
     * 
     * This method return the current
     * message encoder.
     * 
     * @return Encoder
     */
    public function getEncoder()
    {
        return $this->encoder;
    }

    /**
     * Set the encoder.
     * 
     * This method allow to set the current
     * message encoder.
     * 
     * @param Encoder $encoder the current message encoder
     * 
     * @return Header
     */
    public function setEncoder(Encoder $encoder)
    {
        $this->encoder = $encoder;
        return $this;
    }
 
}
