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
 * Specificator class.
 *
 * The Specificator class indicate
 * the mail reference specification.
 *
 * @category MailPart
 * @package  CscfaMailBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class Specificator
{
    /**
     * Unique message id.
     * 
     * this property contain a unique 
     * identifier refers to a unique 
     * message instance.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc822
     */
    protected $messageId;
    
    /**
     * Previous correspondence identity.
     * 
     * The contents of this property identify
     * previous correspondence which this  
     * message answers. Note that if message 
     * identifiers are used in this field, 
     * they must use the mach-id specification 
     * format.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc822
     */
    protected $inReplyTo;
    
    /**
     * Message references.
     * 
     * The contents of this property identify other
     * correspondence which this message references.
     * Note that if message identifiers are used, 
     * they must use the mach-id specification format.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc822
     */
    protected $references;
    
    /**
     * Message keywords.
     * 
     * This property contains keywords or phrases, 
     * separated by commas
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc822
     */
    protected $keywords;
    
    /**
     * Message sending date.
     * 
     * This property indicate the message date
     * and time when it send.
     * 
     * @var  \DateTime
     * @link https://tools.ietf.org/html/rfc822
     */
    protected $date;
    
    /**
     * Message importance.
     * 
     * Indicates the requested priority to be 
     * given by the receiving system. The 
     * case-insensitive values "low", 
     * "normal" and "high" are specified. If 
     * no special importance is requested, 
     * this header may be omitted and the 
     * value assumed to be "normal".
     * 
     * @var string
     * @link https://tools.ietf.org/html/rfc1911
     */
    protected $importance;
    
    /**
     * Message sensitivity.
     * 
     * The sensitivity header, if present, 
     * indicates the requested privacy level.  
     * The case-insensitive values "Personal" 
     * and "Private" are specified. If no 
     * privacy is requested, this field is 
     * omitted.
     * 
     * @var string
     * @link https://tools.ietf.org/html/rfc1911
     */
    protected $sensitivity;

    /**
     * Encoder default constructor.
     *
     * This constructor initialize the
     * contained property to null.
     */
    public function __construct()
    {
        $this->messageId = null;
        $this->inReplyTo = null;
        $this->references = null;
        $this->keywords = null;
        $this->date = new \DateTime();
        $this->importance = null;
        $this->sensitivity = null;
    }
    
    /**
     * Get the unique message id.
     * 
     * this method return the unique 
     * identifier refers to a unique 
     * message instance.
     * 
     * @return string
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * Set the unique message id.
     * 
     * this method allow to set the unique 
     * identifier refers to a unique 
     * message instance.
     * 
     * @param string $messageId the unique message id.
     * 
     * @return Specificator
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
        return $this;
    }

    /**
     * Get the previous correspondence identity.
     * 
     * This method return the contents of 
     * this property identify previous 
     * correspondence which this message 
     * answers.
     * 
     * @return string
     */
    public function getInReplyTo()
    {
        return $this->inReplyTo;
    }

    /**
     * Set the previous correspondence identity.
     * 
     * This method allow to set the contents of 
     * this property identify previous 
     * correspondence which this message 
     * answers.
     * 
     * @param string $inReplyTo the previous correspondence identity.
     * 
     * @return Specificator
     */
    public function setInReplyTo($inReplyTo)
    {
        $this->inReplyTo = $inReplyTo;
        return $this;
    }

    /**
     * Get the message references.
     * 
     * This method return the identity of the other
     * correspondence which this message references.
     * 
     * @return string
     */
    public function getReferences()
    {
        return $this->references;
    }

    /**
     * Set the message references.
     * 
     * This method allow to set the identity of the other
     * correspondence which this message references.
     * 
     * @param string $references the message references
     * 
     * @return Specificator
     */
    public function setReferences($references)
    {
        $this->references = $references;
        return $this;
    }

    /**
     * Get the message keywords.
     * 
     * This method return the keywords or phrases
     * of the current message, separated by commas.
     * 
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set the message keywords.
     * 
     * This method allow to set the keywords or phrases
     * of the current message, separated by commas.
     * 
     * @param string $keywords the message keywords
     * 
     * @return Specificator
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * Get the message sending date.
     * 
     * This method return the message date
     * and time when it send.
     * 
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the message sending date.
     * 
     * This method allow to set the message date
     * and time when it send.
     * 
     * @param \DateTime $date the message sending date
     * 
     * @return Specificator
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get the message importance.
     * 
     * This method return the requested 
     * priority to be given by the 
     * receiving system.
     * 
     * @return string
     */
    public function getImportance()
    {
        return $this->importance;
    }

    /**
     * Set the message importance.
     * 
     * This method allow to set the 
     * requested priority to be given 
     * by the receiving system.
     * 
     * @param string $importance the message importance
     * 
     * @return Specificator
     */
    public function setImportance($importance)
    {
        $this->importance = $importance;
        return $this;
    }

    /**
     * Get the message sensitivity.
     * 
     * This method return the sensitivity 
     * header value. If present, indicates 
     * the requested privacy level.
     * 
     * @return string
     */
    public function getSensitivity()
    {
        return $this->sensitivity;
    }

    /**
     * Set the message sensitivity.
     * 
     * This method allow to set the sensitivity 
     * header value. If present, indicates 
     * the requested privacy level.
     * 
     * @param string $sensitivity the message sensitivity
     * 
     * @return Specificator
     */
    public function setSensitivity($sensitivity)
    {
        $this->sensitivity = $sensitivity;
        return $this;
    }
 
}
