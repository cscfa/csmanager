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

use Cscfa\Bundle\MailBundle\Entity\Syntactic;

/**
 * Attachment class.
 *
 * The Attachment class indicate
 * the main mail file attachment.
 *
 * @category MailPart
 * @package  CscfaMailBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class Attachment
{
    /**
     * The file name.
     * 
     * This property indicate the
     * current attachment file name.
     * 
     * @var string
     */
    protected $fileName;
    
    /**
     * The syntactic.
     * 
     * This property indicate the
     * syntactic properties of
     * the current attachment.
     * 
     * @var Syntactic
     */
    protected $syntactic;
    
    /**
     * The attachment content.
     * 
     * This property contain the
     * current attachment content.
     * 
     * @var string
     */
    protected $content;
    
    /**
     * Default constructor.
     * 
     * This constructor initialize
     * the properties.
     */
    public function __construct()
    {
        $this->fileName = "";
        $this->syntactic = new Syntactic();
        $this->content = "";
    }

    /**
     * Get filename.
     * 
     * This method return the
     * current attachment
     * file name.
     * 
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set filename.
     * 
     * This method allow to set
     * the current attachment
     * file name.
     * 
     * @param string $fileName The attachment file name
     * 
     * @return Attachment
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * Get syntactic.
     * 
     * This method return the 
     * current attachment syntactic.
     * 
     * @return Syntactic
     */
    public function getSyntactic()
    {
        return $this->syntactic;
    }

    /**
     * Set syntactic.
     * 
     * This method allow to set
     * the current attachment
     * syntactic.
     * 
     * @param Syntactic $syntactic The new Syntactic instance
     * 
     * @return Attachment
     */
    public function setSyntactic(Syntactic $syntactic)
    {
        $this->syntactic = $syntactic;
        return $this;
    }

    /**
     * Get content.
     * 
     * This method return the
     * current attachment
     * content.
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set syntactic.
     * 
     * This method allow to set
     * the current attachment
     * syntactic.
     * 
     * @param string $content The current attachment content
     * 
     * @return Attachment
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
 
}