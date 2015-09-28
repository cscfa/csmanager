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
use Cscfa\Bundle\ToolboxBundle\Set\TypedList;
use Cscfa\Bundle\MailBundle\Entity\BodyPart;

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
     * The message body.
     * 
     * @var TypedList
     */
    protected $bodyParts;
    
    /**
     * Message default constructor.
     * 
     * This constructor initialize the
     * contained property.
     */
    public function __construct()
    {
        $this->header = new Header();
        $this->bodyParts = new TypedList(new BodyPart());
    }

    /**
     * Get header.
     * 
     * This method allow to get
     * the current message header.
     * 
     * @return Header
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set header.
     * 
     * This method allow to set
     * the current message header.
     * 
     * @param Header $header The new Header instance
     * 
     * @return Message
     */
    protected function setHeader(Header $header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * Get body parts.
     *
     * This method allow to get
     * the current message body
     * parts.
     *
     * @return Header
     */
    public function getBodyParts()
    {
        return $this->bodyParts;
    }

    /**
     * Set body parts.
     *
     * This method allow to set
     * the current message body
     * parts.
     *
     * @param TypedList $bodyParts The new bodyParts TypedList instance.
     * 
     * @return \Cscfa\Bundle\MailBundle\Entity\Message
     */
    protected function setBodyParts(TypedList $bodyParts)
    {
        $this->bodyParts = $bodyParts;
        return $this;
    }
 
}
