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
 * Encoder class.
 *
 * The Encoder class indicate informations
 * under the message encoding.
 *
 * @category MailPart
 * @package  CscfaMailBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class Encoder
{
    /**
     * The encoding definition.
     * 
     * The Encoding property consists of one 
     * or more subfields, separated by commas. 
     * Each subfield corresponds to a part of 
     * the message, in the order of that 
     * part's appearance. A subfield consists 
     * of a line count and a keyword or a 
     * series of nested keywords defining the 
     * encoding. The line count is optional 
     * in the last subfield.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc1505
     */
    protected $encoding;
    
    /**
     * The message content md5.
     * 
     * If the Content-MD5 field is present, a 
     * recipient user agent may choose to use 
     * it to verify that the contents of a 
     * MIME entity have not been modified 
     * during transport.  Message relays and 
     * gateways are expressly forbidden to 
     * alter their processing based on the 
     * presence of the Content-MD5 field.
     * 
     * @var  string
     * @link https://tools.ietf.org/html/rfc1864
     */
    protected $contentMD5;
    
    /**
     * Encoder default constructor.
     * 
     * This constructor initialize the
     * contained property to null.
     */
    public function __construct()
    {
        $this->encoding = null;
        $this->contentMD5 = null;
    }

    /**
     * Get the encoding definition.
     * 
     * This method return a list of subfield 
     * that corresponding to each message 
     * part encoding.
     * 
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Set the encoding definition.
     * 
     * This method allow to set a list of 
     * subfield that corresponding to each 
     * message part encoding.
     * 
     * @param string $encoding the encoding definition
     * 
     * @return Encoder
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * Get the content md5 hach.
     * 
     * This method return the md5 hach of 
     * the message content that allow to 
     * validate it.
     * 
     * @return string
     */
    public function getContentMD5()
    {
        return $this->contentMD5;
    }

    /**
     * Set the content md5 hach.
     *
     * This method allow to set the md5 
     * hach of the message content that 
     * allow to validate it.
     *
     * @param string $contentMD5 the content md5 hach
     * 
     * @return Encoder
     */
    public function setContentMD5($contentMD5)
    {
        $this->contentMD5 = $contentMD5;
        return $this;
    }
 
}
