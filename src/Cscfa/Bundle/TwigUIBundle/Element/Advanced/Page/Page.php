<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Tag
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Element\Advanced\Page;

use Cscfa\Bundle\TwigUIBundle\Element\Base\Tag;

/**
 * Page class.
 *
 * The Page class is used
 * to create an html page.
 *
 * @category Tag
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
class Page extends Tag
{
    /**
     * The doctype.
     *
     * This property represent the
     * current page doctype.
     *
     * HTML5 by default.
     *
     * @var Doctype
     */
    protected $doctype;

    /**
     * Default constructor.
     *
     * This constructor initialize
     * the properties.
     *
     * @param string $tagName The tagName of the page. (HTML as default)
     * @param int    $doctype The doctype value. (Doctype::HTML5 as default)
     */
    public function __construct($tagName = 'html', $doctype = Doctype::HTML5)
    {
        parent::__construct($tagName);

        $this->doctype = new Doctype($doctype);
    }

    /**
     * Get doctype.
     *
     * This method return the
     * current page Doctype
     * instance.
     *
     * @return Doctype
     */
    public function getDoctype()
    {
        return $this->doctype;
    }

    /**
     * Set doctype.
     *
     * This method allow to set
     * the current Page Doctype
     * instance.
     *
     * @param Doctype $doctype The Doctype instance
     *
     * @return Page
     */
    public function setDoctype(Doctype $doctype)
    {
        $this->doctype = $doctype;

        return $this;
    }

    /**
     * Compute tag.
     *
     * This method return a string
     * that contain the current tag
     * HTML representation.
     *
     * @return string
     */
    protected function computeTag()
    {
        $this->doctype->setNestedLevel($this->nestedLevel);

        return $this->doctype->__toString().parent::computeTag();
    }
}
