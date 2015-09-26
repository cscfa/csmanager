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
 * Originator class.
 *
 * The Originator class indicate
 * the mail originator informations.
 *
 * @category MailPart
 * @package  CscfaMailBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class Originator
{
    /**
     * The person that send the message.
     * 
     * This property contain the identity
     * of the person who send the current
     * message. If this is not set, the
     * sender property must be set to 
     * successfully send the current message.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc822
     */
    protected $from;
    
    /**
     * The person that send the message.
     * 
     * This property contain the identity
     * of the person who send the current
     * message. If this is not set, the
     * from property must be set to 
     * successfully send the current message.
     * 
     * Optionnaly, this property can be set
     * to indicate the sender if it not the
     * current message author.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc822
     */
    protected $sender;
    
    /**
     * The mailbow to sent the response.
     * 
     * This property contain any mailbox
     * who's can receive the response. It
     * is an optional parameter for the
     * successfully message sending.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc822
     */
    protected $replyTo;

    /**
     * Encoder default constructor.
     *
     * This constructor initialize the
     * contained property to null.
     */
    public function __construct()
    {
        $this->from = null;
        $this->sender = null;
        $this->replyTo = null;
    }
    
    /**
     * Get the person that send the message.
     * 
     * This method return the identity
     * of the person who send the current
     * message.
     * 
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set the person that send the message.
     * 
     * This method allow to set the identity
     * of the person who send the current
     * message.
     * 
     * @param string $from the person that send the message.
     * 
     * @return Originator
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Get the person that send the message.
     * 
     * This method return the identity
     * of the person who send the current
     * message.
     * 
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set the person that send the message.
     * 
     * This method allow to set the identity
     * of the person who send the current
     * message.
     * 
     * @param string $sender the person that send the message.
     * 
     * @return Originator
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * Get the mailbow to sent the response.
     * 
     * This method return any mailbox
     * who's can receive the response.
     * 
     * @return string
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * Set the mailbow to sent the response.
     * 
     * This method allow to set any mailbox
     * who's can receive the response.
     * 
     * @param string $replyTo the mailbow to sent the response.
     * 
     * @return Originator
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;
        return $this;
    }
 
}
