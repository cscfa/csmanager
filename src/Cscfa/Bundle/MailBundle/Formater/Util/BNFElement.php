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
 * @category   FormaterTool
 *
 * @author     Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license    http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link       http://cscfa.fr
 */

namespace Cscfa\Bundle\MailBundle\Formater\Util;

/**
 * BNFElement class.
 *
 * The BNFElement class provide
 * default abstraction for
 * backus-Naur format.
 *
 * @category FormaterTool
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class BNFElement
{
    /**
     * The element name.
     *
     * This property represnet
     * the element name.
     *
     * @var string
     */
    protected $name;

    /**
     * The element litteral string.
     *
     * This property represent
     * the element litteral
     * string.
     *
     * @var string
     */
    protected $litteral;

    /**
     * The element comment.
     *
     * This property represent
     * the element comment.
     *
     * @var string
     */
    protected $comment;

    /**
     * Default constructor.
     *
     * This constructor initialize
     * the element properties.
     */
    public function __construct()
    {
        $this->name = '';
        $this->litteral = '';
        $this->comment = '';
    }

    /**
     * Get the element name.
     *
     * This method return
     * the element name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the element name.
     *
     * This method allow to
     * set the element name.
     *
     * @param string $name The element name
     *
     * @return BNFElement
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the element litteral name.
     *
     * This method return
     * the element litteral
     * name.
     *
     * @return string
     */
    public function getLitteral()
    {
        return $this->litteral;
    }

    /**
     * Set the element litteral.
     *
     * This method allow to
     * set the element litteral.
     *
     * @param string $litteral The element litteral
     *
     * @return BNFElement
     */
    public function setLitteral($litteral)
    {
        $this->litteral = $litteral;

        return $this;
    }

    /**
     * Get the element comment.
     *
     * This method return
     * the element comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set the element comment.
     *
     * This method allow to
     * set the element comment.
     *
     * @param string $comment The element comment
     *
     * @return BNFElement
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Has name.
     *
     * This method check if
     * the current element
     * has name.
     *
     * @return bool
     */
    public function hasName()
    {
        return !empty($this->name);
    }

    /**
     * Has litteral.
     *
     * This method check if
     * the current element
     * has litteral.
     *
     * @return bool
     */
    public function hasLitteral()
    {
        return !empty($this->litteral);
    }

    /**
     * Has comment.
     *
     * This method check if
     * the current element
     * has comment.
     *
     * @return bool
     */
    public function hasComment()
    {
        return !empty($this->comment);
    }

    /**
     * Has significant.
     *
     * This method check if
     * the current element
     * contain a significant
     * value.
     *
     * @return bool
     */
    public function hasSignificant()
    {
        if ($this->hasLitteral() || $this->hasName()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get significant.
     *
     * This method return
     * the current significant
     * value if exist.
     *
     * @return string|null
     */
    public function getSignificant()
    {
        if ($this->hasLitteral()) {
            return $this->litteral;
        } elseif ($this->hasName()) {
            return $this->name;
        } else {
            return;
        }
    }

    /**
     * Has label.
     *
     * This method check if the current
     * element contain a label.
     *
     * @return bool
     */
    public function hasLabel()
    {
        if ($this->hasLitteral() && $this->hasName()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get label.
     *
     * This method return
     * the current label
     * value if exist.
     *
     * @return string|null
     */
    public function getLabel()
    {
        if ($this->hasLitteral() && $this->hasName()) {
            return $this->name;
        } else {
            return;
        }
    }

    /**
     * Litteral to name.
     *
     * This method assign the
     * current litteral value
     * to name and remove it.
     */
    public function litteralToName()
    {
        if ($this->hasLitteral()) {
            $this->setName($this->getLitteral())
                ->setLitteral('');
        }
    }
}
