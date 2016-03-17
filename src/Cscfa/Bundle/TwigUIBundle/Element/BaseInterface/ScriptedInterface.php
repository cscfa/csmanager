<?php
/**
 * This file is a part of CSCFA TwigUi project.
 *
 * The TwigUi project is a twig builder written in php
 * with Symfony2 framework.
 *
 * PHP version 5.5
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 *
 * @link     http://cscfa.fr
 */

namespace Cscfa\Bundle\TwigUIBundle\Element\BaseInterface;

/**
 * ScriptedInterface interface.
 *
 * The ScriptedInterface interface
 * define scripting methods.
 *
 * @category Interface
 *
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @link     http://cscfa.fr
 */
interface ScriptedInterface
{
    /**
     * Has script link.
     *
     * This method indicate the
     * script link existance
     * of the current instance.
     *
     * @return bool
     */
    public function hasScriptLink();

    /**
     * Get script link.
     *
     * This method return all
     * of the existings script
     * links of the current
     * instance.
     *
     * @return array
     */
    public function getScriptLink();

    /**
     * Get script link count.
     *
     * This method return the
     * current instance script
     * links counts.
     *
     * @return int
     */
    public function getScriptLinkCount();

    /**
     * Get custom script.
     *
     * This method indicate the
     * existance of customized
     * script.
     *
     * @return bool
     */
    public function hasCustomScript();

    /**
     * Get custom script.
     *
     * This method return the
     * current instance custom
     * script.
     *
     * @return string
     */
    public function getCustomScript();

    /**
     * Get custom script length.
     *
     * This method return the
     * current instance custom
     * script length.
     *
     * @return int
     */
    public function getCustomScriptLength();
}
