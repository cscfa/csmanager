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

use Cscfa\Bundle\ToolboxBundle\Set\TypedList;

/**
 * Message class.
 *
 * The Message class indicate
 * the main mail element.
 *
 * @category MailPart
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
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
     * This property contain all
     * of the bodies parts.
     *
     * @var TypedList
     */
    protected $bodyParts;

    /**
     * The message boundary.
     *
     * This property conatin
     * the message boundary.
     *
     * @var string
     */
    protected $boundary;

    /**
     * The message subject.
     *
     * This property indicate
     * the message subject.
     *
     * @var string
     */
    protected $subject;

    /**
     * The message attachments.
     *
     * This property contain all
     * of the attachments.
     *
     * @var TypedList
     */
    protected $attachments;

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
        $this->boundary = '-----='.md5(rand());
        $this->subject = '';
        $this->attachments = new TypedList(new Attachment());
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
     * @return TypedList
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
     * @return Message
     */
    protected function setBodyParts(TypedList $bodyParts)
    {
        $this->bodyParts = $bodyParts;

        return $this;
    }

    /**
     * Get boundary.
     *
     * This method allow to get
     * the current message
     * boundary.
     *
     * @return string
     */
    public function getBoundary()
    {
        return $this->boundary;
    }

    /**
     * Set boundary.
     *
     * This method allow to set
     * the current message
     * boundary.
     *
     * @param string $boundary The message boundary
     *
     * @return Message
     */
    protected function setBoundary($boundary)
    {
        $this->boundary = $boundary;

        return $this;
    }

    /**
     * Get subject.
     *
     * This method allow to get
     * the current message
     * subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set subject.
     *
     * This method allow to set
     * the current message
     * subject.
     *
     * @param string $subject The new message subject.
     *
     * @return Message
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get attachment.
     *
     * This method return the
     * set of attachment of
     * the current message.
     *
     * @return TypedList
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Set attachment.
     *
     * This method allow to set
     * the set of attachment of
     * the current message.
     *
     * @param TypedList $attachments The new TypedList of attachment
     *
     * @return Message
     */
    public function setAttachments(TypedList $attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }
}
