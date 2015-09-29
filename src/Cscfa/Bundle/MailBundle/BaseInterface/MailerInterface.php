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
 * @category Interface
 * @package  CscfaMailBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\MailBundle\BaseInterface;

use Cscfa\Bundle\MailBundle\Entity\Message;

/**
 * MailerInterface interface.
 *
 * The MailerInterface interface
 * provide default method to process
 * mailing.
 * 
 * @category Interface
 * @package  CscfaMailBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
interface MailerInterface
{
    /**
     * Get message.
     * 
     * This method return the
     * current message instance.
     * 
     * @return Message
     */
    public function getMessage();
    
    /**
     * Set message.
     * 
     * This method allow to set the
     * current message instance.
     * 
     * @param Message $message The new message instance
     */
    public function setMessage(Message $message);
    
    /**
     * Mail.
     * 
     * This method proceed to
     * the mailing.
     * 
     * @return void
     */
    public function mail();
}
