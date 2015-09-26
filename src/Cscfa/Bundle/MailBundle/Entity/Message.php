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

use Cscfa\Bundle\MailBundle\Entity\Header;

/**
 * Message class.
 *
 * The Message class indicate
 * the main mail element.
 *
 * @category MailPart
 * @package  CscfaMailBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class Message
{
    /**
     * The message header.
     * 
     * This property represent the
     * current message header.
     * 
     * @var Header
     */
    protected $header;
    
    /**
     * Message default constructor.
     * 
     * This constructor initialize the
     * contained property.
     */
    public function __construct()
    {
        $this->header = new Header();
    }
}