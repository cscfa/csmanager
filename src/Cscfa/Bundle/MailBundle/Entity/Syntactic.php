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
 * Syntactic class.
 *
 * The Syntactic class indicate
 * the mail syntactic elements.
 *
 * @category MailPart
 * @package  CscfaMailBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class Syntactic
{
    /**
     * The message subject.
     * 
     * This property summarize the
     * current message.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc822
     */
    protected $subject;
    
    /**
     * The message comments.
     * 
     * This property allow to comment
     * the current message without
     * disturbing the content.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc822
     */
    protected $comments;
    
    /**
     * The message content type.
     * 
     * This property indicate the 
     * structuring technique of
     * the message content.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc1049
     */
    protected $contentType;
    
    /**
     * The description of the message content.
     * 
     * This property bring the ability to 
     * associate some descriptive information 
     * with a given body is often desirable. 
     * For example, it may be useful to mark 
     * an "image" body as "a picture of the 
     * Space Shuttle Endeavor." Such text may 
     * be placed in the Content-Description 
     * header field.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc1521
     */
    protected $contentDescription;
    
    /**
     * The high-level body reference.
     * 
     * In constructing a high-level user agent, 
     * it may be desirable to allow one body 
     * to make reference to another. 
     * Accordingly, bodies may be labeled 
     * using the "Content-ID" header field, 
     * which is syntactically identical to 
     * the "Message-ID" header field.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc1521
     */
    protected $contentID;
    
    /**
     * The content transfer encoding of the current message body.
     * 
     * Many Content-Types which could 
     * usefully be transported via email are 
     * represented, in their "natural" format, 
     * as 8-bit character or binary data.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc1521
     */
    protected $contentTransferEncoding;
    
    /**
     * The message content MIME version.
     * 
     * The presence of this header field is 
     * an assertion that the message has been 
     * composed in compliance with the 
     * defined MIME type.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc1521
     */
    protected $MIMEVersion;
    
    /**
     * The message content disposition.
     * 
     * Content-Disposition is an optional 
     * header. In its absence, the MUA may 
     * use whatever presentation method it 
     * deems suitable.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc1806
     */
    protected $contentDisposition;

    /**
     * Encoder default constructor.
     *
     * This constructor initialize the
     * contained property to null.
     */
    public function __construct()
    {
        $this->subject = null;
        $this->comments = null;
        $this->contentType = null;
        $this->contentDescription = null;
        $this->contentID = null;
        $this->contentTransferEncoding = null;
        $this->MIMEVersion = null;
        $this->contentDisposition = null;
    }
    
    /**
     * Get the message subject.
     * 
     * This method return the current
     * message summary.
     * 
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set the message subject.
     * 
     * This method allow to set the 
     * current message summary.
     * 
     * @param string $subject the message subject
     * 
     * @return Syntactic
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Get the message comments.
     * 
     * This method return the comment
     * of the current message.
     * 
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set the message comments.
     * 
     * This method allow to set the 
     * comment of the current message.
     * 
     * @param string $comments the message comments
     * 
     * @return Syntactic
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * Get the message content type.
     * 
     * This method return the 
     * structuring technique of
     * the message content.
     * 
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set the message content type.
     * 
     * This method allow to set 
     * the structuring technique 
     * of the message content.
     * 
     * @param string $contentType the message content type
     * 
     * @return Syntactic
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * Get the description of the message content.
     * 
     * This method return some descriptive 
     * information with a given body. 
     * 
     * @return string
     */
    public function getContentDescription()
    {
        return $this->contentDescription;
    }

    /**
     * Set the description of the message content.
     * 
     * This method allow to set some 
     * descriptive information with a 
     * given body.
     * 
     * @param string $contentDescription the description of the message content
     * 
     * @return Syntactic
     */
    public function setContentDescription($contentDescription)
    {
        $this->contentDescription = $contentDescription;
        return $this;
    }

    /**
     * Get the high-level body reference.
     * 
     * This method return the high-level user 
     * agent body reference to another. 
     * 
     * @return string
     */
    public function getContentID()
    {
        return $this->contentID;
    }

    /**
     * Set the high-level body reference.
     * 
     * This method allow to set the high-level 
     * user agent body reference to another. 
     * 
     * @param string $contentID the high-level body reference
     * 
     * @return \Cscfa\Bundle\MailBundle\Entity\Syntactic
     */
    public function setContentID($contentID)
    {
        $this->contentID = $contentID;
        return $this;
    }

    /**
     * Get the content transfer encoding of the current message body
     * 
     * This method return the content 
     * transfer encoding of the current 
     * message body.
     * 
     * @return string
     */
    public function getContentTransferEncoding()
    {
        return $this->contentTransferEncoding;
    }

    /**
     * Set the content transfer encoding of the current message body
     * 
     * This method allow to set the content 
     * transfer encoding of the current 
     * message body.
     * 
     * @param string $contentTransferEncoding the content transfer encoding of the current message body
     * 
     * @return Syntactic
     */
    public function setContentTransferEncoding($contentTransferEncoding)
    {
        $this->contentTransferEncoding = $contentTransferEncoding;
        return $this;
    }

    /**
     * Get the message content MIME version.
     * 
     * This method return the message content 
     * MIME version.
     * 
     * @return string
     */
    public function getMIMEVersion()
    {
        return $this->MIMEVersion;
    }

    /**
     * Set the message content MIME version.
     * 
     * This method allow to set the message 
     * content MIME version.
     * 
     * @param string $MIMEVersion the message content MIME version
     * 
     * @return Syntactic
     */
    public function setMIMEVersion($MIMEVersion)
    {
        $this->MIMEVersion = $MIMEVersion;
        return $this;
    }

    /**
     * Get the message content disposition.
     * 
     * This method return the message content 
     * disposition.
     * 
     * @return string
     */
    public function getContentDisposition()
    {
        return $this->contentDisposition;
    }

    /**
     * Set the message content disposition.
     * 
     * This method allow to set the message 
     * content disposition.
     * 
     * @param string $contentDisposition the message content disposition
     * 
     * @return Syntactic
     */
    public function setContentDisposition($contentDisposition)
    {
        $this->contentDisposition = $contentDisposition;
        return $this;
    }
 
}
