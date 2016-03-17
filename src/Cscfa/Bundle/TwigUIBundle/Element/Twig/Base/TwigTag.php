<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category TwigTag
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Element\Twig\Base;

use Cscfa\Bundle\TwigUIBundle\Element\BaseInterface\NestedInterface;
use Cscfa\Bundle\TwigUIBundle\Element\BaseInterface\SubTagedInterface;

/**
 * TwigTag class.
 *
 * The TwigTag class is
 * used to create a twig
 * element.
 *
 * @category TwigTag
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
abstract class TwigTag implements NestedInterface, SubTagedInterface
{
    /**
     * Nest level.
     *
     * This property indicate the
     * current nest level.
     *
     * @var int
     */
    protected $nestedLevel;

    /**
     * Set nested level.
     *
     * This method allow to
     * set the current tag
     * nesting level.
     *
     * @param int $nestedLevel The nesteing level
     *
     * @return mixed
     */
    public function setNestedLevel($nestedLevel)
    {
        $this->nestedLevel = $nestedLevel;

        return $this;
    }

    /**
     * Get nested level.
     *
     * This method return
     * the current tag nesting
     * level.
     *
     * @return int
     */
    public function getNestedLevel()
    {
        return $this->nestedLevel;
    }

    /**
     * Get sub tags.
     *
     * Return all of the Tag
     * childs.
     *
     * @return array
     */
    public function getSubTags()
    {
        return array();
    }

    /**
     * Get sub tags count.
     *
     * Return the sub tags count.
     *
     * @return int
     */
    public function getSubTagsCount()
    {
        return 0;
    }

    /**
     * To string.
     *
     * This method return the current
     * instance parsed as string.
     *
     * @return string
     */
    abstract public function __toString();
}
