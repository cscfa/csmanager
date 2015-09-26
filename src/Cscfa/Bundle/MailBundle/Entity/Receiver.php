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

/**
 * Receiver class.
 *
 * The Receiver class indicate
 * the mail receiver informations.
 *
 * @category MailPart
 * @package  CscfaMailBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class Receiver
{
    /**
     * Message receiver.
     * 
     * This property indicate the
     * identity of the primary 
     * receivers of the message.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc822
     */
    protected $to;
    
    /**
     * Message receiver.
     * 
     * This property indicate the
     * identity of the secondary 
     * receivers of the message.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc822
     */
    protected $cc;
    
    /**
     * Message additional receiver.
     * 
     * This property indicate the
     * identity of the additional
     * message receivers.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc822
     */
    protected $bcc;

    /**
     * Encoder default constructor.
     *
     * This constructor initialize the
     * contained property to null.
     */
    public function __construct()
    {
        $this->to = null;
        $this->cc = null;
        $this->bcc = null;
    }
    
    /**
     * Get the message receiver.
     * 
     * This method return the
     * identity of the primary 
     * receivers of the message.
     * 
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set the message receiver.
     * 
     * This method allow to set the
     * identity of the primary 
     * receivers of the message.
     * 
     * @param string $to the message receiver
     * 
     * @return Receiver
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * Get the message receiver.
     * 
     * This method return the
     * identity of the secondary 
     * receivers of the message.
     * 
     * @return string
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * Set the message receiver.
     * 
     * This method allow to set the
     * identity of the secondary 
     * receivers of the message.
     * 
     * @param string $cc the message receiver
     * 
     * @return Receiver
     */
    public function setCc($cc)
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * Get the message additional receiver.
     * 
     * This method return the
     * identity of the additional
     * message receivers.
     * 
     * @return string
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * Set the message additional receiver.
     * 
     * This method allow to set the
     * identity of the additional
     * message receivers.
     * 
     * @param string $bcc the message additional receiver
     * 
     * @return Receiver
     */
    public function setBcc($bcc)
    {
        $this->bcc = $bcc;
        return $this;
    }
 
}
