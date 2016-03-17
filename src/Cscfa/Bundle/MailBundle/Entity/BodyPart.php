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
 * BodyPart class.
 *
 * The BodyPart class contain
 * the body parts elements of
 * a message.
 *
 * @category MailPart
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class BodyPart
{
    /**
     * The body part definition.
     *
     * This property inform on
     * the body part syntactic
     * elements.
     *
     * @var Syntactic
     */
    protected $syntactic;

    /**
     * The body content.
     *
     * This property represent
     * the body part content.
     *
     * @var string
     */
    protected $content;

    /**
     * Default constructor.
     *
     * This BodyPart default constructor
     * initialize the properties.
     */
    public function __construct()
    {
        $this->syntactic = new Syntactic();
        $this->content = '';
    }

    /**
     * Get syntactic.
     *
     * This method allow to get
     * the current message part
     * syntactic definition.
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
     * the current body part
     * syntactic definition.
     *
     * @param Syntactic $syntactic The new syntactic instance
     *
     * @return BodyPart
     */
    public function setSyntactic(Syntactic $syntactic)
    {
        $this->syntactic = $syntactic;

        return $this;
    }

    /**
     * Get content.
     *
     * This method allow to get
     * the current body part
     * content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content.
     *
     * This method allow to set
     * the current body part
     * content.
     *
     * @param string $content The new content
     *
     * @return BodyPart
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
}
