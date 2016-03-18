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
 * @category   MailPart
 *
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link       http://cscfa.fr
 */

namespace Cscfa\Bundle\MailBundle\Entity;

/**
 * Receiver class.
 *
 * The Receiver class indicate
 * the mail receiver informations.
 *
 * @category MailPart
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
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
     * @var string
     *
     * @link https://tools.ietf.org/html/rfc822
     */
    protected $receiver;

    /**
     * Message receiver.
     *
     * This property indicate the
     * identity of the secondary
     * receivers of the message.
     *
     * @var string
     *
     * @link https://tools.ietf.org/html/rfc822
     */
    protected $copyReceiver;

    /**
     * Message additional receiver.
     *
     * This property indicate the
     * identity of the additional
     * message receivers.
     *
     * @var string
     *
     * @link https://tools.ietf.org/html/rfc822
     */
    protected $additionalReceiver;

    /**
     * Encoder default constructor.
     *
     * This constructor initialize the
     * contained property to null.
     */
    public function __construct()
    {
        $this->receiver = null;
        $this->copyReceiver = null;
        $this->additionalReceiver = null;
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
        return $this->receiver;
    }

    /**
     * Set the message receiver.
     *
     * This method allow to set the
     * identity of the primary
     * receivers of the message.
     *
     * @param string $receiver the message receiver
     *
     * @return Receiver
     */
    public function setTo($receiver)
    {
        $this->receiver = $receiver;

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
        return $this->copyReceiver;
    }

    /**
     * Set the message receiver.
     *
     * This method allow to set the
     * identity of the secondary
     * receivers of the message.
     *
     * @param string $copyReceiver the message receiver
     *
     * @return Receiver
     */
    public function setCc($copyReceiver)
    {
        $this->copyReceiver = $copyReceiver;

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
        return $this->additionalReceiver;
    }

    /**
     * Set the message additional receiver.
     *
     * This method allow to set the
     * identity of the additional
     * message receivers.
     *
     * @param string $additionnalReceiver the message additional receiver
     *
     * @return Receiver
     */
    public function setBcc($additionnalReceiver)
    {
        $this->additionalReceiver = $additionnalReceiver;

        return $this;
    }
}
